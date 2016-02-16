<?php
/**
 * Created by PhpStorm.
 * User: Hormiga
 * Date: 14/02/2016
 * Time: 12:58 PM
 */

namespace SkyVet;


class Token
{

    /**
     * @var string
     */
    private $accessToken = null;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var \DateTime
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
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }

    public function isExpired()
    {

        $then = $this->getExpirationDate();
        if ($then === null)
        {
            return true;
        }
        $now = new \DateTime();
        if ($now >= $then)
            return true;
        else
            return false;
    }

    public function isEmpty()
    {
        return is_null($this->getAccessToken());
    }

}