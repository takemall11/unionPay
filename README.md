# 适配hyperf的聚合支付平台SDK

## Installing

```shell
$ composer require nahuomall/unionpay -vvv
```

## Usage

```php
        $obj = \Hyperf\Support\make(\UnionPay\Api\Wechat::class)
        
        $obj->setMchId('你的商户ID');
        // app
        $res =$obj->app->createOrder([]);
        
        var_dump($res);
```

## License
MIT