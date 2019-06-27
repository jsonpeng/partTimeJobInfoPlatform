<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProjectRepository;
use App\Repositories\CityRepository;
use App\Repositories\IndustryRepository;

use Config;
use EasyWeChat\Factory;

class ProjectController extends Controller
{


    public function __construct(
        ProjectRepository $projectRepo,
        CityRepository $cityRepo,
        IndustryRepository $industryRepo
    )
    {
        $this->projectRepository=$projectRepo;
        $this->cityRepository=$cityRepo;
        $this->industryRepository=$industryRepo;
    }

	//新建项目
    public function create(Request $request)
    {   

          #input
          $input=$request->all();
          #第一级省份
          $cities_level1=$this->cityRepository->getLevelNumCities(1);
          
          #行业类型
          $hangye=$this->industryRepository->getAllIndustries();

    	  return view('front.projects.create',compact('input','cities_level1','hangye'));
    }

    //修改项目
    public function edit($id)
    {   

        #对应的项目
        $project=$this->projectRepository->findWithoutFail($id);

        if (empty($project)) {

            return redirect('/');

        }

        #第一级省份
        $cities_level1=$this->cityRepository->getLevelNumCities(1);

        #第二级省份
        $cities_level2=$this->cityRepository->getCommonPidCitiesById($project->city);

        #第三级省份
        $cities_level3=$this->cityRepository->getCommonPidCitiesById($project->district);

        #行业类型
        $hangye=$this->industryRepository->getAllIndustries();

        #项目的图片
        $images=$this->projectRepository->getImages($project);

    	return view('front.projects.edit',compact('cities_level1','cities_level2','cities_level3','project','hangye','images'));
    }

    //展示项目
    public function show($id)
    {
      
        #对应的项目
        $project=$this->projectRepository->findWithoutFail($id);

        if (empty($project)) {

            if(!empty(session('project_url'))){
                return redirect(session('project_url'));
            }

            return redirect('/');

        }

        #用户信息
        $user=$project->user()->first();

        #项目的图片
        $images=$this->projectRepository->getImages($project);

    
        #是否超过限度
        $limit= limit($this->amount(),$project->money);

        #是否需要升级
        $update=$this->userlevel();
        

        $project->update(['view' => $project->view + 1]);

        $app = null;
        if(Config::get('web.app_env') == 'online'){
            $app = Factory::officialAccount(Config::get('wechat.official_account.default'));
        }
        //dd($images[0]['url']);

    	return view('front.projects.show',compact('app','user','project','limit','update','images'));
    }


    public function test(){
        return view('front.projects.test');
    }

}
