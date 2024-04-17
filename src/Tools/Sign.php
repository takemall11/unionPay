<?php

namespace UnionPay\Api\Tools;

use Hyperf\Codec\Json;
use function Hyperf\Config\config;
use function Hyperf\Support\make;

trait Sign
{

    /**
     * @param array $body
     * @return string
     */
    public function getSign(array $body): string
    {
        $body = json_encode($body);
        $appId = $this->app->appId;
        $appKey = $this->app->appKey;
        $timestamp = date("YmdHis", time());
        $nonce = md5(uniqid(microtime(true), true));
        $str = bin2hex(hash('sha256', $body, true));
        $signature = base64_encode(hash_hmac('sha256', "$appId$timestamp$nonce$str", $appKey, true));
        return "OPEN-BODY-SIG AppId=\"$appId\", Timestamp=\"$timestamp\", Nonce=\"$nonce\", Signature=\"$signature\"";
    }

    public function sendPayHttp(array $params)
    {
        $header = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($params)),
            'Authorization: ' . $this->getSign($params)
        ];

        $curl = curl_init();  //初始化
        curl_setopt($curl, CURLOPT_URL, $this->host . $this->url . $this->service);  //设置url  . $this->service
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在 设为0表示不检查证书 设为1表示检查证书中是否有CN(common name)字段 设为2表示在1的基础上校验当前的域名是否与CN匹配
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $result = curl_exec($curl);
        if ($result === false) {
            exit();
        }
        $data = json_decode($result, true);

        var_dump($data);
        exit();
    }
}