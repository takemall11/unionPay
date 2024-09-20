<?php

declare(strict_types=1);

namespace UnionPay\Api\Interfaces;

interface Params
{
    /**
     * PUBLIC属性转数组
     * @return array
     */
    public function build(): array;
}
