<?php

define('BASE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR . '../');

require(BASE_DIR.'vendor/autoload.php');
require('vtcpay.php');

use Vtcpay\SoapClient\Exception\VtcPayException;
try {
    $vtcPay = new Vtcpay();

    // 01. ---------- TopupTelco ---------- Nạp tiền cho điện thoại di động
    /*$serviceCode = 'VTC0058';
    $account = '';
    $amount = 10000;
    $quantity = 1;
    $topupTelco = $vtcPay->topupTelco($serviceCode, $account, $amount, $quantity);
    echo "\nPass Topup Telco >>>>>> ";
    echo "\n>>>>>> Status: ".$topupTelco->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$topupTelco->getMessage()." <<<<<<<\n";*/

    // 02. ---------- CheckAccountGameExits ---------- Cho phép đối tác kiểm tra tài khoản game được nạp có tồn tại hay không truớc khi thực hiện nạp tiền
    /*$serviceCode = 'VTC0209';
    $account = 'mcash';
    $checkAccountGameExits = $vtcPay->checkAccountGameExits($serviceCode, $account);
    echo "\nPass Check Account Game Exits >>>>>> ";
    echo "\n>>>>>> Status: ".$checkAccountGameExits->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$checkAccountGameExits->getMessage()." <<<<<<<\n";*/

    // 03. ---------- TopupPartner ---------- Nạp tiền vào tài khoản game của khách hàng.
    /*$serviceCode = 'VTC0307';
    $account = 'zingxu';
    $amount = 10000;
    $description = "Fullname-|HCM|hoang@gmail.com";
    $topupPartner = $vtcPay->topupPartner($serviceCode, $account, $amount, $description);
    echo "\nPass Topup Partner >>>>>> ";
    echo "\n>>>>>> Status: ".$topupPartner->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$topupPartner->getMessage()." <<<<<<<\n";*/

    // 04. ---------- BuyCard ---------- Thực hiện giao dịch mua mã thẻ (điện thoại, game), key phần mềm ...
    /*$serviceCode = 'VTC0027';
    $amount = 10000;
    $quantity = 1;
    $buyCard = $vtcPay->buyCard($serviceCode, $amount, $quantity);
    echo "\nPass Buy Card >>>>>> ";
    echo "\n>>>>>> Status: ".$buyCard->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$buyCard->getMessage()." <<<<<<<\n";*/

    // 05. ---------- GetCard ---------- Lấy ra mã thẻ mà đối tác đã mua
    /*$serviceCode = 'VTC0029';
    $amount = 10000;
    $orgTransId = '9945351'; //Mã giao dịch do VTC đã gửi trả về trong hàm BuyCard
    $getCard = $vtcPay->getCard($serviceCode, $amount, $orgTransId);
    echo "\nPass Buy Card >>>>>> ";
    echo "\n>>>>>> Status: ".$getCard->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$getCard->getMessage()." <<<<<<<\n";*/

    // 06. ---------- GetBalance ---------- Dùng để kiểm tra số dư tài khoản cược của đối tác tại VTC.
    $getBalance = $vtcPay->getBalance();
    echo "\nPass Get Balance >>>>>> ";
    echo "\n>>>>>> Status: ".$getBalance->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$getBalance->getMessage()." <<<<<<<\n";

    // 07. ---------- GetQuantityCard ---------- Dùng để lấy số lượng mã thẻ hiện có trong kho của VTC.
    /*$serviceCode = 'VTC0027';
    $getQuantityCard = $vtcPay->getQuantityCard($serviceCode);
    echo "\nPass Get Quantity Card >>>>>> ";
    echo "\n>>>>>> Status: ".$getQuantityCard->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$getQuantityCard->getMessage()." <<<<<<<\n";*/

    // 08. ---------- CheckPartnerTransCode ---------- Thực hiện kiểm tra xem giao dịch của đối tác đã được thực hiện hay chưa?
    /*$orgTransId = 1111; //Mã giao dịch do VTC đã gửi trả về
    $checkTypeServiceCode = 1;
    $checkPartnerTransCode = $vtcPay->checkPartnerTransCode($orgTransId, $checkTypeServiceCode);
    echo "\nPass Check Partner Trans Code >>>>>> ";
    echo "\n>>>>>> Status: ".$checkPartnerTransCode->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$checkPartnerTransCode->getMessage()." <<<<<<<\n";*/

    // 09. ---------- GetHistoryTrans ---------- Lấy lịch sử giao dịch của đối tác
    /*$fromDate = '2015/12/08';
    $toDate = '2015/12/10';
    $getHistoryTrans = $vtcPay->getHistoryTrans($fromDate, $toDate);
    echo "\nPass Get History Trans >>>>>> ";
    echo "\n>>>>>> Status: ".$getHistoryTrans->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$getHistoryTrans->getMessage()." <<<<<<<\n";*/

    // 10. ---------- GetSalePrice ---------- Trả ra giá bán đã có chiết khấu của 1 sản phẩm.
    /*$serviceCode = 'VTC0027';
    $amount = 100000;
    $getSalePrice = $vtcPay->getSalePrice($serviceCode, $amount);
    echo "\nPass Get Sale Price >>>>>> ";
    echo "\n>>>>>> Status: ".$getSalePrice->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$getSalePrice->getMessage()." <<<<<<<\n";*/

    // 11. ---------- GetPromotionDate ---------- Lấy lịch khuyến mại của các nhà mạng
    /*$getPromotionDate = $vtcPay->getPromotionDate();
    echo "\nPass Get Promotion Date >>>>>> ";
    echo "\n>>>>>> Status: ".$getPromotionDate->getStatusCode()." <<<<<<<";
    echo "\n>>>>>> Message: ".$getPromotionDate->getMessage()." <<<<<<<\n";*/

    // 12. ---------- CheckServiceAvailable ---------- //Kiểm tra dịch vụ(Available/Unavailable)
    /*$serviceCode = 'VTC0027';
    $checkServiceAvailable = $vtcPay->checkServiceAvailable($serviceCode);
    echo "\nPass Check Service Available >>>>>> Status: ".$checkServiceAvailable->getStatusCode()." <<<<<<<";*/

    echo "\n";
} catch (VtcPayException $ex) {
    echo("\nErr = " . $ex->getVtcPayExceptionErrorCode());
    echo("\nMes = " . $ex->getVtcPayExceptionMessage());
}
catch (\Exception $ex) {
    echo "\nFinal error: ".$ex->getMessage();
    echo "\n";
}