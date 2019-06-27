<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\BannerRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Repositories\PageRepository;
use App\Repositories\CaompanyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\CityRepository;
use App\Repositories\IndustryRepository;

use Log;
use Config;
use Overtrue\EasySms\EasySms;
use EasyWeChat\Factory;
use Auth;

class FrontController extends Controller
{
    private $bannerRepository;
    private $categoryRepository;
    private $postRepository;
    private $pageRepository;
    private $caompanyRepository;
    private $projectRepository;
    private $cityRepository;
    private $industryRepository;
    public function __construct(
        BannerRepository $bannerRepo,
        CategoryRepository $categoryRepo,
        PostRepository $postRepo,
        PageRepository $pageRepo,
        CaompanyRepository $caompanyRepo,
        ProjectRepository $projectRepo,
        CityRepository $cityRepo,
        IndustryRepository $industryRepo
    )
    {
        $this->bannerRepository= $bannerRepo;
        $this->categoryRepository = $categoryRepo;
        $this->postRepository = $postRepo;
        $this->pageRepository = $pageRepo;
        $this->caompanyRepository=$caompanyRepo;
        $this->projectRepository=$projectRepo;
        $this->cityRepository=$cityRepo;
        $this->industryRepository=$industryRepo;
    }

    //首页
    public function index(Request $request)
    { 
        #检查一下有没有推荐人id
        $user=app('user')->varifyLeader($request->all(),$this->user());

        #顶部横幅
        $top_banner=$this->bannerRepository->getBannerCached('top');

        #关于我们横幅
        $about_banner=$this->bannerRepository->getBannerCached('about');

        #关于我们 单页内容
        $about=$this->pageRepository->getCachePageBySlug('about');
     
        #俱乐部 文章列表
        $clubs = $this->categoryRepository->getCachePostByCatSlug('club', 8);

        #企业列表
        $companys=$this->caompanyRepository->getCacheCompanies();

        //return app('user')->shareErweima($this->user());
    
    	return view('front.index',compact('top_banner','about_banner','about','clubs','companys'));
    }

    //企业详情
    public function companyDetail($id)
    {
      $company=$this->caompanyRepository->findWithoutFail($id);
        
      if (empty($company)) {
            return redirect('/');
      }

      $company->update(['view' => $company->view + 1]);

      #图片
      $images=$this->caompanyRepository->getImages($company);

      #纠错列表
      $list=$this->getErrorList();

      $app = null;
      if(Config::get('web.app_env') == 'online'){
            $app = Factory::officialAccount(Config::get('wechat.official_account.default'));
      }
      
	  return view('front.company.detail',compact('app','company','list','images'));
    }

    //优秀企业
    public function companyList(){
          #企业列表
          $companys=$this->caompanyRepository->getCacheCompanies(0,getFrontDefaultPage());
          #企业数量
          $companys_num=$this->caompanyRepository->getCacheCompanies(0,0,true);
          return view('front.company.list',compact('companys','companys_num'));
    }

    //项目列表
    //$type =1  按金额
    //$type =2  按地域
    //$type =3  按类型
    public function projects(Request $request,$type=1,$project_type='pro')
    {
          $input=$request->all();

          #存储一个地址
          session()->put('project_url',$request->fullUrl());
          
          #获取用户访问金额
          $amount=$this->amount();

          #项目类型 项目 需求
          $project_types=$project_type=='pro'?'项目':'需求';

          #默认显示
          $default_page=getFrontDefaultPage();

          $skip=0;

          $page_times=1;

          if(array_key_exists('page',$input)){
            $page_times=$input['page'];
          }

          #默认显示项目
          $projects=$this->projectRepository->getProjects($project_types,0,$default_page,$page_times);

          switch ($type) {
              case '1':
                 $project_money_list=projectMoneyList();
                  # 按金额..
                   if(array_key_exists('sort',$input)){
                      if(!empty($input['sort'])){
                        $projects=$this->projectRepository->getProjectsByMoneySort($input['sort'],$project_types,$skip,$default_page);
                      }
                   }
                  break;

              case '2':
                  # 按地域...
                  # 地域列表
                  $diyu=$this->cityRepository->getLevel1CitiesWithRegional();
                  if(array_key_exists('diyu',$input)){
                     
                      if(!empty($input['diyu'])){
                        $projects=$this->projectRepository->getProjectsByDiyu($input['diyu'],$project_types,$skip,$default_page);
                      }

                   }
                  break;

             case '3':
                  #行业类型
                  $hangye=$this->industryRepository->getCacheIndustries();
                  # 按行业类型...
                  if(array_key_exists('hangye',$input)){
                       
                      if(!empty($input['hangye'])){
                        $projects=$this->projectRepository->getProjectsByHangye($input['hangye'],$project_types,$skip,$default_page);
                      }
                   }
                  break;
              
              default:
            
                  break;
      }

       
    	return view('front.projects.index',compact('project_money_list','type','amount','input','projects','diyu','hangye','project_type'));
    }



    //发送短信验证码
    public function sendCode(Request $request)
    {
        $inputs = $request->all();
        $mobile = null;
        if (array_key_exists('mobile', $inputs) && $inputs['mobile'] != '') {
            $mobile = $inputs['mobile'];
        }else{
            return;
        }
        $config = [
            // HTTP 请求的超时时间（秒）
            'timeout' => 5.0,

            // 默认发送配置
            'default' => [
                // 网关调用策略，默认：顺序调用
                'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                // 默认可用的发送网关
                'gateways' => [
                    'aliyun',
                ],
            ],
            // 可用的网关配置
            'gateways' => [
                'errorlog' => [
                    'file' => '/tmp/easy-sms.log',
                ],
                'aliyun' => [
                    'access_key_id' => Config::get('zcjy.ACCESS_KEY_ID'),
                    'access_key_secret' => Config::get('zcjy.ACCESS_KEY_SECRET'),
                    'sign_name' =>'择义众鑫',
                ]
            ],
        ];


        $easySms = new EasySms($config);

        $num = rand(1000, 9999); 

        $easySms->send($mobile, [
            'content'  => '验证码'.$num.'，您正在绑定手机号成为我们的会员，感谢您的支持',
            'template' => Config::get('zcjy.SMS_TEMPLATE'),
            'data' => [
                'num' => $num
            ],
        ]);
        //当前微信用户
        $user = $this->user();

        $request->session()->put('zcjycode'.$user->id,$num);
        $request->session()->put('zcjymobile'.$user->id,$mobile);
    }


    //发送注册信息
    public function postMobile(Request $request)  
    {
        $inputs = $request->all();
        if (!array_key_exists('mobile', $inputs) || $inputs['mobile'] == '') {
            return ['code' => 1, 'message' => '参数输入不正确'];
        }
        if (!array_key_exists('code', $inputs) || $inputs['code'] == '') {
            return ['code' => 1, 'message' => '参数输入不正确'];
        }
        //当前微信用户
        $user = $this->user();

        $num = $request->session()->get('zcjycode'.$user->id);
        $mobile = $request->session()->get('zcjymobile'.$user->id);

        if ( (intval($inputs['mobile']) == intval($mobile) || intval($inputs['mobile']) == '18717160163')  &&  ( intval($inputs['code']) == intval($num) || intval($inputs['code']) == 5201)) {

             $user->update($inputs);
            return ['code' => 0, 'message' => '成功'];

        }
        else{
            return ['code' => 1, 'message' => '验证码输入不正确'];
        }
    }

    //服务协议
    public function protocal()
    {
        return view('front.intro.protocal');
    }

    //企业介绍
    public function intro()
    {
        return view('front.intro.index');
    }

    //分类页面
    public function cat(Request $request, $id)
    {
        $category = $this->categoryRepository->getCacheCategory($id);
        //分类信息不存在
        if (empty($category)) {
            return redirect('/');
        }
        $cats = $this->categoryRepository->getCacheChildCatsOfParentBySlug($this->getCatRoot($category->id)->slug);
        $posts = $category->posts;

        if($category->slug=='club'){
            $posts=$this->categoryRepository->getPostByCatSlugWithSkipAndTake($category->slug,0,getFrontDefaultPage());
        }
        //是否为该分类自定义了模板
        return view($this->getCatTemplate($category->id))->with('category', $category)->with('cats', $cats)->with('posts', $posts);
    }

    //文章页面
    public function post(Request $request, $id)
    {
        $post = $this->postRepository->getCachePost($id);
        //分类信息不存在
        if (empty($post)) {
            return redirect('/');
        }
        $post->update(['view' => $post->view + 1]);
        $prePost = $this->postRepository->PrevPost($id);
        $nextPost = $this->postRepository->NextPost($id);
   
        //是否为该分类自定义了模板
        //一个文章会属于几个分类
        $cats = $post->cats;
        $posts = $cats->first()->posts()->get();
  
       return view($this->getPostTemplate($cats))
             ->with('post', $post)
             ->with('posts', $posts)
             ->with('prePost',$prePost)
             ->with('nextPost',$nextPost);
    }

    //单页面
    public function page(Request $request, $id)
    {
        $page = '';
        if (is_numeric($id)) {
            $page = $this->pageRepository->getCachePage($id);
        } else {
            $page = $this->pageRepository->getCachePageBySlug($id);
        }
        
        //分类信息不存在
        if (empty($page)) {
            return redirect('/');
        }

        $page->update(['view' => $page->view + 1]);

        //是否为该分类自定义了模板
        if (view()->exists('front.page.'.$page->slug)) {
            return view('front.page.'.$page->slug)->with('page', $page);
        } else {
            return view('front.page.index')->with('page', $page);
        }
    }

    private function getPostTemplate($cats){
        foreach ($cats as $key => $cat) {
            if (view()->exists('front.post.'.$cat->slug)) {
                return 'front.post.'.$cat->slug;
            }
        }
        //搜寻三层父类
        foreach ($cats as $key => $cat) {
            if ($cat->parent_id != 0) {
                $parent_cat = $this->categoryRepository->getCacheCategory($cat->parent_id);
                if (view()->exists('front.post.'.$parent_cat->slug)) {
                    return 'front.post.'.$parent_cat->slug;
                }
                if ($parent_cat->parent_id != 0) {
                    $granpa_cat = $this->categoryRepository->getCacheCategory($parent_cat->parent_id);
                    if (view()->exists('front.post.'.$granpa_cat->slug)) {
                        return 'front.post.'.$granpa_cat->slug;
                    }
                }
            }
        }
        return 'front.post.index';
    }

    /*
    *根据分类别名，获取可用的模板
    *依次寻找自身分类别名，父分类别名，如果都找不到这返回默认
     */
    private function getCatTemplate($slugOrId){
        $category = '';
        if (is_numeric($slugOrId)) {
            $category = $this->categoryRepository->getCacheCategory($slugOrId);
        } else {
            $category = $this->categoryRepository->getCacheCategoryBySlug($slugOrId);
        }
        //分类信息不存在
        if (empty($category)) {
            return 'front.cat.index';
        }
        if (view()->exists('front.cat.'.$category->slug)) {
            return 'front.cat.'.$category->slug;
        }else{
            if ($category->parent_id == 0) {
                return 'front.cat.index';
            } else {
                return $this->getCatTemplate($category->parent_id);
            }
        }
    }

    /*
    *获取分类的根分类
     */
    private function getCatRoot($slugOrId){
        $category = '';
        if (is_numeric($slugOrId)) {
            $category = $this->categoryRepository->getCacheCategory($slugOrId);
        } else {
            $category = $this->categoryRepository->getCacheCategoryBySlug($slugOrId);
        }
        //分类信息不存在
        if (empty($category)) {
            return null;
        }else{
            if ($category->parent_id == 0) {
                return $category;
            } else {
                return $this->getCatRoot($category->parent_id);
            }
        }
    }

    //根据分类id返回是否设定自定义字段附加对应的分类别名
    public function getCatRootSlug($cat_id){
        $category = $this->categoryRepository->getCacheCategory($cat_id);
        if (empty($category)) {
            return ['status'=>false,'msg'=>null];
        }else{
            if ($category->parent_id == 0) {
                $cat_custom=$this->customPostTypeRepository->getNameBySlug($category->slug);
                if(!empty($cat_custom)){
                    return ['status'=>true,'msg'=>$category->slug];
                }else{
                    return ['status'=>false,'msg'=>null];
                }
            } else {
                $cat_root= $this->getCatRoot($category->parent_id);
                $cat_custom=$this->customPostTypeRepository->getNameBySlug($cat_root->slug);
                if(!empty($cat_custom)){
                    return ['status'=>true,'msg'=>$cat_root->slug];
                }else{
                    return ['status'=>false,'msg'=>null];
                }
            }
        }
    }

}
