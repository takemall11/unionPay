<?php

declare(strict_types=1);

namespace UnionPay\Api\Provider;

use UnionPay\Api\Core\Container;
use UnionPay\Api\Functions\Subsidy\FjSubsidyShortcut;
use UnionPay\Api\Functions\Subsidy\GxSubsidyShortcut;
use UnionPay\Api\Functions\Union\CloudMiniPayShortcut;
use UnionPay\Api\Functions\Union\MiniPayShortcut;
use UnionPay\Api\Functions\Union\SubsidyShortcut;
use UnionPay\Api\Interfaces\Provider;

use function Hyperf\Support\env;

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
        $env = env('APP_ENV', 'dev');
        $container['subsidy'] = function ($container) use ($env) {
            return new SubsidyShortcut($container, $env === 'prod' ? '/benefits/web-api/gdhlg' : '/gdhlg');
        };
        $container['gxSubsidy'] = function ($container) {
            return new GxSubsidyShortcut($container, '/v1/inip/marketing/yjhx');
        };
        $container['fjSubsidy'] = function ($container) {
            return new FjSubsidyShortcut($container, '/v1/market/subsidy');
        };
    }
}
