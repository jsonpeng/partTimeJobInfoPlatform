<?php

namespace App\Repositories;

use App\Models\Setting;
use InfyOm\Generator\Common\BaseRepository;
use App\Repositories\CaompanyRepository;
use App\Repositories\CityRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\IndustryRepository;
use App\Repositories\ProjectSignRepository;
use App\Repositories\TaskTemRepository;
use App\Repositories\ErrandTaskRepository;
use App\Repositories\SchoolRepository;
use App\Repositories\FeedBackRepository;
use App\Repositories\ProjectErrorRepository;
use App\Repositories\CreaditsLogRepository;
use App\Repositories\ErrandErrorRepository;
use App\Repositories\WithDrawalLogRepository;
use App\Repositories\RefundLogRepository;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\User;
use Image;
use EasyWeChat\Factory;
use Carbon\Carbon;

class ZcjyRepository 
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];
    private $projectSignRepository;
    private $projectRepository;
    private $companyRepository;
    private $cityRepository;
    private $industryRepository;
    private $taskTemRepository;
    private $errandTaskRepository;
    private $schoolRepository;
    private $feedBackRepository;
    private $projectErrorRepository;
    private $creaditsLogRepository;
    private $errandErrorRepository;
    private $withDrawalLogRepository;
    private $refundLogRepository;
    public function __construct(
        ProjectSignRepository $projectSignRepo,
        ProjectRepository $projectRepo,
        CaompanyRepository $companyRepo,
        CityRepository $cityRepo,
        IndustryRepository $industryRepo,
        TaskTemRepository $taskTemRepo,
        ErrandTaskRepository $errandTaskRepo,
        SchoolRepository $schoolRepo,
        FeedBackRepository $feedBackRepo,
        ProjectErrorRepository $projectErrorRepo,
        CreaditsLogRepository $creaditsLogRepo,
        ErrandErrorRepository $errandErrorRepo,
        WithDrawalLogRepository $withDrawalLogRepo,
        RefundLogRepository $refundLogRepo
    )
    {
        $this->projectSignRepository = $projectSignRepo;
        $this->industryRepository = $industryRepo;
        $this->projectRepository = $projectRepo;
        $this->companyRepository = $companyRepo;
        $this->cityRepository = $cityRepo;
        $this->taskTemRepository = $taskTemRepo;
        $this->errandTaskRepository = $errandTaskRepo;
        $this->schoolRepository = $schoolRepo;
        $this->feedBackRepository = $feedBackRepo;
        $this->projectErrorRepository = $projectErrorRepo;
        $this->creaditsLogRepository = $creaditsLogRepo;
        $this->errandErrorRepository = $errandErrorRepo;
        $this->withDrawalLogRepository = $withDrawalLogRepo;
        $this->refundLogRepository = $refundLogRepo;
    }

    public function refundLogRepo(){
        return $this->refundLogRepository;
    }

    public function withDrawalLogRepo(){
        return $this->withDrawalLogRepository;
    }
    
    public function errandErrorRepo(){
        return $this->errandErrorRepository;
    }

    public function creaditsLogRepo(){
        return $this->creaditsLogRepository;
    }

    public function projectErrorRepo(){
        return $this->projectErrorRepository;
    }

    public function feedBackRepo(){
        return $this->feedBackRepository;
    }

    public function schoolRepo(){
        return $this->schoolRepository;
    }

    public function errandTaskRepo(){
        return $this->errandTaskRepository;
    }

    public function taskTemRepo(){
        return $this->taskTemRepository;
    }

    public function projectSignRepo(){
        return $this->projectSignRepository;
    }

    public function industryRepo(){
        return $this->industryRepository;
    }

    public function projectRepo(){
        return $this->projectRepository;
    }

    public function companyRepo(){
        return $this->companyRepository;
    }

    public function cityRepo(){
        return $this->cityRepository;
    }

    /**
     * [默认直接通过数组的值 否则通过数组的键]
     * @param  [type] $input      [description]
     * @param  array  $attr       [description]
     * @param  string $valueOrKey [description]
     * @return [type]             [description]
     */
    public function varifyInputParam($input,$attr=[],$valueOrKey='value'){
        $status = false;
        #第一种带键值但值为空的情况
        foreach ($input as $key => $val) {
            if(array_key_exists($key,$input)){
                if(empty($input[$key]) && $input[$key]!=0){
                    $status = '参数不完整';
                }
            }
        }
        #第二种是针对提交的指定键值
        if(count($attr)){
            foreach ($attr as $key => $val) {
                if($valueOrKey == 'value'){
                    if(!array_key_exists($val,$input) || array_key_exists($val,$input) && empty($input[$val]) && $input[$val] != 0){
                        $status = '参数不完整';
                    }
                }
                else{
                     if(!array_key_exists($key,$input) || array_key_exists($key,$input) && empty($input[$key]) && $input[$key] != 0){
                        $status = '参数不完整';
                    }
                }
            }
        }

        return $status;
    }

    /**
     * [接口请求用户验证]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function zcjyApiUserVarify($input){
         $status = false;
         if(array_key_exists('token',$input) && !empty($input['token'])){
        
            $token = optional(explode('_', zcjy_base64_de($input['token'])));
            //Log::info($token);
            $user = User::find($token[0]);
            if(empty($user)){
                $status = 'token信息验证失败';
            }
            //return $status;
            if($user->id == $token[0]  && strtotime($user->created_at) == $token[1] && $user->openid == $token[2] ){
                session(['zcjy_api_user_'.$token[0] => $user]);
            }
            else{
                $status = 'token信息验证失败';
            }

        }
        else{
            $status = 'token信息验证失败';
        }
        return $status;
    }

    /**
     * [用户信誉积分检查]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function creditsLimitVarify($input){
        $status = false;
        $user = zcjy_api_user($input);
        if(empty($user)){
            return 'token信息验证失败!';
        }
        $min_credits = empty(getSettingValueByKey('user_min_credits')) ? 0 :getSettingValueByKey('user_min_credits');
        if($user->credits < $min_credits){
            $status = '您的信誉积分已低于系统要求的最低积分'.$min_credits.',请保持良好信用记录后可继续使用系统!';
        }
        return $status;
    }

    /**
     * [企业用户判断]
     * @param  [type] $input [description]
     * @return [type]        [description]
     */
    public function companyPermisVarify($input){
        $status = false;
        $user = zcjy_api_user($input);
        if(empty($user)){
            return 'token信息验证失败!';
        }
        $company = $user->caompany()->first();
        if($user->type != '企业' || empty($company)){
            $status = '不是企业用户或者没有企业公司!';
        }
        return $status;
    }

    //用户提现金额到用户钱包余额 给
    public function companyGiveUserMoney($log,$user,$reason='提现'){
           //Log::info(Config::get('wechat.payment.default'));
           $app = Factory::payment(Config::get('wechat.payment.default'));
           $result = $app->transfer->toBalance([
                'partner_trade_no' => $log->id.time(), // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
                'openid' => $user->openid,
                'check_name' => 'NO_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
                're_user_name' => $user->nickname, // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
                'amount' => $log->price*100, // 企业付款金额，单位为分最少1元
                'desc' => $reason.$log->price.'元到钱包余额', // 企业付款操作说明信息。必填
                'spbill_create_ip' => env('ip','118.190.201.81')//服务器ip地址
          ]);
         // $result = json_encode($result);
          Log::info($result);
          $result  = optional($result);
          if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS'){
             $log->update(['status'=>'已完成']);
          }
          else{

          }
          return $result;
    }

    //自动处理今天未提现的提现记录
    public function autoDealWithDrawLog(){
            $logs = $this->withDrawalLogRepo()->model()::where('status','发起')
            ->whereBetween('created_at',[Carbon::today(),Carbon::tomorrow()])
            ->get();
            if(count($logs)){
                foreach ($logs as $key => $value) {
                   $this->companyGiveUserMoney($value,$value->user()->first());
                }
            }
    }
    

      /**
     * [图片上传]
     * @param  [type] $file     [description]
     * @param  string $api_type [description]
     * @return [type]           [description]
     */
    public function uploadImages($file,$api_type='web',$user=null){
        $allowed_extensions = ["png", "jpg", "gif","jpeg"];
        
        if(empty($file)){
            return zcjy_api_user('文件不能为空',1,$api_type);
        }

        if(!empty($file)) {
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
                return zcjy_callback_data('图片格式不正确',1,$api_type);
            }
        }

        #图片文件夹
        $destinationPath = empty($user) ? "uploads/admin/" : "uploads/user/".$user->id.'/';

        if (!file_exists($destinationPath)){
            mkdir($destinationPath,0777,true);
        }
       
        $extension = $file->getClientOriginalExtension();
        $fileName = str_random(10).'.'.$extension;
        $file->move($destinationPath, $fileName);

        $image_path=public_path().'/'.$destinationPath.$fileName;
        
        $img = Image::make($image_path);
        $img->resize(640, 640);
        $img->save($image_path,70);

        $host='http://'.$_SERVER["HTTP_HOST"];

        if(env('online_version') == 'https'){
             $host='https://'.$_SERVER["HTTP_HOST"];
        }

        #图片路径
        $path=$host.'/'.$destinationPath.$fileName;

        return zcjy_callback_data([
                'src'=>$path,
                'current_time' => Carbon::now()
            ],0,$api_type);
    }

    //自动赠送积分
    public function autoGiveCredits(){
        $users = User::all();
        foreach ($users as $key => $user) {
            #用户没有负面记录
            if(count(app('zcjy')->creaditsLogRepo()->userNegativeLog($user->id)) == 0){
                #这个月没有获得过
                if(count(app('zcjy')->creaditsLogRepo()->userActiveLog($user->id)) == 0){
                    #给用户送积分
                    app('zcjy')->creaditsLogRepo()->giveUserLog($user);
                }
            }
        }
    }

    //平台托管所有的账户余额
    public function platformPrice(){
        $users = User::all();
        $price = 0;
        foreach ($users as $key => $value) {
           $price += $value->user_money;
        }
        $price = round($price,2);
        return $price;
    } 

    public function clearRepUsers(){
         $users = User::all();
         $users_num = count($users);
         $i = -1;
         $k = 0;
         foreach ($users as $key => $value) {
                $i++;
                if($k < $users_num){
                    $k++;
                }
                if(isset($users[$i]) && isset($users[$k])){
                    if($users[$i]['openid'] == $users[$k]['openid']){
                        User::where('id',$users[$k]['id'])->delete();
                    }
                }
         }
    }

    /**
     * [统计校购]
     * @param  [type] $start_time [description]
     * @param  [type] $end_time   [description]
     * @return [type]             [description]
     */
    public function staticsErrand($start_time,$end_time){
        #存在两个起止时间
        if(!empty($start_time) && !empty($end_time)){
            $tasks =  $this->errandTaskRepository->model()::whereBetween('created_at',[$start_time,$end_time]);
            $with_draw_logs = $this->withDrawalLogRepo()->model()::where('status','已完成')
            ->whereBetween('created_at',[$start_time,$end_time]);
        }

        #只有开始时间
        if(!empty($start_time) && empty($end_time)){
             $tasks =  $this->errandTaskRepository->model()::where('created_at','>=',$start_time);
             $with_draw_logs = $this->withDrawalLogRepo()->model()::where('status','已完成')
            ->where('created_at','>=',$start_time);
        }

        #只有结束时间
        if(!empty($end_time) && empty($start_time)){
            $tasks =  $this->errandTaskRepository->model()::where('created_at','<=',$start_time);
            $with_draw_logs = $this->withDrawalLogRepo()->model()::where('status','已完成')
            ->where('created_at','<=',$start_time);
        }

        #两个都是空

        #校购任务总数量
        $all_tasks_num = $tasks->count();

        #校购完成任务
        $achieve_tasks = $this->errandTaskRepository->achieveTasks($tasks);

        #校购完成任务数量
        $achieve_tasks_num = count($this->errandTaskRepository->achieveTasks($tasks));

        #校购任务成功支付金额
        $pay_price = $achieve_tasks->sum('pay_price');

        #校购超时失败任务数量
        $timeout_tasks_num = count($this->errandTaskRepository->timeoutTasks($tasks));

        #平台托管金额
        $platform_price = $this->platformPrice();

        #用户累计提现
        $achieve_withdraw_price = $with_draw_logs->get()->sum('price');

        #平台分润
        $platform_profits = $achieve_tasks->sum('platform_price');

        #买手累计获取
        $errander_price = $achieve_tasks->sum('errander_get_price');

        return (object)[
            'all_tasks_num' => $all_tasks_num ,
            'achieve_tasks_num' => $achieve_tasks_num,
            'pay_price' => $pay_price,
            'timeout_tasks_num' => $timeout_tasks_num,
            'platform_price' => $platform_price,
            'achieve_withdraw_price' => $achieve_withdraw_price,
            'platform_profits'=> $platform_profits,
            'errander_price' => $errander_price
        ]; 
    }

}
