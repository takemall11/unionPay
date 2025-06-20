<?php

declare(strict_types=1);

namespace UnionPay\Api\Tools;

trait RsaUtils
{
    private string $publicKey = '';
    private string $privateKey = '';
    private string $serverPublicKey = '';


    /**
     * RSA 加密 (使用公钥)
     *
     * @param string $data 要加密的数据
     * @return string Base64编码的加密结果
     */
    public function encryptByPublicKey(string $data): string
    {
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split($this->serverPublicKey, 64, "\n") .
            "-----END PUBLIC KEY-----\n";

        // 获取密钥位数（如 2048）
        $keyDetails = openssl_pkey_get_details(openssl_pkey_get_public($publicKey));
        var_dump($keyDetails);
        $keySizeBits = $keyDetails['bits'];
        $maxLength = ($keySizeBits / 8) - 11; // PKCS1 填充预留 11 字节

        $encryptedData = '';
        $offset = 0;

        // 分段加密
        while ($offset < strlen($data)) {
            $input = substr($data, $offset, $maxLength);
            openssl_public_encrypt($input, $encrypted, $publicKey, OPENSSL_PKCS1_OAEP_PADDING);
            $encryptedData .= $encrypted;
            $offset += $maxLength;
        }

        return base64_encode($encryptedData);
    }

    /**
     * RSA 解密 (使用私钥)
     *
     * @param string $data Base64编码的加密数据
     * @return string 解密后的原始数据
     */
    public function decryptByPrivateKey(string $data): string
    {
        $privateKey = "-----BEGIN PRIVATE KEY-----\n" .
            chunk_split($this->privateKey, 64, "\n") .
            "-----END PRIVATE KEY-----\n";

        $data = base64_decode($data);
        openssl_private_decrypt($data, $decrypted, $privateKey, OPENSSL_PKCS1_OAEP_PADDING);
        return $decrypted;
    }

    /**
     * 生成签名
     *
     * @param string $data 要签名的数据
     * @return string Base64编码的签名
     */
    public function sign(string $data): string
    {
        $privateKey = "-----BEGIN PRIVATE KEY-----\n" .
            chunk_split($this->privateKey, 64, "\n") .
            "-----END PRIVATE KEY-----\n";

        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    /**
     * 验证签名
     *
     * @param string $data 原始数据
     * @param string $sign Base64编码的签名
     * @return bool 是否验证通过
     */
    public function verifySign(string $data,string $sign): bool
    {
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
            chunk_split($this->serverPublicKey, 64, "\n") .
            "-----END PUBLIC KEY-----\n";

        $sign = base64_decode($sign);
        return openssl_verify($data, $sign, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }
}
