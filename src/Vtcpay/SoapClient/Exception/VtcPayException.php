<?php

namespace Vtcpay\SoapClient\Exception;

use Exception;

class VtcPayException extends Exception {

    protected $message;
    protected $errorCode;

    public function __construct() {

    }

    function setVtcPayExceptionMessage($message) {
        $this->message = $message;
    }

    function getVtcPayExceptionMessage() {
        return $this->message;
    }

    function setVtcPayExceptionErrorCode($errorCode) {
        $this->errorCode = $errorCode;
    }

    function getVtcPayExceptionErrorCode() {
        return $this->errorCode;
    }

}