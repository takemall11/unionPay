<?php

declare(strict_types=1);

namespace UnionPay\Api;

use UnionPay\Api\Core\ContainerBase;
use UnionPay\Api\Functions\Public\OrderDetail;
use UnionPay\Api\Functions\Public\OrderRefund;
use UnionPay\Api\Functions\Subsidy\FjSubsidyShortcut;
use UnionPay\Api\Functions\Subsidy\GxSubsidyShortcut;
use UnionPay\Api\Functions\Union\CloudMiniPayShortcut;
use UnionPay\Api\Functions\Union\MiniPayShortcut;
use UnionPay\Api\Functions\Union\SubsidyShortcut;
use UnionPay\Api\Provider\SearchProvider;
use UnionPay\Api\Provider\SubsidyProvider;
use UnionPay\Api\Provider\UnionPayProvider;

/**
 * Class UnionPay
 * @package  UnionPay\Api
 *
 * @property-read SubsidyShortcut $subsidy
 * @property-read SubsidyShortcut $queryApply
 * @property-read SubsidyShortcut $queryApplyTrace
 * @property-read OrderDetail $search
 * @property-read OrderDetail $refund_search
 * @property-read OrderRefund $refund
 * @property-read MiniPayShortcut $wechatMini
 * @property-read CloudMiniPayShortcut $uacMini
 * @property-read GxSubsidyShortcut $gxSubsidy
 * @property-read FjSubsidyShortcut $fjSubsidy
 */
class UnionPay extends ContainerBase
{
    /**
     * 服务提供者
     * @var array
     */
    protected array $provider = [
        UnionPayProvider::class,
        SearchProvider::class,
        SubsidyProvider::class
        //...其他服务提供者
    ];
}
