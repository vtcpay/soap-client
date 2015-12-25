<?php

namespace Vtcpay\SoapClient\Result;

class RequestTransactionResult
{
    protected $balanceReturn;
    protected $GetBalanceResult;
    /**
     * @return balanceReturn{}
     */
    public function getBalanceReturn()
    {
        return $this->balanceReturn;
    }

    public function getGetBalanceResult()
    {
        return $this->GetBalanceResult;
    }

    public function getReturn($index)
    {
        if (isset($this->balanceReturn->$index)) {
            return $this->balanceReturn->$index;
        }
    }
}
