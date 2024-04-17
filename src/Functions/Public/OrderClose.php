<?php

namespace UnionPay\Api\Functions\Public;

use GuzzleHttp\Exception\GuzzleException;
use UnionPay\Api\Core\BaseClient;

/**
 * 关闭模块
 */
class OrderClose extends BaseClient
{
    public string $service = 'close';

    protected function setParams(): void
    {
        // ......
    }

    /**
     * 统一关闭订单
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function closeOrder(array $params): array
    {
        return $this->curlRequest($params, 'post');
    }
}