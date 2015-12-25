<?php

use Vtcpay\SoapClient\Result\VtcPayResult;
use Vtcpay\SoapClient\Exception\VtcPayException;

class Vtcpay {

    protected $builder = null;
    protected $encryption = null;
    protected $xml = null;
    protected $partnerCode = ''; //change your "PartnerCode"
    protected $commandType = '';
    protected $version = '1.0';
    protected $transDate = null;
    protected $orgTransID = null;

    public function __construct($log = false)
    {
        $publicKey = openssl_get_publickey('file:///'.BASE_DIR.'private/dev/VTCAPI_xxx_publicKey.pem');
        $privateKey = openssl_get_privatekey('file:///'.BASE_DIR.'private/dev/merchant_privateKey.pem');
        $builder = new Vtcpay\SoapClient\ClientBuilder(
            'private/wsdl/GoodsPaygate.asmx.xml'
        );
        $this->encryption = new Vht\Common\Encryption($publicKey, $privateKey);
        $this->xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><RequestData></RequestData>");

        if ($log) {
            $log = new Monolog\Logger('vtcpay');
            $log->pushHandler(new Monolog\Handler\StreamHandler(BASE_DIR.'logs/'.date("Ymd_H-i").'.log'));
            $this->builder = $builder->withLog($log)->build();
        } else {
            $this->builder = $builder->build();
        }

        $localDateTime = new \DateTime();
        $this->transDate = $localDateTime->format('YmdHis');
        $this->orgTransID = uniqid();

    }

    public function topupTelco($serviceCode, $account, $amount, $quantity)
    {
        $dataSign = "$serviceCode-$account-$amount-$this->partnerCode-$this->transDate-$this->orgTransID";
        $this->xml->addChild('ServiceCode', $serviceCode);
        $this->xml->addChild('Account', $account);
        $this->xml->addChild('Amount', $amount);
        $this->xml->addChild('Quantity', $quantity);
        $this->xml->addChild('TransDate', $this->transDate);
        $this->xml->addChild('OrgTransID', $this->orgTransID);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'TopupTelco', $this->version);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0].'-'.$responseResult[1].'-'.$responseResult[2];

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    public function checkAccountGameExits($serviceCode, $account)
    {
        $dataSign = "$serviceCode-$account-$this->partnerCode";
        $this->xml->addChild('ServiceCode', $serviceCode);
        $this->xml->addChild('Account', $account);
        $this->xml->addChild('Amount', '');
        $this->xml->addChild('TransDate', '');
        $this->xml->addChild('OrgTransID', '');
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'CheckAccount', $this->version);
        //print_r($result);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0];
        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));

        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    public function topupPartner($serviceCode, $account, $amount, $description)
    {
        $dataSign = "$serviceCode-$account-$amount-$this->partnerCode-$this->transDate-$this->orgTransID";
        $this->xml->addChild('ServiceCode', $serviceCode);
        $this->xml->addChild('Account', $account);
        $this->xml->addChild('Amount', $amount);
        $this->xml->addChild('Quantity', '');
        $this->xml->addChild('Description', $description);
        $this->xml->addChild('TransDate', $this->transDate);
        $this->xml->addChild('OrgTransID', $this->orgTransID);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'TopupPartner', $this->version);
        print_r($result);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0].'-'.$responseResult[1].'-'.$responseResult[2];

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    public function buyCard($serviceCode, $amount, $quantity)
    {
        $dataSign = "$serviceCode-$amount-$quantity-$this->partnerCode-$this->transDate-$this->orgTransID";
        $this->xml->addChild('ServiceCode', $serviceCode);
        $this->xml->addChild('Account', '');
        $this->xml->addChild('Amount', $amount);
        $this->xml->addChild('Quantity', $quantity);
        $this->xml->addChild('TransDate', $this->transDate);
        $this->xml->addChild('OrgTransID', $this->orgTransID);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'BuyCard', $this->version);
        //print_r($result);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0].'-'.$responseResult[1].'-'.$responseResult[2].'-'.$responseResult[3];

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    public function getCard($serviceCode, $amount, $orgTransId)
    {
        $dataSign = "$serviceCode-$amount-$this->partnerCode-$orgTransId";
        $this->xml->addChild('ServiceCode', $serviceCode);
        $this->xml->addChild('Account', '');
        $this->xml->addChild('Amount', $amount);
        $this->xml->addChild('TransDate', '');
        $this->xml->addChild('OrgTransID', $orgTransId);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'GetCard', $this->version);

        $requestTransactionResult = $result->RequestTransactionResult;
        $keySeed = file_get_contents(BASE_DIR.'private/dev/TripleDESKey.txt');

        $tripleDes = $this->encryption->tripleDESDecrypt($requestTransactionResult, $keySeed);

        $responseResult = explode('|', $tripleDes);

        $vtcPayResult = new VtcPayResult();
        $vtcPayResult->setMessage($tripleDes);
        $vtcPayResult->setStatusCode($responseResult[0]);

        return $vtcPayResult;
    }

    public function getBalance()
    {
        $dataSign = "$this->partnerCode";
        $this->xml->addChild('ServiceCode', '');
        $this->xml->addChild('Account', '');
        $this->xml->addChild('Amount', '');
        $this->xml->addChild('TransDate', '');
        $this->xml->addChild('OrgTransID', '');
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'GetBalance', $this->version);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0];

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    public function getQuantityCard($serviceCode)
    {
        $dataSign = "$serviceCode-$this->orgTransID";
        $this->xml->addChild('ServiceCode', $serviceCode);
        $this->xml->addChild('Account', '');
        $this->xml->addChild('Amount', '');
        $this->xml->addChild('TransDate', '');
        $this->xml->addChild('OrgTransID', $this->orgTransID);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'GetQuantityCard', $this->version);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0];

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode('OK');

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    public function checkPartnerTransCode($orgTransId, $checkTypeServiceCode)
    {
        $dataSign = "$this->partnerCode-$orgTransId-$checkTypeServiceCode";
        $this->xml->addChild('Account', '');
        $this->xml->addChild('OrgTransID', $orgTransId);
        $this->xml->addChild('CheckTypeServiceCode', $checkTypeServiceCode);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'CheckPartnerTransCode', $this->version);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0].'|'.
                    $responseResult[1].'|'.
                    $responseResult[2].'|'.
                    $responseResult[3].'|'.
                    $responseResult[4].'|'.
                    $responseResult[5];

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    public function getHistoryTrans($fromDate, $toDate)
    {
        $dataSign = "$this->partnerCode-$fromDate-$toDate";
        $this->xml->addChild('FromDate', $fromDate);
        $this->xml->addChild('ToDate', $toDate);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'GetHistoryTrans', $this->version);
        //print_r($result);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0].'|'.
                    $responseResult[1];

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    public function getSalePrice($serviceCode, $amount)
    {
        $dataSign = "$serviceCode-$amount-$this->partnerCode";
        $this->xml->addChild('ServiceCode', $serviceCode);
        $this->xml->addChild('Amount', $amount);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'GetSalePrice', $this->version);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0].'-'.$responseResult[1].'-'.$responseResult[2].'-'.$responseResult[3];

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    /**
     * @return VtcPayResult
     * @throws VtcPayException
     */
    public function getPromotionDate()
    {
        $dataSign = "$this->partnerCode";
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'GetPromotionDate', $this->version);
        //print_r($result);
        //1|[{"Provider":"VinaPhone","BeginDate":"12/12/2015 00:30:00","EndDate":"12/12/2015 23:00:00","Apply":"Vinaphone triển khai chương trình khuyến mãi ngày 12/12/2015"}]

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);
        $dataSign = $responseResult[0].
                //'|'.json_encode($responseResult[1]);
                '-'.$responseResult[1];

        //$dataSign = '1|[{"Provider":"Viettel","BeginDate":"15/12/2015 00:30:00","EndDate":"16/12/2015 23:00:00","Apply":"Viettel khuyen mai 50% gia tri the nap trong 2 ngay"}]';

        $toSign = $this->encryption->verifySignRSA($dataSign, end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }

    /**
     * @param $serviceCode
     *
     * @return VtcPayResult
     * @throws VtcPayException
     */
    public function checkServiceAvailable($serviceCode)
    {
        $dataSign = "$this->partnerCode-$serviceCode";
        $this->xml->addChild('ServiceCode', $serviceCode);
        $this->xml->addChild('DataSign', $this->encryption->generateSignRSA($dataSign));

        $requestData = $this->xml->asXML();

        $result = $this->builder->requestTransaction($requestData, $this->partnerCode, 'CheckServiceAvailable', $this->version);
        print_r($result);

        $requestTransactionResult = $result->RequestTransactionResult;
        $responseResult = explode('|', $requestTransactionResult);

        $toSign = $this->encryption->verifySignRSA($responseResult[0], end($responseResult));
        if ($toSign) {
            $vtcPayResult = new VtcPayResult();
            $vtcPayResult->setMessage($requestTransactionResult);
            $vtcPayResult->setStatusCode($responseResult[0]);

            return $vtcPayResult;
        } else {
            $vtcPayExcep = new VtcPayException();
            $vtcPayExcep->setVtcPayExceptionErrorCode(500);

            throw $vtcPayExcep;
        }
    }
}