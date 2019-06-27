<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



use App\Models\Order;
use App\User;
use App\Models\UserLevel;
use Flash;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use EasyWeChat;
use EasyWeChat\Factory;


class PayController extends Controller
{


    public function __construct()
    {

    }

    /**
     * [购买,升级 会员]
     * @param  Request $request [input参数有price member_id]
     * @param  string  $action  [buy,update 买,升级]
     * @return [type]           [description]
     */
    public function buyActionMember(Request $request,$action='buy'){
        $input = $request->all();

        #用户
        $user = $this->user();
        #价格
        $price= $input['price'];
        #购买的会员id
        $member_id = $input['member_id'];
        #订单类型
        $type=$action=='buy'?'普通':'升级';

        #创建订单
        $order = Order::create([
            'price' => $price,
            'order_pay' => '未支付',
            'type'=>$type,
            'user_id' => $user->id,
            'user_level_id'=>$member_id,
            'pay_platform' => '微信支付',
            //'pay_no' => time().'_'.random_int(1, 20000),
            'out_trade_no'=>time().'_'.random_int(1, 20000)
        ]);

        //return ['code' => 0, 'message' => 'ss'];

        $body = $action=='buy'?'会员购买':'会员升级';

        $order_no = $order->out_trade_no;

        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => $body,
            'detail'           => '订单编号:'.$order->id,
            'out_trade_no'     => $order_no,
            'total_fee'        => intval($order->price * 100 ), // 单位：分
            'notify_url'       => $request->root().'/notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => $user->openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            'attach'           => '会员'
        ];
      

        $payment = Factory::payment(Config::get('wechat.payment.default'));
        //Log::info($payment);
        $result = $payment->order->unify($attributes);
  
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
            $prepayId = $result['prepay_id'];
            $json = $payment->jssdk->bridgeConfig($prepayId);
         
            return ['code' => 0, 'message' => $json];

        }else{
             return ['code' => 1, 'message' => '支付失败'];
             //$payment->payment->reverse($order_no);
        }

    }

    public function payNotify(Request $request)
    {
        $payment = Factory::payment(Config::get('wechat.payment.default'));
        $self = $this;
        $response = $payment->handlePaidNotify(function($message, $fail) use ($self){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单

            $order = Order::where('out_trade_no', $message['out_trade_no'])->first();

            if (empty($order)) { // 如果订单不存在
                $fail('订单不存在'); // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->order_pay == '已支付') {
                // 已经支付成功了就不再更新了
                return true; 
            }

            // 用户是否支付成功
            if ($message['result_code'] === 'SUCCESS') {
                $self->processOrder($order);
            } else { // 用户支付失败

            }

            return true; // 返回处理完成
        });

        return $response;
    }

    // 支付成功后，处理订单信息
    private function processOrder($order){
        $order->update(['order_pay' => '已支付']);

        //删除未支付的订单
        Order::where('user_id', $order->user_id)->where('order_pay', '未支付')->delete();
        //更新会员等级
        $user = User::find($order->user_id);
        
        if (!empty($user)) {
            $user->update([
                'user_level'=>$order->user_level_id,
                'member_buy_time' => Carbon::now(), 
                'is_distribute'   =>  1,
                'member_end_time' => Carbon::now()->addYears(1),
            ]);
           #验证一下用不用加佣金
           app('user')->varifyDistributMoney($user,$order);
        }
 
    }

}
