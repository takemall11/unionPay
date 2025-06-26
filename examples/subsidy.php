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


// 客户端公钥
$publicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAv1BgR/byFgE9EjCtLINVkdyDWgJPbKYrgWH/Taz+U8tmy/oGfHT+8XOGhMLRTDeso+mDw4siUoAaraFGRkkHm6TYISNkkGdr+B8cbaTPr339fsYXwp/LqkZNGtzFAHBoVyJ9AP4sz+PLhJGlTIgyVZYdOo5+E0DBMi91oj6nujxPc2xKzakfW/RrgwmG0osHLkbXaL5yeNWQfz0me2PR3KhqOhaHTN3br2lMiwk786+nLeqKAYoSSAHkQTXs5d2Ui8udifDs2/J31MPROtGcpBKIfKQNQisL9s3bVJFcIDeOIDVJFqSbmZLp501uv5mcuh0cWeRjA33lHGPJCbUCtQIDAQAB";

// 客户端私钥
$privateKey = "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC5cQH/IGaHJkohfXz6dcR1qwN7b/QBbcdHlvJOvzRHN4gvU34GiWGrVxUynpWC06cv/tPwxc2DakXyvK+8H5AcS30obO+lDSZMghn1U4wp2X7SBRG5L3D1KQtAaMTVEUMey+jVKklmM6LHRuGeDQgIyanB/E2P6MAU+Fhu4J5vZAwjOZOv+HT2wnanaMagmYtHMMDxRcc/WybKQToAz/6KEkSjKtiwL3yKyApD7IsreR9UVn72C7W/D4Kdqy/q0X/Oib+ffpkSnYX2+zc5w7ZuyrXnILmdXj+TBYrtQYzH48CT/Ng2JXOnEZnD4OrOCupDv3UEJpTUFEJ4XXyLO92TAgMBAAECggEBAJdZzahd2HB75ssYsZV14weqsSfhAsTbxWb/ovp0ggWSJFuECHrrS8TdSVbMKfjiKDU7Bd4ggxC7/yUTrVacaDE/x4HkD1+lsNmG4grhyqcpdaGSM5nyR35ApodGO0gvU5niEUFRxyBcCoIcru4hcqpBdYT3GJLZA1TaMMsmFwmPuT/HBmVXQWSYcDYkc/wp2qwbeSAtmbmNWdwDrqVA2uXQA1xm1n+4DJTIOGH6Q1YIFCHZ0jL3IkyZDWaLmhVWOr88VGCE6Yajp43PqIQn+zJqAxrpud7UNe6GXdowrwZTY23tJia/Sw4op2AtBQtZ6suaU3qt7mNHPm5Jq616LPkCgYEA5p6ycfjDVSHJ7zgw/0GepheR0OVwgm5o25J2BNwyRF2KrM0oVPQveENOwtb2Iihy+HXM96DqAnmTswWxlMrAylwK3eFnuUr0qQkCCwAPwRAX3ElQ+/UtAHqU7zZWJrcTEtmTC2bGeQTqhqwdIm/zqslXXjFvhSTZVuA34MVYjQcCgYEAzdl8+Fpf4k7mig3t1rsxexDHarRMQnN/tGdRBF5wRM//OVHHySJKDh/3TJf2QWucdgy6OLL3CBON2D046GQnleomnDAYk+eCgb+ppYEadshElQwe6Fm8DVTi0f66AERKrjXGdkY5pOdjhQP6OQT0ucI9U1jjtbc3C4T2yXM+VBUCgYEA5SfyBr/6UIB3qGW8ghdRLpcjBGDIMELHnM7myKOIq1gLsPNWzTuqY6T2ATMadgydWXesiK7zeCwYcH4K29+wPCPDhIAy0fLCM6jIx+dywBNesjyD5SjVY7FqhlwGtQebQ1LBA0ZHlv8kj1c8x/hYNm9EikszFijscZ/wxj8yY0cCgYAXqTEZJuIwlBshsTouXXPxjlsto63EUZxTMzD3zJchAbt3bjQFpYBXoUr+rdTrbiAc3ZemHsQQVJcQTPE2nRSDwddQSqL4lTrGCS7JzE4raxee/jarRG0+JwyvUJU2pKLk5V97htTnZIVm668eULiEhZQg+W2rHHUiSNVJEIsQhQKBgGFj5mIlEvnmF7cNDEPtqU6zMAARPmkWK1ilcT0azBsoJrtX06luJSZlSQ7eydnUvyWvDmQXFbuY+sloMoIGwDs+RMD6f0FsU346x0Otapj7oQvC51sb/fptTyZf2bAhX7KI6umnOp57TOOTZ/dFyFN7oqNaRe0MDM0BjbJD/gjP";

// 服务端公钥
$serverPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjnAe+PaRY8/mndT+Qp+ivL4iTnvZdu0x9YsvlFLmAuwE3tYdRnMeVD1aVXPfV68/tYgYyGmjgGNWomDuvnnE7c/Xzl3R+tp4Q/W8c6/WkCID0PBstyVqZugJ9T6gktDIUYG+VfIOgZgNVUpVY91Iwwo+1uH8KPuWSmN/jZMDPRmc39edIYmQemi39n4mPpcCwcHGWLgewDMxAiQbg28pE7aZeBbWs8sNtIdME0g+uhW67AAjSRUakJphlo6h8anVuZK5zNE/swBOt4JcBk/rUByxFDKRwBXpAf9/QHEYHWBxt6DkZ8wUcw9Iluwa+DxZ9yXMPsYHrBPiRWUBBhgbzQIDAQAB";

// 用户 ID
$userId = "NHYX";

// 国补推送接口
$param = [
    'traceId' => date('YmdHis') . substr(microtime(), 2, 3) . uniqid(),
    'activeBigClass' => '3C',
    'provinceCode' => '44',
    'cityCode' => '4401',
    'districtCode' => '440101',
    'address' => 'wherever',
    'idCardFrontPicKey' => 'khl20240505153417428.png',
    'idCardBackPicKey' => 'khl20240505153417428.png',
    'newElecAppCnt' => '1',
    'brand' => '品牌',
    'newEei' => 'L1',
    'newElecAppBrandAndModel' => '品牌型号',
    'newElectSn' => 'sn666',
    'imei1' => '11',
    'imei2' => '22',
    'transDate' => '20250218',
    'newCelecAppInvPicKey' => 'khl20240505153417428.png',
    'newInvNo' => '666',
    'newInvCode' => '777',
    'invoiceIssueDate' => '2025-02-19',
    'newTotalPrice' => '1.00',
    'newTotalPriceExcTax' => '2.00',
    'salesCompName' => '61',
    'salesCompCode' => '71',
    'newElecAppPicKey' => 'khl20240505204345705.png',
    'extendField1Key' => 'khl20240505153417428.png',
    'extendField2Key' => 'khl20240505153417428.png',
    'constructionRegistPicKey' => 'khl20240505153417428.png',
    'newOtherFile1Key' => 'khl20240505153417428.png',
    'newOtherFile2Key' => 'khl20240505153417428.png',
    'newOtherFile3Key' => 'khl20240505204345705.png',
    'newOtherFile4Key' => 'khl20240505204345705.png',
    'remark' => '备注',
    'clientIp' => '192.168.1.1',
];

$payClient = new UnionPay();
## 初始化配置
$payClient->setUserId($userId);
$payClient->setPublicKey($publicKey);
$payClient->setPrivateKey($privateKey);
$payClient->setServerPublicKey($serverPublicKey);

$res = $payClient->subsidy->push($param);