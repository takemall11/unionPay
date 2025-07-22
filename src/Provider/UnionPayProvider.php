<?php

declare(strict_types=1);

namespace UnionPay\Api\Provider;

use UnionPay\Api\Core\Container;
use UnionPay\Api\Functions\Union\CloudMiniPayShortcut;
use UnionPay\Api\Functions\Union\MiniPayShortcut;
use UnionPay\Api\Interfaces\Provider;

/**
 * Class UnionPayProvider
 * @package UnionPay\Api\Provider
 */
class UnionPayProvider implements Provider
{
    /**
     * 服务提供者
     * @param Container $container
     */
    public function serviceProvider(Container $container): void
    {
        $container['wechatMini'] = function ($container) {
            return new MiniPayShortcut($container, '/wx/mini-pre-order');
        };
        $container['uacMini'] = function ($container) {
            return new CloudMiniPayShortcut($container, '/uac/mini-order');
        };
    }
}
