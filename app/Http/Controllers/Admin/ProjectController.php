<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Repositories\ProjectRepository;
use App\Repositories\CityRepository;
use App\Repositories\IndustryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ProjectController extends AppBaseController
{
    /** @var  ProjectRepository */
    private $projectRepository;
    private $cityRepository;
    private $industryRepository;
    public function __construct(ProjectRepository $projectRepo,CityRepository $cityRepo,IndustryRepository $industryRepo)
    {
        $this->projectRepository = $projectRepo;
        $this->cityRepository = $cityRepo;
        $this->industryRepository=$industryRepo;
    }

    /**
     * Display a listing of the Project.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->projectRepository->pushCriteria(new RequestCriteria($request));
        $input=$request->all();
        $input =array_filter( $input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );
        $tools=$this->varifyTools($input);
       // $projects=$this->projectRepository->defaultSearchState();
        $projects=$this->defaultSearchState($this->projectRepository->model());
        // dd($projects->get());
        $industries=$this->industryRepository->getAllIndustries();
        $level01=0;
        $level02=0;
        $diyu=$this->cityRepository->getLevel1CitiesWithRegional();
        $cities=[0=>'请选择地区'];

        if(array_key_exists('name',$input)){
            $projects = $projects->where('name', 'like','%'.$input['name'].'%');
        }
        if(array_key_exists('industries',$input)){
            $industry=  $this->industryRepository->findWithoutFail($input['industries']);
            $projects = $industry->projects();
        }
        if(array_key_exists('price_start',$input)){
            $projects = $projects->where('money', '>=', $input['price_start']);
        }
        if(array_key_exists('price_end',$input)){
            $projects = $projects->where('money', '<=', $input['price_end']);
        }
        if(array_key_exists('diyu',$input)){
            if(!empty($input['diyu'])){
                $province_arr=$this->cityRepository->getRegionalCitiesArrOnlyId($input['diyu']);
                $cities=$this->cityRepository->getCitiesArrSelectByArr($province_arr);
                $projects = $projects->whereIn('province', $province_arr);
            }
        }
        //选择城市
        if(array_key_exists('cities',$input)){
            $level02=$input['cities'];
            if(!empty($input['cities'])){
                $projects = $projects->where('province', $input['cities']);
            }
        }
        if(array_key_exists('status',$input)){
            $projects = $projects->where('status', $input['status']);
        }
        if(array_key_exists('pay_status',$input)){
            $projects = $projects->where('pay_status', $input['pay_status']);
        }

        $projects = $this->descAndPaginateToShow($projects);
       
        return view('admin.projects.index')
             ->with('industries',$industries)
             ->with('tools',$tools)
             ->with('diyu',$diyu)
             ->with('cities',$cities)
             ->with('level01',$level01)
             ->with('level02',$level02)
             ->with('projects', $projects)
             ->with('input',$input);
    }

    /**
     * Show the form for creating a new Project.
     *
     * @return Response
     */
    public function create()
    {
        $cities_level1=$this->cityRepository->getLevelNumCities(1);
        $industries=$this->industryRepository->getAllIndustries();
        $selectedIndustries=[];
        $model_required = modelRequiredParam(app('zcjy')->projectRepo()->model()); 
        return view('admin.projects.create')
                ->with('model_required',$model_required)
                ->with('project',null)
                ->with('images',[])
                ->with('cities_level1',$cities_level1)
                ->with('cities_level2',[])
                ->with('cities_level3',[])
                ->with('industries',$industries)
                ->with('selectedIndustries',$selectedIndustries);
    }

    /**
     * Store a newly created Project in storage.
     *
     * @param CreateProjectRequest $request
     *
     * @return Response
     */
    public function store(CreateProjectRequest $request)
    {
        $input = $request->all();
        $input['user_id']=1;

        if(array_key_exists('caompanie_id',$input)){
            $caompanie = app('zcjy')->companyRepo()->findWithoutFail($input['caompanie_id']);
            if(!empty($caompanie)){
                $input['caompanie_name'] = $caompanie->name;
            }
        }

        $project = $this->projectRepository->create($input);

        if(array_key_exists('industries', $input)){
            $project->industries()->sync($input['industries']);
        }

        #project_images
        #添加附加图片
        if(array_key_exists('project_images',$input)){
            $this->projectRepository->syncImages($input['project_images'],$project->id);
        }


        Flash::success('创建成功');

        return redirect(route('projects.index'));
    }

    /**
     * Display the specified Project.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $project = $this->projectRepository->findWithoutFail($id);

        if (empty($project)) {
            Flash::error('没有找到该项目');

            return redirect(route('projects.index'));
        }

        return view('admin.projects.show')->with('project', $project);
    }

    /**
     * Show the form for editing the specified Project.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $project = $this->projectRepository->findWithoutFail($id);
        if (empty($project)) {
            Flash::error('没有找到该项目');

            return redirect(route('projects.index'));
        }
        $cities_level1=$this->cityRepository->getLevelNumCities(1);
        $cities_level2=$this->cityRepository->getCommonPidCitiesById($project->city);
        $cities_level3=$this->cityRepository->getCommonPidCitiesById($project->district);
        $images=$this->projectRepository->getImages($project);
        $industries=$this->industryRepository->getAllIndustries();

        $selectedIndustries=[];

        $projectIndustries=$project->industries()->get();

        foreach ($projectIndustries as $v) {
            array_push($selectedIndustries, $v->id);  
        }
        //dd($this->cityRepository->getCommonPidCitiesById(36));
        //dd($selectedIndustries);
        if (empty($project)) {
            Flash::error('没有找到该项目');
            return redirect(route('projects.index'));
        }
        $model_required = modelRequiredParam(app('zcjy')->projectRepo()->model()); 
        return view('admin.projects.edit')
                ->with('model_required',$model_required)
                ->with('project', $project)
                ->with('images',$images)
                ->with('cities_level1',$cities_level1)
                ->with('cities_level2',$cities_level2)
                ->with('cities_level3',$cities_level3)
                ->with('industries',$industries)
                ->with('selectedIndustries',$selectedIndustries);
    }

    /**
     * Update the specified Project in storage.
     *
     * @param  int              $id
     * @param UpdateProjectRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProjectRequest $request)
    {
        $project = $this->projectRepository->findWithoutFail($id);
        $input=$request->all();
        if (empty($project)) {
            Flash::error('没有找到该项目');

            return redirect(route('projects.index'));
        }

        if(array_key_exists('caompanie_id',$input)){
            $caompanie = app('zcjy')->companyRepo()->findWithoutFail($input['caompanie_id']);
            if(!empty($caompanie)){
                $input['caompanie_name'] = $caompanie->name;
            }
        }
        
        $project = $this->projectRepository->update($input,$id);

        if ( array_key_exists('industries', $input) ) {
            $project->industries()->sync($input['industries']);
        }

        #project_images
        #添加附加图片
        if(array_key_exists('project_images',$input)){
          $this->projectRepository->syncImages($input['project_images'],$project->id,true);
        }else{
          $this->projectRepository->clearImages($id);
        }

        Flash::success('更新成功');

        return redirect(route('projects.index'));
    }

    /**
     * Remove the specified Project from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $project = $this->projectRepository->findWithoutFail($id);

        if (empty($project)) {
            Flash::error('没有找到该项目');

            return redirect(route('projects.index'));
        }

        $this->projectRepository->delete($id);
        
        $this->projectRepository->clearImages($id);

        #一起清除记录
        app('zcjy')->projectSignRepo()->model()::where('project_id',$id)->delete();

        Flash::success('删除成功');

        return redirect(route('projects.index'));
    }
}
