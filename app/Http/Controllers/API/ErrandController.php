<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use EasyWeChat\Factory;
use Config;
use Log;

class ErrandController extends Controller
{
    //选择学校后添加学校
    public function selectAndAddSchool(Request $request){
        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,app('zcjy')->schoolRepo()->model()::$rules,'key');
        if($varify){
            return zcjy_callback_data($varify,1);
        }
        $school = app('zcjy')->schoolRepo()->model()::where('name',$input['name'])->first();
        if(empty($school)){
            app('zcjy')->schoolRepo()->create($input);
        }
        $user = zcjy_api_user($input);
        #带上学校
        $user->update(['school'=>$input['name']]);
        return zcjy_callback_data('选择学校成功');
    }

    //所有的任务模板
    public function allTems(Request $request){
        return zcjy_callback_data(app('zcjy')->taskTemRepo()->all());
    }

    //发起提现
    public function publishWithDraw(Request $request){
        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,app('zcjy')->withDrawalLogRepo()->model()::$rules,'key');
        if($varify){
            return zcjy_callback_data($varify,1);
        }
        $min_withdrawal_price = empty(getSettingValueByKey('min_withdrawal_price')) ? 0 : getSettingValueByKey('min_withdrawal_price');
        if($input['price'] < $min_withdrawal_price){
            return zcjy_callback_data('最低满'.$min_withdrawal_price.'元提现!',1);
        }
        $user = zcjy_api_user($input);
        if($input['price'] > $user->user_money){
            return zcjy_callback_data('您的余额不足以提现!',1);
        }
        $input['user_id'] = $user->id;
        #扣除账户余额
        $user->update(['user_money'=>round($user->user_money-$input['price'],2)]);
        app('zcjy')->withDrawalLogRepo()->create($input);
        return zcjy_callback_data('提现成功');
    }


    //发布跑腿任务
    public function publishErrandTask(Request $request){
        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,app('zcjy')->errandTaskRepo()->model()::$rules,'key');
        if($varify){
            return zcjy_callback_data($varify,1);
        }
        if(!array_key_exists('item_cost',$input)){
            $input['item_cost'] = 0;
        }
        $input['user_id'] = zcjy_api_user($input)->id;
        #处理期望送达时间
        //$input = $this->dealwithRemainAndWishTime($input);
        #最低打赏金额验证
        $errand_min_price = empty(getSettingValueByKey('errand_min_price')) ? 0 : getSettingValueByKey('errand_min_price');
        if($input['give_price'] < $errand_min_price){
            return zcjy_callback_data('最低打赏金额为'.$errand_min_price.'元',1);
        }
        #该任务需要支付金额
        $input['pay_price'] = round($input['give_price'] + $input['item_cost'],2);
        #平台比例
        $platform_scale = empty(getSettingValueByKey('platform_scale')) ? 0 : getSettingValueByKey('platform_scale');
        #平台提取费用
        $input['platform_price'] = round($input['give_price']*$platform_scale/100,2);
        #需要给买手的费用
        $input['errander_get_price'] = round(($input['pay_price'] - $input['platform_price']),2);
        #创建任务
        $task=app('zcjy')->errandTaskRepo()->model()::create($input);
        //$task['current_remain_time'] = $task['current_remain_time']->format('Y-m-d H:i:s');
        //$task['current_wish_time'] = $task['current_wish_time']->format('Y-m-d H:i:s');
        #添加备注图片
        if(array_key_exists('images',$input)){
            if(!is_array($input['images'])){
                $input['images'] = explode(',', $input['images']);
            }
            app('zcjy')->errandTaskRepo()->syncImages($input['images'],$task->id);
        }
        #付钱
        return zcjy_callback_data(['task'=>$task,'message'=>'发布跑腿任务成功']);
    }

    //发布人删除任务
    public function delErrandTask($id){
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);
        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }
        if($task->status == '已收货' || $task->errand_status == '已收款'){
            return zcjy_callback_data('该任务已完结!',1);
        }
        // if($task->pay_status == '已支付'){
        //    return zcjy_callback_data('该任务已支付!',1);
        // }
        $task->delete();
        return zcjy_callback_data('删除任务成功');
    }

    //发布人取消订单
    public function cancleErrandTask($id){
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);
        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }
        if($task->status == '已收货' || $task->errand_status == '已收款'){
            return zcjy_callback_data('该任务已完结!',1);
        }
        if($task->pay_status == '未支付'){
           return zcjy_callback_data('该任务未支付!',1);
        }

        $task->update(['status'=>'已取消']);
        #退钱给用户
        $user = user_by_id($task->user_id);
        $user->update(['user_money'=>round($user->user_money+$task->pay_price,2)]);
        #加上记录
        app('zcjy')->refundLogRepo()->create(['price'=>$task->pay_price,'reason'=>'手动取消退回','content'=>'手动取消退回退款'.$task->pay_price.'元','user_id'=>$user->id]);
        return zcjy_callback_data('取消任务成功');
    }

    //处理期望送达时间
    private function dealwithRemainAndWishTime($input){
        $current_remain_time = Carbon::now();

        #处理剩余时间
        $remain_hour = $input['remain_time_hour'];
        $remain_min = $input['remain_time_min'];

        if(!empty($remain_hour)){
            $current_remain_time = $current_remain_time->addHours($remain_hour);
        }

        if(!empty($remain_min)){
            $current_remain_time = $current_remain_time->addMinutes($remain_min);
        }

        $current_wish_time = Carbon::now();

        #处理剩余时间
        $wish_hour = $input['wish_time_hour'];
        $wish_min = $input['wish_time_minute'];

        if(!empty($wish_hour)){
            $current_wish_time = $current_wish_time->addHours($wish_hour);
        }

        if(!empty($wish_min)){
            $current_wish_time = $current_wish_time->addMinutes($wish_min);
        }

        $input['current_remain_time'] = $current_remain_time;
        $input['current_wish_time'] = $current_wish_time;
        #处理希望送达时间
        return $input;
    }

    //发布者发起任务支付
    public function payErrandTask(Request $request,$id) {
        $input = $request->all();
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);
        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }
        if($task->status == '已收货' || $task->errand_status == '已收款'){
            return zcjy_callback_data('该任务已完结!',1);
        }
        if($task->pay_status == '已支付'){
           return zcjy_callback_data('该任务已支付!',1);
        }

        $out_trade_no = $id.'_'.time();
 
        $body = '支付订单'.$out_trade_no.'费用';

        $task->update(['out_trade_no' => $out_trade_no]);

        $user = zcjy_api_user($input);

        $attributes = [
            'trade_type'       => 'JSAPI', // JSAPI，NATIVE，APP...
            'body'             => $body,
            'detail'           => '订单编号:'.$out_trade_no,
            'out_trade_no'     => $out_trade_no,
            'total_fee'        => intval( $task->pay_price * 100 ), // 单位：分
            'notify_url'       => $request->root().'/notify_wechcat_pay', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'openid'           => $user->openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            'attach'           => '支付订单',
        ];
        $payment = Factory::payment(Config::get('wechat.payment.xiaochengxu'));

        $result = $payment->order->unify($attributes);

        Log::info($result);
        
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
            $prepayId = $result['prepay_id'];
            $json = $payment->jssdk->bridgeConfig($prepayId);
            return zcjy_callback_data($json);
        }
        else{
            return zcjy_callback_data('支付失败',1);
        }
        //$task->update(['pay_status'=>'已支付']);
    }

    //监听通知任务支付成功
    public function notifyPay(Request $request){
        $payment = Factory::payment(Config::get('wechat.payment.default'));
        $response = $payment->handlePaidNotify(function($message, $fail){
            $order = app('zcjy')->errandTaskRepo()->model()::where('out_trade_no', $message['out_trade_no'])->first();
            if (empty($order)) { // 如果订单不存在
                return true; 
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->pay_status == '已支付') {
                // 已经支付成功了就不再更新了
                return true; 
            }
            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $order->update(['pay_status'=>'已支付']);
                // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    
                }
            } 
            else {
                return $fail('通信失败，请稍后再通知我');
            }
            return true; // 返回处理完成
        });
        return $response;
    }

    //对应学校的跑腿任务
    public function schoolTasks(Request $request){
        $input = $request->all();
        $varify = app('zcjy')->varifyInputParam($input,['school_name']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }

        $skip = 0;
        $take = $this->defaultPage();

        if(array_key_exists('skip',$input)){
            $skip = $input['skip'];
        }

        if(array_key_exists('take',$input)){
            $take = $input['take'];
        }

        $tasks = $this->defaultSearchState(app('zcjy')->errandTaskRepo())
        ->where('school_name',$input['school_name'])
        ->where(function ($query){
             $query
            ->where('pay_status','已支付')
            ->orWhere('wait_buyer_enter',1);
        })
        ->where('status','<>','已收货')
        ->where('status','<>','已取消')
        ;
        $tasks =  $tasks->skip($skip)->take($take)->get();
        $tasks = app('zcjy')->errandTaskRepo()->attachPulisherAndErranderInfo($tasks);
        return zcjy_callback_data($tasks);
    }

    //任务详情
    public function errandTaskDetail(Request $request,$id){
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);
        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }
        if($task->status == '已取消'){
            return zcjy_callback_data('该任务已取消!',1);
        }
        // if($task->status == '已收货' || $task->errand_status == '已收款'){
        //     return zcjy_callback_data('该任务已完结!',1);
        // }
        $tasks = collect([$task]);
        $tasks = app('zcjy')->errandTaskRepo()->attachPulisherAndErranderInfo($tasks);
        return zcjy_callback_data($tasks[0]);
    }

    //买手接单
    public function errandTakeOrderTask(Request $request,$id){
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);
        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }
        if($task->status == '待收货'){
            return zcjy_callback_data('该任务已被接单!',1);
        }
        if($task->status == '已收货' || $task->errand_status == '已收款'){
            return zcjy_callback_data('该任务已完结!',1);
        }
        $user = zcjy_api_user($request->all());
        if($task->user_id == $user->id){
            return zcjy_callback_data('自己不能接自己的任务',1);
        }
        $task->update(['status'=>'待收货','errand_id'=>$user->id]);
        return zcjy_callback_data('接单成功');
    }

    //买手取消订单
    public function errandCancleOrderTask(Request $request,$id){
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);
        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }
        $user = zcjy_api_user($request->all());
        if(empty($task->errand_id)){
            return zcjy_callback_data('该任务还没有人接单');
        }
        if($user->id != $task->errand_id){
            return zcjy_callback_data('自己才能取消自己的跑腿任务',1);
        }
        if($task->errand_status == '确认送达'){
             return zcjy_callback_data('该任务已确认送达',1);
        }
        if($task->errand_status == '已收款'){
            return zcjy_callback_data('该任务已收款',1);
        }
        $status = '已发布';

        #已经取消过得不能再重置了
        if($task->status == '已取消'){
            $status = '已取消';
        }

        $item_cost = null;

        if($task->price_type == '需支付物品费用'){
            $item_cost = $task->item_cost;
        }

        $pay_price = $task->pay_price;

        if($task->wait_buyer_enter == 1){
            $item_cost = null;
            if(!empty($task->item_cost)){
                 $pay_price = round($task->pay_price - $task->item_cost,2);
            }
        }

        #已经确认过物费的 把物费重置为0
        $task->update(['errand_id'=>0,'errand_status'=>'待送达','status'=>$status,'pay_price'=>$pay_price,'item_cost'=>$item_cost]);
        return zcjy_callback_data('取消任务成功');
    }

    //买手确认物品费用
    public function errandEnterItemCostTask(Request $request,$id){
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);

        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }

        if($task->wait_buyer_enter == 0){
            return zcjy_callback_data('该任务物品费用已确认',1);
        }

        $input = $request->all();

        $varify = app('zcjy')->varifyInputParam($input,['item_cost']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }

        $user = zcjy_api_user($input);

        if($task->user_id == $user->id){
            return zcjy_callback_data('自己不能接自己的任务',1);
        }

        #该任务需要支付金额
        $input['pay_price'] = round($task->give_price + $input['item_cost'],2);
        #需要给买手的费用
        $input['errander_get_price'] = round(($input['pay_price'] - $task->platform_price),2);

        $task->update($input);

        return zcjy_callback_data('确认物品费用成功!请等待发布者支付!');
    }

    //发布者的任务列表  已发布 待收货 已收货 /    //买手的任务列表   待送达 已收款
    public function publisherTasks(Request $request,$type='publisher'){
        $input = $request->all();
        $skip = 0;
        $take = $this->defaultPage(); 

        if(array_key_exists('skip',$input)){
            $skip = $input['skip'];
        }

        if(array_key_exists('take',$input)){
            $take = $input['take'];
        }

        $user = zcjy_api_user($input);
        $tasks = $this->defaultSearchState(app('zcjy')->errandTaskRepo());
        #发布者
        if($type == 'publisher'){
            $status = '已发布';
            if(array_key_exists('status',$input) && !empty($input['status'])){
                $status = $input['status'];
            }
            if($status == '已发布'){
                $tasks = $tasks
                ->where('user_id',$user->id)
                ->where(function ($query) use ($input){
                    $query
                    ->where('status',$input['status'])
                    ->orWhere('status','已取消');
                });
            }
            else{
                $tasks = $tasks
                ->where('user_id',$user->id)
                ->where('status',$input['status']);
            }
        }#买手
        else{
            $status = '待送达';
            if(array_key_exists('status',$input) && !empty($input['status'])){
                $status = $input['status'];
            }
           
            if($status == '待送达'){
                $tasks = $tasks->where('errand_id',$user->id)
                         ->where(function ($query) use ($input){
                            $query
                            ->where('errand_status',$input['status'])
                            ->orWhere('errand_status','确认送达');
                        });
            }
            else{
                 $tasks = $tasks
                ->where('errand_id',$user->id)
                ->where('errand_status',$input['status']);
            }
        }
        $tasks = $tasks->skip($skip)->take($take)->get();
        #过滤自己的
        $tasks = $tasks->filter(function($item,$key) use ($user,$type){
           if($type == 'publisher'){
                return $user->id == $item->user_id;
           }
           else{
                return $user->id != $item->user_id;
           }  
        });
        $tasks_arr = [];
        foreach ($tasks as $key => $value) {
            array_push($tasks_arr,$value);
        }
        #带上买手信息
        $tasks_arr = app('zcjy')->errandTaskRepo()->attachPulisherAndErranderInfo($tasks_arr);
        return zcjy_callback_data($tasks_arr);
    }

    //发布人确认收货
    public function publishManEnterTaskRec(Request $request,$id){
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);
        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }

        if($task->status == '已发布' || $task->errand_status == '待送达'){
            return zcjy_callback_data('该任务还未送达!',1);
        }

        if($task->status == '已收货' || $task->errand_status == '已收款'){
            return zcjy_callback_data('该任务已完结!请勿重复提交',1);
        }

        $user = zcjy_api_user($request->all());

        if($task->user_id != $user->id){
            return zcjy_callback_data('该任务不是由你发布!',1);
        }

        $task->update(['status'=>'已收货','errand_status'=>'已收款']);
        #把钱给买手
        $errander = user_by_id($task->errand_id);
        $errander->update(['user_money'=>round($errander->user_money+$task->errander_get_price,2)]);
        return zcjy_callback_data('收货成功');
    }

    //买手确认送达
    public function buyerEnterTaskArrive(Request $request,$id){
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);
        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }

        if($task->status == '已收货' || $task->errand_status == '已收款'){
            return zcjy_callback_data('该任务已完结!请勿重复提交',1);
        }

        $user = zcjy_api_user($request->all());
        
        if($task->errand_id != $user->id){
            return zcjy_callback_data('该任务不是由你接手无法提交!',1);
        }

        $task->update(['errand_status'=>'确认送达']);
        return zcjy_callback_data('确认送达成功');
    }

    //我的钱包 收入/支出
    public function myPublishAndErrandLog(Request $request){
        $input = $request->all();
        $skip = 0;
        $take = $this->defaultPage(); 

        if(array_key_exists('skip',$input)){
            $skip = $input['skip'];
        }

        if(array_key_exists('take',$input)){
            $take = $input['take'];
        }

        $user = zcjy_api_user($input);
       
        #publisher
        $publish_log = app('zcjy')->errandTaskRepo()->myTaskLog('publisher',$user->id,$skip,$take);
        #errand_log
        $errand_log = app('zcjy')->errandTaskRepo()->myTaskLog('errander',$user->id,$skip,$take);

        #提现记录
        $withdraw_log = app('zcjy')->withDrawalLogRepo()->model()::where('user_id',$user->id)
        ->skip($skip)
        ->take($take)
        ->get();

        #退款记录
        $refund_log = app('zcjy')->refundLogRepo()->model()::where('user_id',$user->id)->orderBy('created_at','desc')->get();
        foreach ($refund_log as $key => $value) {
            $value['format_time'] = $value->created_at->diffForHumans();
        }
        return zcjy_callback_data(['publish_log'=>$publish_log,'errand_log'=>$errand_log,'withdraw_log'=>$withdraw_log,'refund_log'=>$refund_log ]);
    }

    //投诉发起者/买手
    public function errorErrandTask(Request $request,$id,$type=null){
        $input = $request->all();
        $task = app('zcjy')->errandTaskRepo()->findWithoutFail($id);

        if(empty($task)){
            return zcjy_callback_data('没有该任务!',1);
        }

        if(empty($type)){
            return zcjy_callback_data('参数错误',1);
        }

        $varify = app('zcjy')->varifyInputParam($input,['type','reason']);

        if($varify){
            return zcjy_callback_data($varify,1);
        }
        
        $user = zcjy_api_user($input);
        $input['task_id'] = $id;
        $input['user_id'] = $user->id;
        $input['errand_id'] = $task->errand_id;
        $input['send_type'] = '发起';
        //任务发起者投诉买手 用户发起 买手收到
        if($type=='publisher'){
            #用户发起
            app('zcjy')->errandErrorRepo()->create($input);
            #买手收到
            $input['send_type'] = '收到';
            $input['user_id'] = $task->errand_id;
            app('zcjy')->errandErrorRepo()->create($input);
        }#买手发起 用户收到
        else{
             app('zcjy')->errandErrorRepo()->create($input);
             $input['send_type'] = '收到';
             $input['user_id'] = $task->user_id;
             app('zcjy')->errandErrorRepo()->create($input);
        }
        return zcjy_callback_data('投诉成功!');


    }

}
