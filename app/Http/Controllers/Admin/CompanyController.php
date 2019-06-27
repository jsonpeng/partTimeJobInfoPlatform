<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateCaompanyRequest;
use App\Http\Requests\UpdateCaompanyRequest;
use App\Repositories\CaompanyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\CityRepository;

class CompanyController extends AppBaseController
{
    /** @var  CaompanyRepository */
    private $companyRepository;
    private $cityRepository;
    public function __construct(CaompanyRepository $companyRepo,CityRepository $cityRepo)
    {
        $this->companyRepository = $companyRepo;
        $this->cityRepository = $cityRepo;
    }

    /**
     * Display a listing of the Caompany.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->companyRepository->pushCriteria(new RequestCriteria($request));
        $input=$request->all();
        $input =array_filter( $input, function($v, $k) {
            return $v != '';
        }, ARRAY_FILTER_USE_BOTH );
        $tools=$this->varifyTools($input);
    
        $caompanies=$this->defaultSearchState($this->companyRepository->model());
        $level01=0;
        $level02=0;
        $diyu=$this->cityRepository->getLevel1CitiesWithRegional();
        $cities=[0=>'请选择地区'];
        if(array_key_exists('name',$input)){
            $caompanies = $caompanies->where('name', 'like','%'.$input['name'].'%');
        }
        if(array_key_exists('diyu',$input)){
            if(!empty($input['diyu'])){
                $province_arr=$this->cityRepository->getRegionalCitiesArrOnlyId($input['diyu']);
                $cities=$this->cityRepository->getCitiesArrSelectByArr($province_arr);
                $caompanies = $caompanies->whereIn('province', $province_arr);
            }
        }
        //选择城市
        if(array_key_exists('cities',$input)){
            $level02=$input['cities'];
            if(!empty($input['cities'])){
                $caompanies = $caompanies->where('province', $input['cities']);
            }
        }
        $caompanies =  $this->descAndPaginateToShow($caompanies);
      
        return view('admin.caompanies.index')
            ->with('caompanies', $caompanies)
            ->with('tools',$tools)
            ->with('diyu',$diyu)
            ->with('cities',$cities)
            ->with('level01',$level01)
            ->with('level02',$level02)
            ->with('input',$input);
    }

    /**
     * Show the form for creating a new Caompany.
     *
     * @return Response
     */
    public function create()
    {
        $cities_level1=$this->cityRepository->getLevelNumCities(1);
        $model_required = modelRequiredParam(app('zcjy')->companyRepo()->model()); 
        return view('admin.caompanies.create')
                ->with('company',null)
                ->with('images',[])
                ->with('cities_level1',$cities_level1)
                ->with('cities_level2',[])
                ->with('cities_level3',[])
                ->with('model_required',$model_required);
    }

    /**
     * Store a newly created Caompany in storage.
     *
     * @param CreateCaompanyRequest $request
     *
     * @return Response
     */
    public function store(CreateCaompanyRequest $request)
    {
        $input = $request->all();
        $input['user_id']=1;
     
        $company = $this->companyRepository->create($input);

        #添加附加图片
        if(array_key_exists('company_images',$input)){
          $this->companyRepository->syncImages($input['company_images'],$company->id);
        }

        Flash::success('保存成功');

        return redirect(route('caompanies.index'));
    }

    /**
     * Display the specified Caompany.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $company = $this->companyRepository->findWithoutFail($id);

        if (empty($company)) {
            Flash::error('没有找到该企业');

            return redirect(route('caompanies.index'));
        }

        return view('admin.caompanies.show')->with('caompany', $company);
    }

    /**
     * Show the form for editing the specified Caompany.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $company = $this->companyRepository->findWithoutFail($id);
        $cities_level1=$this->cityRepository->getLevelNumCities(1);
        $cities_level2=$this->cityRepository->getCommonPidCitiesById($company->city);
        $cities_level3=$this->cityRepository->getCommonPidCitiesById($company->district);

        $images=$this->companyRepository->getImages($company);
      
    
        if (empty($company)) {
            Flash::error('没有找到该企业');

            return redirect(route('caompanies.index'));
        }

        $model_required = modelRequiredParam(app('zcjy')->companyRepo()->model()); 

        return view('admin.caompanies.edit')
              ->with('caompany', $company)
              ->with('images',$images)
              ->with('cities_level1',$cities_level1)
              ->with('cities_level2',$cities_level2)
              ->with('cities_level3',$cities_level3)
              ->with('model_required',$model_required);
    }

    /**
     * Update the specified Caompany in storage.
     *
     * @param  int              $id
     * @param UpdateCaompanyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCaompanyRequest $request)
    {
        $company = $this->companyRepository->findWithoutFail($id);

        if (empty($company)) {
            Flash::error('没有找到该企业');

            return redirect(route('caompanies.index'));
        }
        $input=$request->all();
        if($input['status'] == '通过'){
            $user = $company->user()->first();
            $user->update(['type'=>'企业']);
        }
        $company = $this->companyRepository->update($input,$id);

        #添加附加图片
        if(array_key_exists('company_images',$input)){
          $this->companyRepository->syncImages($input['company_images'],$company->id,true);
        }else{
          $this->companyRepository->clearImages($id);
        }

        Flash::success('更新成功');

        return redirect(route('caompanies.index'));
    }

    /**
     * Remove the specified Caompany from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $company = $this->companyRepository->findWithoutFail($id);

        if (empty($company)) {
            Flash::error('没有找到该企业');

            return redirect(route('caompanies.index'));
        }

        #把对应用户设置为个人
        $user = $company->user()->first();

        $user->update(['type'=>'个人']);

        $this->companyRepository->delete($id);

        #删除把图片也干掉
        $this->companyRepository->clearImages($id);

        Flash::success('删除成功');
        
        return redirect(route('caompanies.index'));
    }
}
