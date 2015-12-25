<?php
namespace Vtcpay\SoapClient;

use Vtcpay\SoapClient\Result\LoginResult;
use Vht\Common\AbstractHasDispatcher;
use Vtcpay\SoapClient\Soap\SoapClient;
use Vtcpay\SoapClient\Result;
use Vtcpay\SoapClient\Event;

/**
 * A client for the Vtcpay SOAP API
 *
 */
class Client extends AbstractHasDispatcher implements ClientInterface
{
    /**
     * SOAP namespace
     *
     * @var string
     */
    const SOAP_NAMESPACE = 'https://pay.vtc.vn/WS/GoodsPaygate.asmx';

    /**
     * PHP SOAP client for interacting with the Vtcpay API
     *
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * Login result
     *
     * @var Result\LoginResult
     */
    protected $loginResult;

    /**
     * Construct Vtcpay SOAP client
     *
     * @param SoapClient $soapClient SOAP client
     *
     */
    public function __construct(SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
    }

    public function doLogin()
    {
        $loginResult = new LoginResult();
        $this->setLoginResult($loginResult);

        return $loginResult;
    }

    public function login()
    {
        return $this->doLogin();
    }

    /**
     * Get login result
     *
     * @return Result\LoginResult
     */
    public function getLoginResult()
    {
        if (null === $this->loginResult) {
            $this->login();
        }

        return $this->loginResult;
    }

    protected function setLoginResult(Result\LoginResult $loginResult)
    {
        $this->loginResult = $loginResult;
    }

    public function requestTransaction($requestData, $partnerCode, $commandType, $version)
    {
        return $this->call(
            'RequestTransaction',
            array(
                'requesData'   => $requestData,
                'partnerCode'   => $partnerCode,
                'commandType'   => $commandType,
                'version'   => $version,

            )
        );
    }

    /**
     * Initialize connection
     *
     */
    protected function init()
    {
    }

    /**
     * Issue a call to Vtcpay API
     *
     * @param string $method SOAP operation name
     * @param array  $params SOAP parameters
     *
     * @return array | $result object, such as QueryResult, SaveResult, DeleteResult.
     * @throws \Exception
     * @throws \SoapFault
     */
    protected function call($method, array $params = array())
    {
        $this->init();

        $requestEvent = new Event\RequestEvent($method, $params);
        $this->dispatch(Events::REQUEST, $requestEvent);

        try {
            $result = $this->soapClient->$method($params);
        } catch (\SoapFault $soapFault) {
            $faultEvent = new Event\FaultEvent($soapFault, $requestEvent);
            $this->dispatch(Events::FAULT, $faultEvent);

            throw $soapFault;
        }

        if (!isset($result)) {
            return array();
        }

        $this->dispatch(
            Events::RESPONSE,
            new Event\ResponseEvent($requestEvent, $result)
        );

        return $result;
    }
}

