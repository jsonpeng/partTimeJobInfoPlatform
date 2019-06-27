<?php

namespace App\Repositories;

use App\Models\ErrandTask;
use InfyOm\Generator\Common\BaseRepository;
use Carbon\Carbon;
use Log;
use App\Models\ErrandImage;
/**
 * Class ErrandTaskRepository
 * @package App\Repositories
 * @version July 5, 2018, 11:19 am CST
 *
 * @method ErrandTask findWithoutFail($id, $columns = ['*'])
 * @method ErrandTask find($id, $columns = ['*'])
 * @method ErrandTask first($columns = ['*'])
*/
class ErrandTaskRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'user_id',
        'tem_id',
        'remark',
        'give_price',
        'price_type',
        'item_cost',
        'wait_buyer_enter',
        'remain_time',
        'wish_time_hour',
        'wish_time_minute',
        'mobile',
        'status',
        'tem_word1',
        'tem_word2',
        'province',
        'city',
        'district',
        'address',
        'lat',
        'lon',
        'school_name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ErrandTask::class;
    }

    //用户的收入明细
    public function  myTaskLog($type = 'publisher',$user_id,$skip=0,$take=10){
        if($type == 'publisher'){
            $tasks = ErrandTask::where('user_id',$user_id);
        }
        else{
             $tasks = ErrandTask::where('errand_id',$user_id);
        }
        $tasks = $tasks
        ->where('pay_status','已支付')
        ->where('status','已收货')
        ->skip($skip)
        ->take($take)
        ->get();
        $tasks = $this->attachPulisherAndErranderInfo($tasks);
        return $tasks;
    }

    //为任务带上发布者和买手信息 还有图片
    public function attachPulisherAndErranderInfo($tasks){
        foreach ($tasks as $key => $value) {
            #发布者
            $value['user'] = $value->user()->first();
            #买手
            if(!empty($value->errand_id)){
                $value['errander'] = user_by_id($value->errand_id);
            }
           #图片
           $value['images'] = $value->images()->get();
           $value['format_time'] = $value->created_at->diffForHumans();
           $value['cha_remain_time'] = Carbon::parse($value->current_remain_time)->diffForHumans();
           if(strpos($value['cha_remain_time'], '距现在') !== false){
                $value['cha_remain_time'] = mb_substr($value['cha_remain_time'], 3, 100, 'utf-8');
                $value['cha_remain_time'] = '剩余'.$value['cha_remain_time'];
           }
           else{
                $value['cha_remain_time'] = '已超时';
                #已发布并且没人接
                if($value->status == '已发布' && empty($value->errand_id)){
                    ErrandTask::where('id',$value->id)->update(['status'=>'已取消']);
                    #如果这个任务已经支付了 就退还给账户余额
                    if($value->pay_status == '已支付'){
                        $user = user_by_id($value->user_id);
                        $pay_price = $value->pay_price;
                        $user->update(['user_money'=>round($user->user_money+$pay_price,2)]);
                        #加上记录
                        app('zcjy')->refundLogRepo()->create(['price'=>$pay_price,'reason'=>'系统超时自动退回','content'=>'无人接单系统自动退款'.$pay_price.'元','user_id'=>$user->id]);
                    }
                    unset($tasks[$key]);
                }
           }
           $value['cha_wish_time'] = Carbon::parse($value->current_wish_time)->diffForHumans();
           if(strpos($value['cha_wish_time'], '距现在') !== false){
                $value['cha_wish_time'] = mb_substr($value['cha_wish_time'], 3, 100, 'utf-8');
                $value['cha_wish_time'] = '剩余'.$value['cha_wish_time'];
           }
           else{
                $value['cha_wish_time'] = '已超时';
           }
        }
        return $tasks;
    }

    //处理超时的订单
    public function dealTimeoutTasks(){
        $tasks = ErrandTask::orderBy('created_at','asc')
                ->where('status','已发布')
                ->where('errand_id',0)
                ->get();
        if(count($tasks)){
            $this->attachPulisherAndErranderInfo($tasks);   
        }     
    }

    //已经完成的任务
    public function achieveTasks($tasks){
        return $tasks
        ->where('pay_status','已支付')
        ->where('status','已收货')
        ->where('errand_status','已收款')
        ->get();
    }

    public function timeoutTasks($tasks){
        return $tasks
        ->where('status','已取消')
        ->where('errand_id',0)
        ->get();
    }

    /**
    * [清除图片]
    * @param  [type] $project_id [description]
    * @return [type]             [description]
    */
   public function clearImages($errand_task_id){
        $id=ErrandImage::where('errand_task_id',$errand_task_id)->delete();
        return $id;
   }

   /**
    * [创建 更新图片]
    * @param  [type]  $images_arr     [description]
    * @param  [type]  $errand_task_id [description]
    * @param  boolean $update         [description]
    * @return [type]                  [description]
    */
   public function syncImages($images_arr,$errand_task_id,$update=false){

        #更新先重置
        if($update){
            $this->clearImages($errand_task_id);
        }
        if(count($images_arr)){
            #只添加的话直接添加
            foreach ($images_arr as $k => $v) {
                if(!empty($v)){
                    ErrandImage::create([
                        'url'=>$v,
                        'errand_task_id'=>$errand_task_id
                    ]);
                }
            }
        }

   }


}
