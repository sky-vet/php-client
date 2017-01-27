<?php
namespace SkyVet;

use SkyVet\Model\Turno;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use \SkyVet\Model\Client as ClientModel;

/**
 * Class Client
 *
 * @package SkyVet
 */
class Client
{
    const GRANT_TYPE = 'http://app.sky.vet/grants/api_key';
    const SESSION_TOKEN = 'skyvet_token';
    const ENDPOINT_TURNOS = '/turnos';
    const ENDPOINT_CLIENTS = '/clientes';
    const BASE_URL = 'http://app.sky.vet/api/v1';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var string
     */
    private $authUrl = 'http://app.sky.vet/oauth/v2/auth';

    /**
     * @var string
     */
    private $tokenUrl = 'http://app.sky.vet/oauth/v2/token';

    /**
     * Client constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $apiKey
     */
    public function __construct($clientId, $clientSecret, $apiKey)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->apiKey = $apiKey;
        $this->session = new Session();
        $this->setToken(new Token());
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     *
     * @return void
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     *
     * @return void
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return void
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->authUrl;
    }

    /**
     * @param string $authUrl
     *
     * @return void
     */
    public function setAuthUrl($authUrl)
    {
        $this->authUrl = $authUrl;
    }

    /**
     * @return string
     */
    public function getTokenUrl()
    {
        return $this->tokenUrl;
    }

    /**
     * @param string $tokenUrl
     *
     * @return void
     */
    public function setTokenUrl($tokenUrl)
    {
        $this->tokenUrl = $tokenUrl;
    }

    /**
     * @return void
     */
    public function fetchAccessToken()
    {

        $url = $this->getTokenUrl();

        $params = [
            'grant_type'    => static::GRANT_TYPE,
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'api_key'       => $this->getApiKey()
        ];

        $url .= '?' . http_build_query($params);

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);
        $content = $response->getBody()->getContents();
        $data = json_decode($content);

        $token = $this->session->get(static::SESSION_TOKEN);
        $token->setAccessToken($data->access_token);
        $token->setRefreshToken($data->refresh_token);

        $date = new \DateTime();
        $date->add(new \DateInterval("PT" . $data->expires_in . "M"));
        $token->setExpirationDate($date);

        $this->setToken($token);
    }

    /**
     * @return Token
     */
    public function getToken()
    {
        $token = $this->session->get(self::SESSION_TOKEN);

        if ($token->isEmpty() || $token->isExpired()) {
            $this->fetchAccessToken();
        }

        return $this->session->get(self::SESSION_TOKEN);
    }

    /**
     * @param Token $token
     *
     * @return void
     */
    public function setToken(Token $token)
    {
        $this->session->set(self::SESSION_TOKEN, $token);
    }

    /**
     * @return mixed
     */
    public function fetchTurnos()
    {
        $token = $this->getToken();
        $accessToken = $token->getAccessToken();

        $url = static::BASE_URL . static::ENDPOINT_TURNOS;
        $params = [
            'access_token' => $accessToken
        ];
        $url.= '?' . http_build_query($params);
        $client = new \GuzzleHttp\Client();
        $data = $client->get($url)->getBody()->getContents();

        return json_decode($data);
    }

    /**
     * @param Turno $turno
     *
     * @return void
     */
    public function saveTurno(Turno &$turno)
    {

        $accessToken = $this->getAccessToken();
        $url = static::BASE_URL . static::ENDPOINT_TURNOS;
        $client = new \GuzzleHttp\Client([
            'query' => [
                'access_token' => $accessToken
            ]
        ]);
        $data = $client->request('POST', $url, [
            'form_params' => [
                'nombre'      => $turno->getNombre(),
                'fecha'       => $turno->getDate()->format('d/m/Y H:i'),
                'telefono'    => $turno->getTelefono(),
                'comentarios' => $turno->getComentarios()
            ]
        ])->getBody()->getContents();

        $obj = json_decode($data);

        if ($obj && isset($obj->id)) {
            $turno->setId($obj->id);
        }
    }

    /**
     * @param mixed $id
     *
     * @return mixed
     */
    public function deleteTurno($id)
    {
        $accessToken = $this->getAccessToken();
        $url = self::BASE_URL . self::ENDPOINT_TURNOS . '/' . $id;
        $client = new \GuzzleHttp\Client([
            'query' => [
                'access_token' => $accessToken
            ]
        ]);
        $data = $client->request(Request::METHOD_DELETE, $url)->getBody()->getContents();

        return $data;
    }

    /**
     * @return string|null
     */
    public function getAccessToken()
    {
        $token = $this->getToken();
        $accessToken = $token->getAccessToken();

        return $accessToken;
    }

    /**
     * @param $base
     * @param array $params
     *
     * @return string
     */
    private function buildUri($base, $params = [])
    {
        $url = $base. '?' . http_build_query($params);

        return $url;
    }

    /**
     * @param ClientModel $clientModel
     */
    public function saveClient(ClientModel &$clientModel)
    {
        $accessToken = $this->getAccessToken();
        $url = static::BASE_URL . static::ENDPOINT_CLIENTS;
        $client = new \GuzzleHttp\Client([
            'query' => [
                'access_token' => $accessToken
            ]
        ]);
        $data = $client->request(Request::METHOD_POST, $url, [
            'form_params' => [
                'nombre'              => $clientModel->getFirstName(),
                'apellido'            => $clientModel->getLastName(),
                'email'               => $clientModel->getEmail(),
                'localidad'           => $clientModel->getCity(),
                'telefono'            => $clientModel->getPhone(),
                'celular'             => $clientModel->getCellphone(),
                'direccion'           => $clientModel->getAddress(),
                'custom_fields'       => $clientModel->getCustomFields(),
                'unique_custom_field' => $clientModel->getUniqueCustomField(),
            ]
        ])->getBody()->getContents();

        $obj = json_decode($data, true);

        if ($obj && isset($obj['id'])) {
            $clientModel->setId($obj['id']);
            $clientModel->setCreatedAt(\DateTime::createFromFormat(\DateTime::ISO8601, $obj['created_at']));
        }
    }
}
