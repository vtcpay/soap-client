<?php
namespace Vtcpay\SoapClient\Soap;

/**
 * Factory to create a \SoapClient properly configured for the Vtcpay SOAP client
 *
 */
class SoapClientFactory
{
    /**
     * Default classmap
     *
     * @var array
     */
    protected $classmap = array(
        'RequestTransactionResult'  => 'Vtcpay\SoapClient\Result\RequestTransactionResult',
        'LoginResult'               => 'Vtcpay\SoapClient\Result\LoginResult',

    );

    /**
     * @param string $wsdl Path to WSDL file
     * @param array $soapOptions
     *
     * @return SoapClient
     */
    public function factory($wsdl, array $soapOptions = array())
    {
        $defaults = array(
            'trace'      => 1,
            'features'   => \SOAP_SINGLE_ELEMENT_ARRAYS,
            'classmap'   => $this->classmap,
            'cache_wsdl' => \WSDL_CACHE_MEMORY,
            //'connection_timeout' => 80000
        );

        $options = array_merge($defaults, $soapOptions);

        return new SoapClient($wsdl, $options);
    }
}
