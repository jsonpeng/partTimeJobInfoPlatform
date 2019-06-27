<?php

namespace App\Repositories;

use App\Models\Cities;
use InfyOm\Generator\Common\BaseRepository;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class CityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pid',
        'name',
        'level',
        //'path'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Cities::class;
    }

    //根据pid获取上级地区的路由
    public function getLastCitiesRouterByPid($pid){
        $parent_cities=Cities::find($pid);
        if($parent_cities->level==1){
            return route('cities.index');
        }else{
            $back_cities=Cities::find($pid)->ParentCitiesObj;
            if(!empty($back_cities)) {
                return route('cities.child.index', [$back_cities->id]);
            }
        }
    }

    //根据地区id返回对应运费模板信息
   public function getFreightInfoByCitiesId($cities_id){
        $city=Cities::find($cities_id);
        if(!empty($city)) {
            $freigt_tem = $city->freightTems()->get();
            if (!empty($freigt_tem)) {
                $freigt_tem_arr = [];
                $i = 0;
                foreach ($freigt_tem as $item) {
                    $freight_type = $item->pivot->freight_type;
                    $freight_first_count = $item->pivot->freight_first_count;
                    $the_freight = $item->pivot->the_freight;
                    $freight_continue_count = $item->pivot->freight_continue_count;
                    $freight_continue_price = $item->pivot->freight_continue_price;
                    $freigt_tem_arr[$i] = ['name'=>$item->name,'use_default'=>$item->SystemDefault,'freight_type' => $freight_type, 'freight_first_count' => $freight_first_count, 'the_freight' => $the_freight, 'freight_continue_count' => $freight_continue_count, 'freight_continue_price' => $freight_continue_price];
                    $i++;
                }
                return $freigt_tem_arr;
            } else {
                return null;
            }
        }else{
            return null;
        }
    }

    public function getFreightInfoByAddress($address){
        $all=getFreightInfoByCitiesId(1);
        $province=getFreightInfoByCitiesId($address->province);
        $city=getFreightInfoByCitiesId($address->city);
        $district=getFreightInfoByCitiesId($address->district);
        $weightType = 0;
        //首重重量
        $freight_first_weight = 0;
        //首重价格
        $freight_first_price = 0;
        //续重重量
        $freight_continue_weight = 0;
        //续重价格
        $freight_continue_price = 0;

        if(!empty($all)){
            //计费方式
            $weightType = $all[0]['freight_type'];
            //首重重量
            $freight_first_weight =  $all[0]['freight_first_count'];
            //首重价格
            $freight_first_price = $all[0]['the_freight'];
            //续重重量
            $freight_continue_weight = $all[0]['freight_continue_count'];
            //续重价格
            $freight_continue_price = $all[0]['freight_continue_price'];
        }

        if(!empty($province)){
            //计费方式
            $weightType = $province[0]['freight_type'];
            //首重重量
            $freight_first_weight =  $province[0]['freight_first_count'];
            //首重价格
            $freight_first_price = $province[0]['the_freight'];
            //续重重量
            $freight_continue_weight = $province[0]['freight_continue_count'];
            //续重价格
            $freight_continue_price = $province[0]['freight_continue_price'];
        }

        if(!empty($city)){
            //计费方式
            $weightType = $city[0]['freight_type'];
            //首重重量
            $freight_first_weight =  $city[0]['freight_first_count'];
            //首重价格
            $freight_first_price = $city[0]['the_freight'];
            //续重重量
            $freight_continue_weight = $city[0]['freight_continue_count'];
            //续重价格
            $freight_continue_price = $city[0]['freight_continue_price'];
        }

        if(!empty($district)){
            //计费方式
            $weightType = $district[0]['freight_type'];
            //首重重量
            $freight_first_weight =  $district[0]['freight_first_count'];
            //首重价格
            $freight_first_price = $district[0]['the_freight'];
            //续重重量
            $freight_continue_weight = $district[0]['freight_continue_count'];
            //续重价格
            $freight_continue_price = $district[0]['freight_continue_price'];
        }

        return ['freight_type'=>$weightType,'freight_first_count'=>$freight_first_weight,'freight_first_price'=>$freight_first_price,'freight_continue_count'=>$freight_continue_weight,'freight_continue_price'=>$freight_continue_price];

    }

    //获取第一级城市
    public function getBasicLevelCities(){
        $cities=Cities::where('level',1)->get();
        if(!empty($cities)){
            return $cities;
        }else{
            return [];
        }
    }

    //根据id获取子集
    public function getChildCitiesById($cities_id){
        $cities=Cities::where('pid',$cities_id)->get();
        $cities_list=[];
        if(!empty($cities)){
            foreach ($cities as $key=>$city){
                $cities_list[$key]=['id'=>$city->id,'name'=>$city->name];
            }
            return $cities_list;
        }else{
            return $cities_list;
        }

    }


    //根据等级获取城市
    public function getLevelNumCities($level){
        $cities=Cities::where('level',$level)->get();
        if(!empty($cities)){
            return $cities;
        }else{
            return [];
        }
    }

    //获取第一级城市带地域
    public function getLevel1CitiesWithRegional(){
        $cities=Config::get('city');
        return $cities;
    }

    //获取地域下的城市列表
    public function getRegionalCities($regional){
      return ($this->getLevel1CitiesWithRegional())[$regional];
    }

    //获取地域下的城市列表 只是id的数组集合
    public function getRegionalCitiesArrOnlyId($regional){
        $cities=$this->getRegionalCities($regional);
        $cities_arr=[];
        foreach ($cities as $v) {
            array_push($cities_arr,$v['id']);
        }
        return $cities_arr;
    }

    //将列表整理为select option给前端
    public function getCitiesAjaxSelect($cities){
      $ajax_select=null;
      if(count($cities)){
            foreach ($cities as $city){
                $ajax_select .="<option value=".$city->id.">".$city->name."</option>";
            }
            return ['code'=>0,'message'=>$ajax_select];
        }else{
            return ['code'=>1,'message'=>'无'];
        }
    }

   //将列表整理为arr给前端 id=>城市名
    public function getCitiesArrSelectByArr($cities_arr){
           $cities=Cities::whereIn('id',$cities_arr)->get();
           $cities_final=[];
     
           foreach ($cities as  $v) {
               $cities_final[$v->id]=$v->name;
          
           }
           return $cities_final;
    }

    //获取相同pid的城市
    public function getCommonPidCitiesById($cities_id){
        if(empty($cities_id)){
            return [];
        }
        $city=Cities::find($cities_id);
        if(!empty($city)){
            $cities=Cities::where('pid',$city->pid)->get();
            return $cities;
        }else{
            return [];
        }

    }

    //根据省份名称获取对应的省份id
    public function getProvinceIdByName($name){
        $city = Cities::where('name','like','%'.$name.'%')->first();
        if(!empty($city)){
            return $city->id;
        }else{
            return null;
        }
    }

}
