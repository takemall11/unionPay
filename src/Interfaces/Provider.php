<?php

declare(strict_types=1);

namespace UnionPay\Api\Interfaces;

use UnionPay\Api\Core\Container;

/**
 * Interface Provider
 * @package JavaReact\AlibabaOpen\interfaces
 */
interface Provider
{
    /**
     * @param Container $container
     * @return void
     */
    public function serviceProvider(Container $container): void;
}
