<?php

namespace App\Http\Controllers\Admin;

//use App\Repositories\ManagerRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\CityRepository;
use Hash;
use App\Models\Cities;

class CitiesController extends AppBaseController
{
    private $cityRepository;
    public function __construct(CityRepository $cityRepo)
    {
        $this->cityRepository=$cityRepo;
    }

    /**
     * Display a listing of the BankSets.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
     
        $cities=$this->cityRepository->getLevelNumCities(1);
        /*
        $city=$this->cityRepository->getLevel1CitiesWithRegional();
        $str='';
        foreach ($city as $key => $value) {
            $str .=$key.":";
            foreach ($city[$key] as $item) {
                $str .=$item['name'].",";
            }
        }
        return $str;
        */
      
        return view('admin.common.cities.index')
                ->with('cities', $cities);
    }

    public function map(Request $request){
        $address=empty($request->get('address'))?'武汉市':$request->get('address');
         return view('admin.common.cities.map')
                ->with('address', $address);
    }

    public function show(Request $request)
    {
       // $this->managerRepository->pushCriteria(new RequestCriteria($request));
        $cities=Cities::where('level',1)->get();
        return view('admin.common.cities.index')
            ->with('cities', $cities);
    }

    public function CitiesAjaxSelect($id){
        $cities=Cities::where('pid',$id)->get();
      
        return $this->cityRepository->getCitiesAjaxSelect($cities);
    }

    public function DiyuCitiesAjaxSelect($diyu){
        if($diyu=='0'){
            return['code'=>1,'message'=>'没有找到地区'];
        }
        $cities_arr=$this->cityRepository->getRegionalCitiesArrOnlyId($diyu);
        $cities= Cities::whereIn('id',$cities_arr)->get();
        return $this->cityRepository->getCitiesAjaxSelect($cities);
    }

    public function CitiesSelectFrame(){
        $cities_level1=Cities::where('level',1)->get();
        return view('admin.common.cities.select')->with('cities_level1',$cities_level1);

    }

    public function ChildList(Request $request,$pid){
        $cities=Cities::where('pid',$pid)->get();
        return view('admin.common.cities.childlist')
            ->with('pid',$pid)
            ->with('cities', $cities);
    }

    public function GetFreightTemByCid($cid){
        $freight_tem=getFreightInfoByCitiesId($cid);
        if(!empty($freight_tem)){
            return ['code'=>0,'message'=>$freight_tem];
        }else{
            return ['code'=>1,'message'=>'没有找到对应的运费模板信息'];
        }
    }

    public function GetFreightTemByCidFrame($cid){
        $cities=Cities::find($cid);
        $freight_tem=getFreightInfoByCitiesId($cid);
        return view('admin.common.cities.freight_tem')
                ->with('freight_tem',$freight_tem)
                ->with('cities',$cities);
    }
    /**
     * Show the form for creating a new BankSets.
     *
     * @return Response
     */
    public function create()
    {
        $pid=1;
        $level=1;
        return view('admin.common.cities.create')
                ->with('pid',$pid)
                ->with('level',$level);
    }

    public function ChildCreate($pid){
        $last_cities=Cities::find($pid);
        if(!empty($last_cities)) {
           $level =$last_cities->level+1;
         return view('admin.common.cities.create_child')
            ->with('last_cities', $last_cities->name)
            ->with('pid', $pid)
            ->with('level',$level);
        }
    }



    /**
     * Store a newly created BankSets in storage.
     *
     * @param CreateBankSetsRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        Cities::create($input);
        Flash::success('保存成功.');
        if($input['pid']==1) {
            return redirect(route('cities.index'));
        }else{
            return redirect(route('cities.child.index',[$input['pid']]));
        }
    }

    /**
     * Remove the specified BankSets from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cities = Cities::find($id);

        if (empty($cities)) {
            Flash::error('没有该地区');

            return redirect(route('managers.index'));
        }

        $cities->delete($id);

        Flash::success('删除成功.');

        return redirect(route('cities.index'));
    }
}
