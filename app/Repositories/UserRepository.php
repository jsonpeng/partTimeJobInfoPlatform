<?php

namespace App\Repositories;

use App\User;
use App\Models\CouponRule;
use InfyOm\Generator\Common\BaseRepository;
use Carbon\Carbon;
use EasyWeChat\Factory;
use Storage;
use Image;
use Config;
use Log;
use App\Models\Caompany;
use App\Models\Project;

use Illuminate\Support\Facades\Cache;


class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'email_validated',
        'nickname',
        'password',
        'password-pay',
        'sex',
        'birthday',
        'head_image',
        'mobile',
        'qq',
        'openid',
        'unionid',
        'code',
        'share_qcode',
        'credits',
        'underling_number',
        'user_money',
        'frozen_money',
        'distribut_money',
        'consume_total',
        'last_login',
        'last_ip',
        'oauth',
        'province',
        'city',
        'district',
        'lock',
        'is_distribut',
        'leader1',
        'leader2',
        'leader3',
        'user_level',
        'member_buy_time',
        'member_end_time',
        'share_time',
        'distribute_time'

    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    //处理推荐关系
    public function processRecommendRelation($user_openid, $parent_id)
    {
        $user = User::where('openid', $user_openid)->first();
        //新用户注册才算
        if (empty($user)) {
            //推荐关系
            $parent = $this->findWithoutFail($parent_id);
            $grandParent = null;
            $grandGrandParent = null;
            if (!empty($parent) && $parent->leader1) {
                $grandParent = $this->findWithoutFail($parent->leader1);
                if (!empty($grandParent) && $grandParent->leader1) {
                    $grandGrandParent = $this->findWithoutFail($grandParent->leader1);
                }
            }
            $leader1 = 0;
            $leader2 = 0;
            $leader3 = 0;
            if (!empty($parent)) {
                $leader1 = $parent->id;
                $parent->update(['level1' => $parent->level1 + 1]);
            }
            if (!empty($grandParent)) {
                $leader2 = $grandParent->id;
                $grandParent->update(['level2' => $grandParent->level2 + 1]);
            }
            if (!empty($grandGrandParent)) {
                $leader3 = $grandGrandParent->id;
                $grandGrandParent->update(['level3' => $grandGrandParent->level3 + 1]);
            }
            $first_level = \App\Models\UserLevel::orderBy('amount', 'asc')->first();
            $user_level  = empty($first_level) ? 0 : $first_level->id;
            $user = User::create([
                'openid' => $user_openid,
                'leader1' => $leader1,
                'leader2' => $leader2,
                'leader3' => $leader3,
                'user_level' => $user_level,
            ]);

        }
    }

    /**
     * 新用户注册事件
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    function newUserCreated($user) {
        
        //积分赠送
        $this->creditsForNewUser($user);
        //优惠券赠送
        $this->conponForNewUser($user);
    }

    /**
     * 分享二维码
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function erweima($user)
    {
        if (empty($user->share_qcode)) {
            $app = app('wechat.official_account');
            $result = $app->qrcode->forever($user->id);
            $url = $app->qrcode->url($result['ticket']);
            $user->update(['share_qcode' => $url]);
        }

        $share_img = public_path().'/qrcodes/user_share'.$user->id.'.png';

        if(!Storage::exists($share_img)){
            $qcode = Image::make($user->share_qcode)->resize(260, 260);
            $qcode->save($share_img, 70);
        }
        
        $share_img ='/qrcodes/user_share'.$user->id.'.png';
        return $share_img;
    }

    /**
     * 分享二维码
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function shareErweima($user)
    {
        if (empty($user->share_qcode)) {
            // $app = app('wechat.official_account');
            // $result = $app->qrcode->forever($user->id);
            // $url = $app->qrcode->url($result['ticket']);
            // $user->update(['share_qcode' => $url]);
        }
        $erweima = public_path().$user->Erweima;
        $share_img=public_path().'/qrcodes/user_share_'.$user->id.'.png';

        $this->dealWithErweimaImg($user,$erweima,$share_img);

        $share_img_path='http://'.$_SERVER['HTTP_HOST'].'/qrcodes/user_share_'.$user->id.'.png';
        return $share_img_path;
    }


    public function dealWithErweimaImg($user,$erweima,$share_img){
            $base_image = getSettingValueByKeyCache('user_center_share_bg');
            if (empty($base_image)) {
                $base_image = public_path().'/images/about.jpg';
            }
            #用户头像
            $head_image=$user->head_image;
            #背景图
            $img = Image::make($base_image);
            #二维码
            $qcode = Image::make($erweima)->resize(260, 260);
            #用户头像
            $head_image=Image::make($head_image)->resize(150,150);
            #先插头像
            $img->insert($head_image, 'bottom-left', 163, 174);
            #再插二维码
            $img->insert($qcode, 'bottom-center', 163, 174);

            $img->save($share_img, 70);   
    }


    /**
     * 微信授权登录,根据微信用户的授权信息，创建或更新用户信息
     * @param [mixed] $socialUser [微信用户对象]
     */
    public function CreateUserFromWechatOauth($socialUser)
    {
        $user = null;
        $unionid = null;
        $socialUser=optional($socialUser);
        //用户是否公众平台用户
        if (array_key_exists('unionid', $socialUser)) {
            $unionid = $socialUser['unionid'];
            $user = User::where('unionid', $socialUser['unionid'])->first(); 
        }
        //不是，则是否是微信用户
        if (empty($user)) {
            $user = User::where('openid', $socialUser['openid'])->first();
            if(!empty($socialUser) && !empty($user)){
                $user->update([
                  'name' => $socialUser['nickname'],
                  'nickname' => $socialUser['nickname'],
                  'head_image' => $socialUser['headimgurl'],
                  'sex' => empty($socialUser['sex']) ? '男' : $socialUser['sex'],
                  'province' => $socialUser['province'],
                  'city' => $socialUser['city'],
                  'oauth' => '微信',
              ]);
            }

        }
        
        if (is_null($user)) {
          if(!empty($socialUser)){
            // 新建用户
            $user = User::create([
                'openid' => $socialUser['openid'],
                'unionid' => $unionid,
                'name' => $socialUser['nickname'],
                'nickname' => $socialUser['nickname'],
                'head_image' => $socialUser['headimgurl'],
                'sex' => empty($socialUser['sex']) ? '男' : $socialUser['sex'],
                'province' => $socialUser['province'],
                'city' => $socialUser['city'],
                'user_level' => 1,
                'oauth' => '微信',
            ]);
            
         }
            
        }else{
          if(!empty($socialUser) && !empty($user)){
            //$user=$this->varifyUserMemberGuoQi($socialUser);
            $user->update([
                'nickname' => $socialUser['nickname'],
                'head_image' => $socialUser['headimgurl'],
                'sex' => empty($socialUser['sex']) ? '男' : $socialUser['sex'],
                'province' => $socialUser['province'],
                'city' => $socialUser['city']
            ]);
          }
        }
        return $user;
    }


    
     /**
      * userlevel会员详情
      * @param  [object] $user [description]
      * @return [type]       [description]
      */
     public function userLevelInfo($user){
          $userlevel=$user->userlevel()->first();
          return $userlevel;
     }


    /**
     * 收藏的企业 收藏的项目
     * @param  传入[string,object,int,int][类型,user对象,跳过多少个,一次拿多少,项目类型(项目,需求)]
     * @return 返回[array]             [列表信息]
     */
    public function collections($type,$user,$skip = 0, $take = 18000)
    {
        return Cache::remember('zcjy_project_company_shoucang_list'.$type.'_'.$user.$skip.'_'.$take, Config::get('web.shrottimecache'), function() use ($type,$user,$skip,$take) {

              if($type=='project'){
                $item=$user->projects()->where('auth_status','通过')->where('status','正常')->with('images')->orderBy('created_at', 'desc')->skip($skip)->take($take)->get();
              }else{
                $item= $user->caompanys()->where('status',1)->with('images')->orderBy('created_at', 'desc')->skip($skip)->take($take)->get();
              }
              return $item;

      });
    }

    /**
     * 发布的企业 发布的项目
     * @param  传入[string,obj,int,int][类型,user对象,跳过多少个,一次拿多少,项目类型(项目,需求)]
     * @return 返回[array]             [列表信息]
     */
    public function releases($type,$user,$skip = 0,$take = 18000){

        return Cache::remember('zcjy_project_company_release_list'.$type.'_'.$user.$skip.'_'.$take, Config::get('web.shrottimecache'), function() use ($type,$user,$skip,$take) {

            if($type=='project'){
              //->where('type',$project)
              $item=$user->project()->with('images')->orderBy('created_at', 'desc')->skip($skip)->take($take)->get();
            }else{
              $item= $user->caompany()->with('images')->orderBy('created_at', 'desc')->skip($skip)->take($take)->get();
            }
            return $item;

      });
    }

    /**
     * [用户发布的企业]
     * @param  [type] $user_id [description]
     * @return [type]          [description]
     */
    public function releasesCompany($user_id){
        $user = $this->findWithoutFail($user_id);
        $caompany = null;
        if(!empty($user)){
          $caompany = $user->caompany()->first();
          $caompany = optional($caompany);
        } 
        return  $caompany;
    }
        

   /**
    * [收藏动作]
    * @param  [object] $user   [用户对象]
    * @param  [int] $id        [项目/公司id]
    * @param  [string] $type   [类型 project项目 company]
    * @param  [int] $status    [0 取消收藏 1收藏]
    * @return [json object]    [message 收藏成功 取消收藏成功]
    */  
    public function attachAction($user,$id,$type,$status){
      if(!empty($status)){
        #收藏
        if($type=='project'){
          #收藏项目
          $user->projects()->attach($id);

          #增加收藏数目
          $project=Project::find($id);
          $project->update(['collections'=>$project->collections+1]);

        }else{
          #收藏公司
          $user->caompanys()->attach($id);

          #增加收藏数目
          $caompanys=Caompany::find($id);
          $caompanys->update(['collect'=>$caompanys->collect+1]);

        }
        return ['code'=>0,'message'=>'收藏成功'];
      }else{
        #取消收藏
        if($type=='project'){
          #取消收藏项目
          $user->projects()->detach($id);
         
          #减少收藏数目
          $project=Project::find($id);
          $collections=($project->collections-1)<0?0:($project->collections-1);
          $project->update(['collections'=>$collections]);

       }else{
          #取消收藏公司
          $user->caompanys()->detach($id);

          #减少收藏数目
          $caompanys=Caompany::find($id);
          $collections=($caompanys->collect-1)<0?0:($caompanys->collect-1);
          $caompanys->update(['collect'=>$collections]);

       }
      return ['code'=>0,'message'=>'取消收藏成功'];
      }
    }
    
  /**
   * 通过昵称模糊查询用户列表 返回用户id数组
   * @param  [type] $nickname [description]
   * @return [type]           [description]
   */
    public function getUserArrByNickName($nickname){
           $user_id=\App\User::where('nickname','like','%'.$nickname.'%')->select('id')->get();
            $user_arr=[];
            if(count($user_id)){
              foreach ($user_id as $k => $v) {
                array_push($user_arr, $v->id);
              }
          }
            return $user_arr;
    }

    /**
     * 检查有没有过期
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function varifyUserMemberGuoQi($user, $weixin_user){
            //Log::info('进入了验证:');
            #更新下昵称和用户信息
            if(!empty($weixin_user)){
              if(!empty($user)){
                $user->update([
                    'nickname' => $weixin_user['nickname'],
                    'head_image' => $weixin_user['headimgurl'],
                    'sex' => empty($weixin_user['sex']) ? '男' : $weixin_user['sex'],
                    'province' => $weixin_user['province'],
                    'city' => $weixin_user['city']
                ]);
            }
           }
            #注册会员就不用管
            if(optional($user->userlevel()->first())->name=='注册会员'){

              return $user;
            }
            #过期时间
            $end_time=$user->member_end_time;

            #过期的天数 大于0等于0就过期不计算
            $guoqi=Carbon::parse($end_time)->diffInDays(Carbon::now(),false);
            Log::info('过期:');
            Log::info($guoqi);
            #过期就重置会员
            if($guoqi >=0){
              $user->update([
                'user_level'=>1,
                'member_buy_time'=>null,
                'member_end_time'=>null,
                'is_distribute' =>0,
                'share_time'=>0,
                'distribute_time'=>0,
                'leader1'=>0
              ]);
              Log::info('验证过期');
            }
            return $user;
    }


    /**
     * 验证推荐人
     */
    public function varifyLeader($input,$user){
      #先检查有没有分享id
      if(array_key_exists('share_id',$input)){
        if(!empty($input['share_id'])){
          $leader=$this->findWithoutFail($input['share_id']);
          #存在分享id则更新 非会员不加leader 自己不能分享自己 自己已经是会员不更新关系 注册会员才会有 && $leader->user_level != 1
          #&& $user->user_level == 1
          if(!empty($user) && !empty($leader)  && $user->id != $leader->id ){
            #更新推荐人id
            return $user->update([
              'leader1'=>$input['share_id'],
              'share_time'=>Carbon::now(),
              'user_level' => 1
            ]);

          }
        }
      }
    }


    /**
     * 验证推荐人 通过id
     */
    public function varifyLeaderById($user_openid,$parent_id){
       $user = User::where('openid', $user_openid)->first();
        //新用户注册才算
        if (empty($user)) {
            $leader = $this->findWithoutFail($parent_id);
            if(!empty($leader)){
                $user = User::create([
                     'openid'=>$user_openid,
                     'leader1'=>$parent_id,
                     'share_time'=>Carbon::now(),
                     'user_level' => 1
                ]);
            }
        }
    }

    /**
     * 其他会员购买成功判断 加不加佣金
     */
    public function varifyDistributMoney($user,$order){
       Log::info('验证佣金:');
       #只有初次购买的订单
       if($order->type== '普通' ){
        #如果是通过leader推荐的给予用户更新
            if(!empty($user->leader1)){
                #分拥人
                $leader=$this->findWithoutFail($user->leader1);
                #分拥比例
                $bili=($leader->userlevel()->first()->rate)/100;
                #提成金额
                $distribut_money=round($leader->distribut_money+$order->price*$bili,2);
                Log::info('提成金额:'.$distribut_money);
                if(!empty($leader)){
                   Log::info('提成金额:'.$distribut_money);
                     $leader->update([
                      'distribute_time'=>Carbon::now(),
                      'distribut_money'=>$distribut_money
                    ]);
                }
            }
        }
  }

    /**
     * 购买或者分享的用户列表
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function shareAndBuyUserList($user,$share,$take=20){
        $leader_id=$user->id;
        $user_list=[];
        #分享进来的用户
        if($share=='share'){
        $user_list=User::where('leader1',$leader_id)->orderBy('share_time','desc')->take($take)->get();
        #购买了的用户
        }else{
        $user_list=User::where('leader1',$leader_id)->where('is_distribute',1)->orderBy('distribute_time','desc')->take($take)->get();
      }
      return $user_list;
    }

    /**
     * 购买或者分享的用户带分页
     * @param  [type] $user [description]
     * @return [type]       [description]
     */
    public function shareAndBuyUserListPaginate($user,$share){
        $leader_id=$user->id;
        $user_list=[];
        #分享进来的用户
        if($share=='share'){
        $user_list=User::where('leader1',$leader_id)->orderBy('share_time','desc')->paginate(defaultPage());
     
        #购买了的用户
        }
        else{
        $user_list=User::where('leader1',$leader_id)->where('is_distribute',1)->orderBy('distribute_time','desc')->get();
      }
      return $user_list;
    }


    /**
     * 操作分拥金额
     */
    public function actionDistributMoney($user_id,$input){
      $user=$this->findWithoutFail($user_id);
      if(!empty($user)){
        if(array_key_exists('distribut_money',$input)){
              if(!empty($input['distribut_money'])){
                   $user->update([
                      'distribut_money'=>$leader->distribut_money+$order->price*$bili
                    ]);
                   return ['code'=>0,'message'=>'操作成功'];
                }else{
                  return ['code'=>1,'message'=>'请输入操作金额'];
                }
        }else{
          return ['code'=>1,'message'=>'请输入操作金额'];
        }
      }else{
        return ['code'=>1,'message'=>'没有该用户'];
      }
  }

  /**
   * 重置会员为注册会员
   */
  public function resetUserLevel($user){
    return $user->update([
            'user_level'=>1,
            'member_buy_time'=>null,
            'member_end_time'=>null,
            'is_distribute'=>0,
            'share_time'=>null,
            'distribute_time'=>null,
            'leader1'=>0
    ]);
  }

  /**
   * 恢复/设置会员级别
   */
  public function updateUserLevel($user,$user_level_id){
        return $user->update([
            'user_level'=>$user_level_id,
            'member_buy_time'=>Carbon::now(),
            'member_end_time'=>Carbon::now()->addYears(1)
    ]);
  }




}
