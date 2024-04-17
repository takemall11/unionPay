<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace UnionPay\Api\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
class UnionErrorCode extends AbstractConstants
{

    public const SERVER_ERROR = 500;


    /**
     * @Message("Order Type Not Found")
     */
    public const ORDER_TYPE_NOTFOUND = 10000001;

    /**
     * @Message("Pay Type Not Found")
     */
    public const PAY_TYPE_NOTFOUND = 10000002;

    /**
     * @Message("订单下单请求失败")
     */
    public const PAY_POST_ERROR = 10000003;

    /**
     * @Message("订单不存在")
     */
    public const ORDER_NOT_EXIST = 10000004;

    /**
     * @Message("第三方支付服务错误")
     */
    public const ORDER_SERVICE_ERROR = 10000005;

    /**
     * @Message("签名错误")
     */
    public const PAY_SIGN_ERROR = 10000006;
}
