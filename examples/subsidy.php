<?php

$userId = '';
// 客户端公钥
$publicKey = "";
// 客户端私钥
$privateKey = "";
// 服务端公钥
$serverPublicKey = "";

class RSAUtils
{
    /**
     * RSA最大加密明文大小
     */
    private const MAX_ENCRYPT_BLOCK = 245;

    /**
     * RSA最大解密密文大小
     */
    private const MAX_DECRYPT_BLOCK = 256;

    /**
     * 后端RSA的密钥对(公钥和私钥)
     */
    private $privateKey;
    private $publicKey;
    private $serverPublicKey;

    public function __construct($privateKey, $publicKey, $serverPublicKey)
    {
        $this->privateKey = $this->formatPrivateKey($privateKey);
        $this->publicKey = $this->formatPublicKey($publicKey);
        $this->serverPublicKey = $this->formatPublicKey($serverPublicKey);
    }

    /**
     * 格式化私钥
     */
    private function formatPrivateKey($privateKey)
    {
        $pem = "-----BEGIN PRIVATE KEY-----\n";
        $pem .= chunk_split($privateKey, 64, "\n");
        $pem .= "-----END PRIVATE KEY-----";
        return $pem;
    }

    /**
     * 格式化公钥
     */
    private function formatPublicKey($publicKey)
    {
        $pem = "-----BEGIN PUBLIC KEY-----\n";
        $pem .= chunk_split($publicKey, 64, "\n");
        $pem .= "-----END PUBLIC KEY-----";
        return $pem;
    }

    /**
     * RSA 加密 (使用公钥)
     *
     * @param string $data 要加密的数据
     * @return string Base64编码的加密结果
     * @throws Exception
     */
    public function encryptByPublicKey(string $data): string
    {
        $pubKey = openssl_pkey_get_public($this->serverPublicKey);
        $result = '';
        $dataLen = strlen($data);
        $offset = 0;
        $data = mb_convert_encoding($data, 'UTF-8'); // 强制UTF-8编码

        while ($offset < $dataLen) {
            // 取出当前块
            $chunk = substr($data, $offset, self::MAX_ENCRYPT_BLOCK);

            $encryptedChunk = '';

            // 执行加密
            if (!openssl_public_encrypt($chunk, $encryptedChunk, $pubKey)) {
                throw new Exception("RSA 加密失败: " . openssl_error_string());
            }

            $result .= $encryptedChunk;
            $offset += self::MAX_ENCRYPT_BLOCK;
        }

        return base64_encode($result);
    }

    /**
     * RSA 解密 (使用私钥)
     *
     * @param string $data Base64编码的加密数据
     * @return array 解密后的原始数据
     * @throws Exception
     */
    public function decryptByPrivateKey(string $data): array
    {
        $privKey = openssl_pkey_get_private($this->privateKey);
        if (!$privKey) {
            throw new Exception("加载私钥失败: ".openssl_error_string());
        }
        $rawData = base64_decode($data);
        if ($rawData === false) {
            throw new Exception("Base64解码失败");
        }
        $result = '';
        $dataLength = strlen($rawData);

        for ($offset = 0; $offset < $dataLength; $offset += self::MAX_DECRYPT_BLOCK) {
            $chunk = substr($rawData, $offset, self::MAX_DECRYPT_BLOCK);
            if (!openssl_private_decrypt($chunk, $decrypted, $privKey)) {
                throw new Exception("分段解密失败: ".openssl_error_string());
            }
            $result .= $decrypted;
        }

        $result = json_decode($result, true);

        return is_array($result) ? $result : [];
    }

    /**
     * 生成签名
     *
     * @param string $data 要签名的数据
     * @return string Base64编码的签名
     */
    public function sign(string $data): string
    {
        $privKey = openssl_pkey_get_private($this->privateKey);
        openssl_sign($data, $signature, $privKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    /**
     * 验证签名
     *
     * @param string $data 原始数据
     * @param string $sign Base64编码的签名
     * @return bool 是否验证通过
     */
    public function verifySign(string $data, string $sign): bool
    {
        $pubKey = openssl_pkey_get_public($this->serverPublicKey);
        return (bool)openssl_verify($data, base64_decode($sign), $pubKey, OPENSSL_ALGO_SHA256);
    }

}

// 示例用法
try {
    // 实例化工具类
    $rsa = new RSAUtils($privateKey, $publicKey, $serverPublicKey);
    // 参数
    $originalData = json_encode(['traceId' => 'xxxxxxxx']);

    // 公钥加密，私钥解密
    $encrypted = $rsa->encryptByPublicKey($originalData);
    echo "加密后: $encrypted\n";

    // 公钥加密，私钥解密
    $sign = $rsa->sign(base64_decode($encrypted));
    echo "签名后: $sign\n";

    // 构造基础请求参数
    $baseReq = [
        'userId' => $userId,
        'reqSn' => date('YmdHis') . substr(microtime(), 2, 3) . uniqid(),
        'timestamp' => date('Y-m-d H:i:s'),
        'encBizReqData' => $encrypted,
        'signAlg' => 'SHA256withRSA',
        'sign' => $sign,
    ];


    // 发起 HTTP POST 请求（使用 curl）
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://mgw-test.gnete.com/gdhlg/o2o/querySubsidyApplTrace");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($baseReq, JSON_UNESCAPED_UNICODE));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json;charset=utf-8']);

    $response = curl_exec($ch);
    curl_close($ch);

    // 解析响应
    $respJson = json_decode($response, true);

    if (is_array($respJson)) {
        if ($respJson['code'] == '00000') {
            // 解析
            $response = $rsa->decryptByPrivateKey($respJson['encBizRespData']);

            var_dump($response);
        } else {
            echo '接口返回失败' . json_encode($respJson, JSON_UNESCAPED_UNICODE);
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

