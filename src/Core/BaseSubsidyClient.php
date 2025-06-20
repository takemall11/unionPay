<?php

declare(strict_types=1);

namespace UnionPay\Api\Core;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use UnionPay\Api\Constants\UnionErrorCode;
use UnionPay\Api\Exception\PayException;
use UnionPay\Api\Tools\Guzzle;
use UnionPay\Api\Tools\RsaUtils;

use function Hyperf\Support\make;
use function Hyperf\Config\config;

/**
 * Class BaseClient
 * @package UnionPay\Api\Core
 * @property BaseClient app
 */
abstract class BaseSubsidyClient
{
    use RsaUtils;

    // 基础参数
    protected ContainerBase $app;
    // 请求地址
    public string $host = 'https://mgw-test.gnete.com';
    // 请求路径
    public string $url = '/gdhlg';
    // 服务名称
    public string $service = '';

    /**
     * BaseClient constructor.
     * @param ContainerBase $app
     * @param string $service
     */
    public function __construct(ContainerBase $app, string $service)
    {
        $this->app = $app;
        $this->service = $service;
        $this->publicKey = $this->app->publicKey;
        $this->privateKey = $this->app->privateKey;
        $this->serverPublicKey = $this->app->serverPublicKey;
    }

    /**
     * 设置参数.
     * @return void
     */
    abstract protected function setParams(): void;

    /**
     * curl 请求
     * @param array $data
     * @param string $method
     * @return array
     * @throws GuzzleException
     */
    public function curlRequest(array $data, string $method = 'get'): array
    {
        try {
            $this->setParams();
            unset($this->app->baseParams['mid']);
            ## 加密
            $this->app->baseParams['encBizReqData'] = $this->encryptByPublicKey(json_encode($data, JSON_UNESCAPED_UNICODE));
            ## 签名
            $this->app->baseParams['sign'] = $this->sign($this->app->baseParams['encBizReqData']);
            ## 开始请求
            $client = $this->getInstance();
            ## 发送请求
            $method = 'send' . ucfirst($method);

            var_dump($this->app->baseParams);
            ## 获取返回结果
            return $client->$method($this->url . $this->service, $this->app->baseParams);
        } catch (RequestException|ClientException $e) {
            throw new PayException(UnionErrorCode::SERVER_ERROR, '支付服务访问失败');
        }
    }

    /**
     * 获取实例.
     * @param array $headers
     * @param int $timeout
     * @return mixed
     */
    private function getInstance(array $headers = [], int $timeout = 10): Guzzle
    {
        $params = [
            'base_uri' => $this->host,
            'timeout' => $timeout,
            'verify' => false,
            'headers' => $headers,
        ];

        ## 开始请求
        /** @var Guzzle $client */
        return make(Guzzle::class)->setHttpHandle($params);
    }

}
