# 适配hyperf的聚合美的支付平台SDK
## Installing

```shell
$ composer require nahuomall/mideapay -vvv
```

## Usage

```php
        $obj = \Hyperf\Support\make(\UnionPay\Api\Wechat::class)
        
        $obj->setMchId('你的商户ID');
        // app
        $res =$obj->app->createOrder([]);
        
        var_dump($res);
```

更新日志：

1.修复php8下，因为强类型返回参数报错问题
2.增加hyperf 协程 Guzzle client 客户端
3.修复部分bug
4.重新设计sdk架构
5.处理替换curl

## License

MIT
# mideaPay
