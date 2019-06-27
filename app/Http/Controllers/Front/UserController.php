<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;

use Config;

class UserController extends Controller
{

    private $userRepository;
    private $userLevelRepository;
    public function __construct(UserRepository $userRepo){
        $this->userRepository=$userRepo;
    }

	//用户首页
    public function index()
    {
        #个人信息
        $user=$this->user();
        #会员等级
        $userlevel=$this->userRepository->userLevelInfo($user);
        #发布的项目信息
        $projects=$this->userRepository->releases('project',$user);
        
    	return view('front.usercenter.index',compact('user','userlevel','projects'));

    }

    //购买会员
    public function member()
    {
        #个人信息
        $user=$this->user();

        #先判断用户绑定过手机号没有
        if(empty($user->mobile)){
            return redirect('/auth/mobile');
        }

        #先判断用户购买过会员没有
        if($this->userlevel()){
            #已经买过会员就跳转到升级会员
            return redirect('/usercenter/memberLevelup');
        }
        #会员列表
        $members=$this->userLevelRepository->getMemberList();
      
    	return view('front.member.buy',compact('members','user'));
    }

    //升级会员
    public function memberLevelup()
    {
        #当前的会员信息
        $user=$this->user();

        #当前的会员等级
        $member_user=$user->userlevel()->first();

        #大于当前访问金额的会员列表
        $members=$this->userLevelRepository->getUpdateListDetail($member_user->amount,$user);

    	return view('front.member.update',compact('user','member_user','members'));
    }

    //我的企业
    public function company()
    {
        #发布的企业信息
        $companys=$this->userRepository->releases('caompanys',$this->user());

        #企业数量
        $companys_num=count($companys);

        if(!$companys_num){
            return redirect('/usercenter/company/create');
        }

    	return view('front.usercenter.company',compact('companys','companys_num'));
    }

    public function companyCreate(){
        #第一级省份
        $cities_level1=app('city')->getLevelNumCities(1);
        // dd($cities_level1);
        return view('front.usercenter.company_add',compact('cities_level1'));
    }

    //推广二维码
    public function erweima()
    {
        $user=$this->user();

        #分享二维码链接
        $erweima=null;
        if(Config::get('web.app_env') == 'online'){
            $erweima=app('user')->erweima($user);
        }

    	return view('front.usercenter.erweima',compact('user','erweima'));
    }

    public function promote(Request $request,$id)
    {
        $user=$this->userRepository->findWithoutFail($id);

        #分享二维码链接
        $erweima=null;
        if(Config::get('web.app_env') == 'online'){
            $erweima=app('user')->erweima($user);
        }

        return view('front.usercenter.erweima',compact('user','erweima'));
    }

    //分享记录
    public function share(Request $request,$type='share'){
        #个人信息
        $user=$this->user();

        #分享人 购买人
        $users=$this->userRepository->shareAndBuyUserList($user,$type);
     
        return view('front.usercenter.share.index',compact('type','users','user'));

    }

    //我的收藏
    public function collections($type='caompanys')
    {
        #$type=caopanys 企业 project项目
        //$project=$type=='caompanys'?'':'';
        #收藏的企业
        $list=$this->userRepository->collections($type,$this->user());

        #企业数量
        $num=count($list);
        
    	return view('front.usercenter.collection',compact('type','list','num'));
    }

    //联系管理员页面
    public function manager()
    {
    	return view('front.intro.contact_manager');
    }
}
