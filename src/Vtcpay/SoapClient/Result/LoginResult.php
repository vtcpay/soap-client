<?php

namespace Vtcpay\SoapClient\Result;

/**
 * Login result
 */
class LoginResult
{
    protected $serverUrl;
    protected $sessionId;
    protected $userId;

    /**
     * @return string
     */
    public function getServerUrl()
    {
        return $this->serverUrl;
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
