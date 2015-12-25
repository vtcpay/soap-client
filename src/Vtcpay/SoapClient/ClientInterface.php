<?php
namespace Vtcpay\SoapClient;

use Vtcpay\SoapClient\Result;

/**
 * Vtcpay API client interface
 *
 */
interface ClientInterface
{
    /**
     * Logs in to the login server and starts a client session
     *
     * @return Result\LoginResult
     * @link
     */
    public function login();

    /**
     * @param $requestData
     * @param $partnerCode
     * @param $commandType
     * @param $version
     *
     * @return Result\RequestTransactionResult
     * @link https://pay.vtc.vn/WS/GoodsPaygate.asmx?op=RequestTransaction
     */
    public function requestTransaction($requestData, $partnerCode, $commandType, $version);
}

