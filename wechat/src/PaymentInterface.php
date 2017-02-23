<?php
/**
 * Created by PhpStorm.
 * User: fuqixue
 * Date: 2017/1/5
 * Time: 上午10:34
 */

namespace Eyuan\Payment;



interface PaymentInterface {
    /**
     * 创建本地同意支付订单
     * @param  $data
     * @return $order
     */
    public  function createOrder();

    /**
     * 发起支付
     * @param array $data 发起支付数据
     * @return floor true|false 返回支付情况
     */
    public  function sendPay($data=[]);

    /**
     * 处理异步通知
     * @return mixed
     */
    public function callback();



}
