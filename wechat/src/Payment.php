<?php
/**
 * Created by PhpStorm.
 * User: fuqixue
 * Date: 2017/1/3
 * Time: 上午2:17
 */

namespace Eyuan\Payment\Wechat;


use EasyWeChat\Foundation\Application;
use EasyWeChat\Payment\Order;

use eyuan\basement\service\Service;
use eyuan\basement\util\Response;
use eyuan\basement\validation\Validator;


use Eyuan\Payment\Wechat\Models\PaymentOrderModel;
use Eyuan\Wexin\Pay\WechatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Payment  extends Service implements PaymentInterface{


    protected $order_data=[];


    private $assend=true;

    /*
         * 交易类型
         * JSAPI--公众号支付、NATIVE--原生扫码支付、APP--app支付，统一下单接口trade_type的传参可参考这里
         * MICROPAY--刷卡支付，刷卡支付有单独的支付接口，不调用统一下单接口
         * */
    const trade_type_arr=['JSAPI','NATIVE','APP'];
    const trade_type_mic_arr=['MICROPAY'];

    /*
     * 支付方式参数
     */
    protected $trade_type='JSAPI';

    /*
     *是否刷卡支付
     */
    protected $micropay=false;
    /*
     * 支付sdk参数
     * 前面的appid什么的也得保留哦
     * 'app_id' => 'xxxx',
     * 'payment' => [
     *   'merchant_id'        => 'your-mch-id',
     *   'key'                => 'key-for-signature',
     *   'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
     *   'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
     *   'notify_url'         => '默认的订单回调地址',       // 你也可以在下单时单独设置来想覆盖它
     * ],
    */
    protected function __construct() {
        parent::__construct();
        $this->options=config('payment.wechat');
        $this->options['payment']['cert_path']=base_path($this->options['payment']['cert_path']);
        $this->options['payment']['key_path']=base_path($this->options['payment']['key_path']);
    }
    /**
     * 创建本地同意支付订单
     * @param  $data ['user_id','id','order_name','price','product_type','payment_platform']
     * @return $order
     */
    public function createOrder($order=[])
    {

        if(!empty($order)){
            $this->order_data=$order;
        }

        // TODO: Implement createOrder() method.
        $va=Validator::make($this->order_data, [
            'user_id'=>'required',
            'id'=>'required',
            'order_name'=>'required',
            'price'=>'required',
            'payment_platform'=>'required',
            'product_type'=>'required|Integer',
        ]);
        if ($va->fails()) {
            return Response::error($code=400, $va->errors());
        }

        $data=[

            'product_id'=>$this->order_data['id'],
            'order_name'=>$this->order_data['order_name'],
            'price'=>$this->order_data['price'],
            'payment_money'=>$this->order_data['price'],
            'payment_platform'=>$this->order_data['payment_platform'],
            'product_type'=>$this->order_data['product_type'],
            'user_id'=>$this->order_data['user_id'],
            'order_no'=>time(),
        ];


        $info=PaymentOrderModel::query()->firstOrCreate($data);
        $rel=$info->save();

        if($this->assend&&$rel){

          return  $this->sendPay($info);

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
//        $wechat=WechatService::getInstance();
        return $this->send($data);
    }

    /**
     * 设置订单数据
     * @param $order
     */
    public function setOrder($order){

        $this->order_data=$order;
    }

//    /**
//     * 允许发起支付
//     */
//    public function asSend(){
//        $this->assend=true;
//    }



    /**
     * 返回支付订单
     * @param $trade_no
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function queryFind($trade_no){
        return PaymentOrderModel::query()->where('trade_no',$trade_no)->first();
    }



    /**
     * 创建支付订单发起支付
     * @param $data ['order_name','order_no','price']
     * @return \EasyWeChat\Support\Collection
     */
    public function send($data){
        $openid=Auth::guard('member')->user()->username;

        $app=new Application($this->options);
        $luckyMoney = $app->payment;
        $attributes=[
            'trade_type'       => $this->trade_type, // JSAPI，NATIVE，APP...
            'body'             => $data['order_name'],
            'detail'           => $data['order_name'],
            'out_trade_no'     => $data['order_no'],
            'total_fee'        => $data['price'],
            'notify_url'       => $this->options['payment']['notify_url'], // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        ];
        $order=new Order($attributes);


        $result= $this->micropay?$luckyMoney->pay($order):$luckyMoney->prepare($order);

        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){

            return true;
//            $prepayId = $result->prepay_id;
        }else{
            return false;
        }
    }

    /**
     * 输出统一支付数据
     * @param $return
     * @return mixed
     */
    private function paySuccess($return){
        return $return;
    }

    /**
     * 设置支付方式
     * @param $type
     */
    public function trade_type($type){
        $this->trade_type=$type;
        $this->micropay();
    }

    /**
     * 设置刷卡支付开关
     */
    public function micropay(){
        $this->micropay=in_array($this->trade_type,self::trade_type_arr)?false:true;
    }

    /**
     * 处理异步通知
     * @return mixed
     */
    public function callback(){
        // TODO: Implement callback() method.
        $app=new Application($this->options);
        $response = $app->payment->handleNotify(function($notify, $successful){

            $payment=Payment::getInstance();
            $order=$payment->queryFind($notify->transaction_id);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->paid_at) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                $order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 'paid';
            } else { // 用户支付失败
                $order->status = 'paid_fail';
            }
            $order->save(); // 保存订单
            // 你的逻辑
            return true; //  返回处理完成
        });
        Log::info($response);
        return $response;
    }


}