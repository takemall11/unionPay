<?php

namespace UnionPay\Api\Functions\Public;

use GuzzleHttp\Exception\GuzzleException;
use UnionPay\Api\Core\BaseClient;

/**
 * 退款模块
 */
class OrderRefund extends BaseClient
{

    public string $service = 'refund';


    protected function setParams(): void
    {
        // ......
    }

    /**
     * 统一退款
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function refund(array $params): array
    {
        return $this->curlRequest($params, 'post');
    }
}