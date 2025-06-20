<?php

declare(strict_types=1);

namespace UnionPay\Api\Functions\Public;

use GuzzleHttp\Exception\GuzzleException;
use UnionPay\Api\Core\BaseClient;
use UnionPay\Api\Core\Container;

/**
 * 订单模块
 */
class OrderDetail extends BaseClient
{
    protected function setParams(): void
    {
        // ......
        $this->app->baseParams['instMid'] = "MINIDEFAULT";
    }

    /**
     * 统一查询订单
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function getInfo(array $params): array
    {
        return $this->curlRequest($params, 'post');
    }

    /**
     * 退款查询
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function getRefundInfo(array $params): array
    {
        return $this->curlRequest($params, 'post');
    }

}
