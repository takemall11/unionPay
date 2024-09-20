<?php
/**
 * Created by PhpStorm.
 * User: stbz
 * Date: 2020/6/17
 * Time: 4:00 PM
 */

require_once __DIR__ . '/../vendor/autoload.php';

use UnionPay\Api\UnionPay;

date_default_timezone_set('PRC');


// 生产参数
$mchId = "89844085722AAFR";
$appId = "8a81c1bd8e804897018ecaf9148501a5";
$appSecret = "b7d44db036c441ff84c47a1034534e05";

// 下单接口
$param = [
    "merOrderId" => '38HA' . time(),
    "attachedData"=>"",
    "totalAmount" => 100,
    "goods" => json_encode(
        [
            "goodsId"=>"001",
            "goodsName"=>"鸡蛋",
            "quantity"=>"10",
            "price"=>"1",
            "goodsCategory"=>"food meterial",
            "body"=>"two eggs",
            "unit"=>"个",
            "discount"=>"5",
            "subMerchantId"=>"",
            "merOrderId"=>"dd20220419111222",
            "subOrderAmount"=>"200"
        ]
    ),
    "notifyUrl"=>"https://www.test.com/notify",
    "subAppId"=>"wx014ad2ef80a147a7",
    "subOpenId"=>"oLLD10OE0bZiUE_UUola5ecJPDLI",
];

$payClient = new UnionPay(['mid' => $mchId]);
## 初始化配置
$payClient->setAppId($appId);
$payClient->setAppKey($appSecret);


// 普通小程序下单
// $response = $payClient->wechatMini->createOrder($param);
// 云闪付小程序下单
$response = $payClient->uacMini->createOrder($param);

//订单详情
$param = [
    'merOrderId' => '38HA1713326108'
];
// $response = $payClient->search->getInfo($param);


//退款
$param = [
	'targetOrderId'=>'20200610111116', //支付订单号
	'refundAmount'=>100, //退款金额
    'refundOrderId' => '20200610111116', //退款订单号
    'refundDesc'=>'退款原因', //退款原因
];

// $response = $payClient->refund->refund($param);

//物流查询
//$param = [];
//$response = $supplyClient->order->setApi("/v2/logistic/firms")->get();

var_dump($response);exit();