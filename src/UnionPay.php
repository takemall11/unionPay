<?php

declare(strict_types=1);

namespace UnionPay\Api;

use UnionPay\Api\Core\ContainerBase;
use UnionPay\Api\Provider\UnionPayProvider;
use UnionPay\Api\Provider\SearchProvider;

/**
 * Class UnionPay
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
        //...其他服务提供者
    ];
}
