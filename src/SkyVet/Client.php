<?php
/**
 * Created by PhpStorm.
 * User: Hormiga
 * Date: 14/02/2016
 * Time: 12:37 PM
 */

namespace SkyVet;

use SkyVet\Model\Turno;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Client
{

    private $clientId;
    private $clientSecret;
    private $apiKey;
    private $session;

    private $authUrl = 'http://skyvet.dev/app_dev.php/oauth/v2/auth';

    private $tokenUrl = 'http://skyvet.dev/app_dev.php/oauth/v2/token';

    const GRANT_TYPE = 'http://sky.vet/grants/api_key';

    const SESSION_TOKEN = 'skyvet_token';

    const ENDPOINT_TURNOS = '/turnos';

    const BASE_URL = 'http://skyvet.dev/app_dev.php/api/v1';

    /**
     * Client constructor.
     * @param $clientId
     * @param $clientSecret
     * @param $apiKey
     */
    public function __construct($clientId, $clientSecret, $apiKey)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->apiKey = $apiKey;
        $session = new Session();
        $this->session = $session;
        $this->setToken(new Token());
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param mixed $clientSecret
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param mixed $apiKey
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
     */
    public function setTokenUrl($tokenUrl)
    {
        $this->tokenUrl = $tokenUrl;
    }

    public function fetchAccessToken()
    {

        $url = $this->getTokenUrl();

        $params = array(
            'grant_type' => self::GRANT_TYPE,
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'api_key' => $this->getApiKey()
        );

        $url .= '?' . http_build_query($params);

        $client = new \GuzzleHttp\Client();
        $response = $client->get($url);
        $content = $response->getBody()->getContents();
        $data = json_decode($content);

        $token = $this->session->get(self::SESSION_TOKEN);
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
        if ($token->isEmpty() || $token->isExpired())
        {
            $this->fetchAccessToken();
        }
        return $this->session->get(self::SESSION_TOKEN);
    }

    /**
     * @param Token $token
     */
    public function setToken($token)
    {
        $this->session->set(self::SESSION_TOKEN, $token);
    }

    public function fetchTurnos()
    {
        $token = $this->getToken();
        $accessToken = $token->getAccessToken();

        $url = self::BASE_URL . self::ENDPOINT_TURNOS;
        $params = array(
            'access_token' => $accessToken
        );
        $url.= '?' . http_build_query($params);
        $client = new \GuzzleHttp\Client();
        $data = $client->get($url)->getBody()->getContents();
        return json_decode($data);
    }

    /**
     * @param Turno $turno
     */
    public function saveTurno(Turno &$turno)
    {

        $accessToken = $this->getAccessToken();
        $url = self::BASE_URL . self::ENDPOINT_TURNOS;
        $client = new \GuzzleHttp\Client(array(
            'query' => array(
                'access_token' => $accessToken
            )
        ));
        $data = $client->request('POST', $url, array(
            'form_params' => array(
                'nombre' => $turno->getNombre(),
                'fecha' => $turno->getDate()->format('d/m/Y H:i'),
                'telefono' => $turno->getTelefono(),
                'comentarios' => $turno->getComentarios()
            )
        ))->getBody()->getContents();
        $obj = json_decode($data);
        if ($obj)
        {
            if (isset($obj->id))
                $turno->setId($obj->id);
        }
    }

    /**
     * @param $id
     */
    public function deleteTurno($id)
    {
        $accessToken = $this->getAccessToken();
        $url = self::BASE_URL . self::ENDPOINT_TURNOS . '/' . $id;
        $client = new \GuzzleHttp\Client(array(
            'query' => array(
                'access_token' => $accessToken
            )
        ));
        $data = $client->request(Request::METHOD_DELETE, $url)
            ->getBody()->getContents()
        ;
        var_dump($data);
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
     */
    private function buildUri($base, $params = array())
    {
        $url = $base. '?' . http_build_query($params);
        return $url;
    }

}