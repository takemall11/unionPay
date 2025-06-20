<?php

declare(strict_types=1);

namespace UnionPay\Api\Functions\Union;

use GuzzleHttp\Exception\GuzzleException;
use UnionPay\Api\Core\BaseSubsidyClient;

/**
 * 订单模块
 */
class SubsidyShortcut extends BaseSubsidyClient
{
    /**
     * @return void
     */
    protected function setParams(): void
    {
        $this->app->baseParams['userId'] = $this->app->userId;
        $this->app->baseParams['reqSn'] = uniqid();
        $this->app->baseParams['signAlg'] = 'SHA256withRSA';
        $this->app->baseParams['timestamp'] = date('Y-m-d H:i:s');
    }

    /**
     * 国补台帐
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function push(array $params): array
    {
        $this->service = '/o2o/submitSubsidyApplTrace';
        return $this->curlRequest($params, 'post');
    }

    /**
     * 查询国补申请补录信息
     *
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function queryApplyTrace(array $params): array
    {
        $this->service = '/o2o/querySubsidyApplTrace';
        return $this->curlRequest($params, 'post');
    }

    /**
     * 查询国补申请单据
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function queryApply(array $params): array
    {
        $this->service = '/o2o/querySubsidyAppl';
        return $this->curlRequest($params, 'post');
    }


}
