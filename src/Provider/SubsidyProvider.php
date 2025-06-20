<?php

declare(strict_types=1);

namespace UnionPay\Api\Provider;

use UnionPay\Api\Core\Container;
use UnionPay\Api\Functions\Union\CloudMiniPayShortcut;
use UnionPay\Api\Functions\Union\MiniPayShortcut;
use UnionPay\Api\Functions\Union\SubsidyShortcut;
use UnionPay\Api\Interfaces\Provider;

/**
 * Class SubsidyProvider
 * @package UnionPay\Api\Provider
 */
class SubsidyProvider implements Provider
{
    /**
     * 服务提供者
     * @param Container $container
     */
    public function serviceProvider(Container $container): void
    {
        $container['subsidy'] = function ($container) {
            return new SubsidyShortcut($container, '/gdhlg');
        };
    }
}
