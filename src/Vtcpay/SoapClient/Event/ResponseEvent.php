<?php
namespace Vtcpay\SoapClient\Event;

use Symfony\Component\EventDispatcher\Event;

class ResponseEvent extends Event
{
    protected $requestEvent;
    protected $response;

    public function __construct(RequestEvent $requestEvent, $response)
    {
        $this->requestEvent = $requestEvent;
        $this->response = $response;
    }

    public function getRequestEvent()
    {
        return $this->requestEvent;
    }

    public function getResponse()
    {
        return $this->response;
    }
}

