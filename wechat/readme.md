## 使用说明

---
use Eyuan\Payment\Wechat;
### Payment

Payment可用的方法同laravel facade

---
配置：
app配置文件添加：
providers=[
    \Eyuan\Payment\Wechat\PaymentServiceProvider::class,
]
控制台执行命令：php artisan vendor:publish --tag=payment

### Payment 使用方法2：
--
$payment=Payment::getInstance();
--

#### 创建统一支付订单
```
    $order=[
                'user_id'=>6,
                'id'=>62,
                'order_no'=>'TC'.time(),
                'order_name'=>'测试支付1元',
                'price'=>'1',
                'product_type'=>'1',
                'payment_platform'=>'1',
            ];

    $info=$payment->createOrder($order)
     return view($info?'frontend.payment.success':'frontend.pay_error');
```

#### 异步通知调用
```
    return $payment->callback();
```
