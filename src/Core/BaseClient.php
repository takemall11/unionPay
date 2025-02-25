<?php

declare(strict_types=1);

namespace UnionPay\Api\Core;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use UnionPay\Api\Constants\UnionErrorCode;
use UnionPay\Api\Exception\PayException;
use UnionPay\Api\Tools\Guzzle;
use UnionPay\Api\Tools\Sign;

use function Hyperf\Support\make;
use function Hyperf\Config\config;

/**
 * Class BaseClient
 * @package UnionPay\Api\Core
 * @property BaseClient app
 */
abstract class BaseClient
{
    use Sign;

    // 基础参数
    protected Container $app;
    // 请求地址
    public string $host = 'https://api-mop.chinaums.com';
    // 测试地址
    public string $testHost = 'https://test-api-open.chinaums.com';
    // 请求路径
    public string $url = '/v1/netpay';
    // 服务名称
    public string $service;

    /**
     * BaseClient constructor.
     * @param Container $app
     * @param string $service
     */
    public function __construct(Container $app, string $service)
    {
        $this->app = $app;
        $this->service = $service;
        // 设置公共参数
        $app->baseParams['msgId'] = uniqid();
        $this->host = config('unionpay.env') === 'prod' ? $this->host : $this->testHost;
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
            ## 合并公共参数
            $data = array_merge($data, $this->app->baseParams);
            ## 开始请求
            $client = $this->getInstance(['Authorization' => $this->getSign($data), 'Content-Length' => strlen(json_encode($data, JSON_UNESCAPED_UNICODE))]);
            ## 发送请求
            $method = 'send' . ucfirst($method);
            ## 获取返回结果
            return $client->$method($this->url . $this->service, $data);
        } catch (RequestException|ClientException $e) {
            // 请求失败
            logger('unionpay')->error('UnionPay Request Error', [
                'url' => $this->host . $this->url . $this->service,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);
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
