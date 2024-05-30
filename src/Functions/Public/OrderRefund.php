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
        $this->app->baseParams['instMid'] = "MINIDEFAULT";
    }

    /**
     * 统一退款
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function refund(array $params): array
    {
        $this->setParams();
        return $this->curlRequest($params, 'post');
    }
}