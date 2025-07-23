<?php

declare(strict_types=1);

namespace UnionPay\Api\Functions\Subsidy;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use UnionPay\Api\Constants\UnionErrorCode;
use UnionPay\Api\Core\BaseClient;
use UnionPay\Api\Exception\PayException;
use function Hyperf\Support\env;
use function UnionPay\Api\Functions\Union\logger;

/**
 * 广西云闪付国补台账模块
 */
class GxSubsidyShortcut extends BaseClient
{
    /**
     * @return void
     */
    protected function setParams(): void
    {
        $env = env('APP_ENV', 'dev');
        $this->host = $env === 'prod' ? 'https://open-yjhx.chinaums.com' : 'https://test-api-open.chinaums.com';
    }

    /**
     * 审核信息上传
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function auditUpload(array $params): array
    {
        $this->service .= '/audit-upload';
        return $this->curlRequest($params, 'post');
    }

    /**
     * 审核信息修改
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function auditUpdate(array $params): array
    {
        $this->service .= '/audit-update';
        return $this->curlRequest($params, 'post');
    }

    /**
     * 审核信息修改
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function auditQuery(array $params): array
    {
        $this->service .= '/audit-query';
        return $this->curlRequest($params, 'post');
    }


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
            ## 开始请求
            $client = $this->getInstance(['Authorization' => $this->getSign($data)]);
            ## 发送请求
            $method = 'send' . ucfirst($method);
            ## 获取返回结果
            return $client->$method($this->service, $data);
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

}
