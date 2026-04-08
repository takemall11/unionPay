<?php

declare(strict_types=1);

namespace UnionPay\Api\Functions\Union;

use GuzzleHttp\Exception\GuzzleException;
use UnionPay\Api\Core\BaseClient;

/**
 * 订单模块
 */
class QRCodePayShortcut extends BaseClient
{
    public string $url = '/v6';

    /**
     * @return void
     */
    protected function setParams(): void
    {
        $this->app->baseParams['payMode']                 = "CODE_SCAN";
        $this->app->baseParams['deviceType']              = "11";
        $this->app->baseParams['transactionCurrencyCode'] = "156";
    }

    /**
     * 创建订单
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function createOrder(array $params): array
    {
        return $this->curlRequest($params, 'post');
    }
}
