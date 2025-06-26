<?php

declare(strict_types=1);

namespace UnionPay\Api\Tools;

use Exception;

trait RsaUtils
{
    private string $publicKey = '';
    private string $privateKey = '';
    private string $serverPublicKey = '';

    public function setPublicKey($key)
    {
        $this->publicKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
    }

    public function setServerPublicKey($key)
    {
        $this->serverPublicKey = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
    }

    public function setPrivateKey($key)
    {
        $this->privateKey = "-----BEGIN PRIVATE KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PRIVATE KEY-----";
    }

    /**
     * RSA 加密 (使用公钥)
     *
     * @param string $data 要加密的数据
     * @return string Base64编码的加密结果
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
            $chunk = substr($data, $offset, 245);

            $encryptedChunk = '';

            // 执行加密
            if (!openssl_public_encrypt($chunk, $encryptedChunk, $pubKey, OPENSSL_PKCS1_PADDING)) {
                throw new Exception("RSA 加密失败: " . openssl_error_string());
            }

            $result .= $encryptedChunk;
            $offset += 245;
        }

        return base64_encode($result);
    }

    /**
     * RSA 解密 (使用私钥)
     *
     * @param string $data Base64编码的加密数据
     * @return string 解密后的原始数据
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

        for ($offset = 0; $offset < $dataLength; $offset += 265) {
            $chunk = substr($rawData, $offset, 265);
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
