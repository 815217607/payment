<?php
/**
 * Created by PhpStorm.
 * User: fuqixue
 * Date: 2017/1/3
 * Time: 上午2:17
 */

namespace Eyuan\Payment;


use Eyuan\AuthExtend\Model\Member;
use Eyuan\AuthExtend\Model\SocialLogins;

use eyuan\basement\service\Service;
use eyuan\basement\util\Response;
use eyuan\basement\validation\Validator;
use Eyuan\Payment\Models\PaymentOrder;
use Eyuan\Payment\Models\PaymentOrderModel;

class Payment  extends Service implements PaymentInterface{

    /**
     * 标示
     * @var
     */
    protected $appid;
    /**
     * 商家ID
     * @var
     */
    protected $mahid;
    /**
     * key
     * @var
     */
    protected $key;
    /**
     * 秘钥
     * @var
     */
    protected $secret;

    protected $order_data=[];


    private $assend=false;

    protected function __construct() {
        parent::__construct();
        $this->appid=env('WECHAT_APP_ID');
        $this->mahid=env('WECHAT_MCH_ID');
        $this->key=env('WECHAT_KEY');
        $this->secret=env('WECHAT_SECRET');
    }
    /**
     * 创建本地同意支付订单
     * @param  $data
     * @return $order
     */
    public function createOrder()
    {
        // TODO: Implement createOrder() method.
        $va=Validator::make($this->order_data, [
            'user_id'=>'required',
            'id'=>'required',
            'order_name'=>'required',
            'price'=>'required',
            'payment_money'=>'required',
            'payment_platform'=>'required',
            'product_type'=>'required|Integer',
        ]);
        if ($va->fails()) {
            return Response::error($code=400, $va->errors());
        }

        $data=[
            'user_id'=>$this->order_data['user_id'],
            'product_id'=>$this->order_data['id'],
            'order_name'=>$this->order_data['order_name'],
            'price'=>$this->order_data['price'],
            'payment_money'=>$this->order_data['price'],
            'payment_platform'=>$this->order_data['payment_platform'],
            'product_type'=>$this->order_data['product_type'],

        ];
        $info=PaymentOrderModel::query()->firstOrNew($data);
        $info->order_no=$this->generate_orderSn();
        $info->paid_at=date('Y-m-d H:i:s');
        $info->status=0;
        $rel= $info->save();
        if($this->assend&&$rel){

            $this->sendPay($info);

        }
    }

    /**
     * 发起支付
     * @param array $data 发起支付数据
     * @return floor true|false 返回支付情况
     */
    public function sendPay($data=[])
    {
        // TODO: Implement sendPay() method.
        $data=is_array($data)?$data:json_decode(json_encode($data),true);
        
    }

    /**
     * 处理异步通知
     * @return mixed
     */
    public function callback()
    {
        // TODO: Implement callback() method.
    }

    public function setOrder($order){

        $this->order_data=$order;
    }
    public function asSend(){
        $this->assend=true;
    }
    private  function generate_orderSn(){
        $sn1 = date("ymdHis");
        $sn2 = '0' . '00';
        $sn3 = self::generate_str(7,'0123456789');//7位随机数
        return $sn1 . $sn2 . $sn3;
    }
}