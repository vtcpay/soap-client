<?php
namespace Vtcpay\SoapClient;

use Vtcpay\SoapClient\Soap\SoapClientFactory;
use Vtcpay\SoapClient\Plugin\LogPlugin;
use Psr\Log\LoggerInterface;

/**
 * Vtcpay SOAP client builder
 *
 * @author HgTAn <hoangthienan@gmail.com>
 */
class ClientBuilder
{
    protected $log;

    /**
     * Construct client builder with required parameters
     *
     * @param string $wsdl        Path to your Vtcpay WSDL
     * @param array  $soapOptions Further options to be passed to the SoapClient
     */
    public function __construct($wsdl, array $soapOptions = array())
    {
        $this->wsdl = $wsdl;
        $this->soapOptions = $soapOptions;
    }

    /**
     * Enable logging
     *
     * @param LoggerInterface $log Logger
     *
     * @return ClientBuilder
     */
    public function withLog(LoggerInterface $log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Build the Vtcpay SOAP client
     *
     * @return Client
     */
    public function build()
    {
        $soapClientFactory = new SoapClientFactory();
        $soapClient = $soapClientFactory->factory($this->wsdl, $this->soapOptions);

        $client = new Client($soapClient);

        if ($this->log) {
            $logPlugin = new LogPlugin($this->log);
            $client->getEventDispatcher()->addSubscriber($logPlugin);
        }

        return $client;
    }
}
