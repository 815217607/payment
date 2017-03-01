## 使用说明

---
use Eyuan\Payment\Wechat;
### Payment

Payment可用的方法同laravel facade

---
配置：
.env

WEIXIN_PAY_APPID=
WEIXIN_PAY_MCHID=
WEIXIN_PAY_KEY=
WEIXIN_PAY_SECRET=
WEIXIN_APY_CERT_PATH=
WEIXIN_APY_KEY_PATH=
WEIXIN_NOTIFY_PAY_URL=http://host/payment/alipay_callback

app配置文件添加：
providers=[
    \Eyuan\Payment\Wechat\PaymentServiceProvider::class,
]
控制台执行命令：php artisan vendor:publish --tag=payment



### Payment 使用方法2：
--
$payment=Payment::getInstance();
--
createOrder($peymentorder,'成功后重定向地址','支付失败后重定向地址')
#### 创建统一支付订单
```
    $order=[
           'user_id'=>6,
           'product_id'=>62,
           'order_no'=>'TC'.time(),
           'order_name'=>'测试支付1元',
           'price'=>'1',
           'product_type'=>'1',
           'payment_platform'=>'1',
       ];
    $send=Payment::getInstance();

    $info=$send->createOrder($order,'/','/');
    return view(($info?'frontend.payment.success':'frontend.pay_error'),$info)
```

#### 异步通知调用
```
    return $payment->callback();
```
