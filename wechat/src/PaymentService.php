<?php
/**
 * Created by PhpStorm.
 * User: fuqixue
 * Date: 2017/1/3
 * Time: 上午2:17
 */

namespace Eyuan\Payment\Wechat;


use Eyuan\AuthExtend\Model\Member;
use Eyuan\AuthExtend\Model\SocialLogins;

use eyuan\basement\util\Response;
use eyuan\basement\validation\Validator;

class PaymentService  implements PaymentInterface{


    /**
     * 创建本地同意支付订单
     * @param  $data
     * @return $order
     */
    public function createOrder()
    {
        // TODO: Implement createOrder() method.
    }

    /**
     * 发起支付
     * @param array $data 发起支付数据
     * @return floor true|false 返回支付情况
     */
    public function sendPay($data = [])
    {
        // TODO: Implement sendPay() method.
    }

    /**
     * 处理异步通知
     * @return mixed
     */
    public function callback()
    {
        // TODO: Implement callback() method.
    }
}