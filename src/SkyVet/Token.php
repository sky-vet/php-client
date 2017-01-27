<?php
namespace SkyVet;

/**
 * Class Token
 *
 * @package SkyVet
 */
class Token
{
    /**
     * @var string|null
     */
    private $accessToken = null;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var \DateTime|null
     */
    private $expirationDate = null;

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     *
     * @return void
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return void
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $expirationDate
     *
     * @return void
     */
    public function setExpirationDate(\DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        $then = $this->getExpirationDate();

        if ($then === null) {
            return true;
        }

        $now = new \DateTime();

        if ($now >= $then) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return is_null($this->getAccessToken());
    }
}
