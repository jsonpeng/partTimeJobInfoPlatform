<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use App\Models\Cities;
use App\Models\Setting;
use App\Models\Project;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

include_once "wxBizDataCrypt.php";

//use Log;

function weixinDecodeTel($sessionKey,$encryptedData,$iv){
    $appid = Config::get('wechat.mini_program.default.app_id');
    $pc = new WXBizDataCrypt($appid, $sessionKey);
    $errCode = $pc->decryptData($encryptedData, $iv, $data);
    if ($errCode == 0) {
        return $data;
    } 
    else {
        return $errCode;
    }
}


function user_by_id($id){
    //return Cache::remember('zcjy_user_by_id_'.$id, Config::get('web.shrottimecache'), function() use ($id) {
        try {
           return User::find($id);
        } catch (Exception $e) {
            return null;
        }
     
    //});
}

/**
 * [地图地址详细信息]
 * @param  [type] $address [description]
 * @return [type]          [description]
 */
function getAddressDetail($address){
     return Cache::remember('zcjy_get_address_detail'.$address, Config::get('web.longtimecache'), function() use ($address) {
            $client = new Client(['base_uri' => 'http://api.map.baidu.com']);
            $response = $client->request('GET', '/place/v2/suggestion?query='.mb_substr($address , 0 , 10 , 'utf-8').'&region='.mb_substr($address , 0 , 6 , 'utf-8').'city_limit=true&output=json&ak=usHzWa4rzd22DLO58GmUHUGTwgFrKyW5');
            $address_obj = $response->getBody();
            $address_obj = json_decode($address_obj,true);
            return $address_obj['result'][0];
     }); 
}


function getDetailBylt($jindu,$weidu)
{
     return Cache::remember('zcjy_get_address_by_location_lt'.$jindu.'_'.$weidu, Config::get('web.longtimecache'), function() use ($jindu,$weidu) {
            $client = new Client(['base_uri' => 'http://api.map.baidu.com']);
            $response = $client->request('GET', '/geocoder/v2/?ak=usHzWa4rzd22DLO58GmUHUGTwgFrKyW5&location='.$weidu.','.$jindu.'&output=json&pois=1');
            $obj = $response->getBody();
            $obj = json_decode($obj,true);
            return ($obj['result']['addressComponent']);

     }
    );
}

/**
 * [地图逆解析 根据经纬度获取地址详情]
 * @param  [type] $jindu [description]
 * @param  [type] $weidu [description]
 * @return [type]        [description]
 */
function getAddressLocation($jindu,$weidu){
  return Cache::remember('zcjy_get_address_by_location_'.$jindu.'_'.$weidu, Config::get('web.longtimecache'), function() use ($jindu,$weidu) {
            $address = file_get_contents('http://api.map.baidu.com/geocoder/v2/?callback=renderReverse&location='.$weidu.','.$jindu.'&output=json&pois=1&ak=usHzWa4rzd22DLO58GmUHUGTwgFrKyW5');

            $address = explode(',',$address); 

            $sub_address = address_str_sub($address,3,21);
            $province = address_str_sub($address,9,12);
            $city = address_str_sub($address,10,8);
            $district = address_str_sub($address,12,12);

         
            $client = new Client(['base_uri' => 'http://api.map.baidu.com']);
            $response = $client->request('GET', '/place/v2/search?query=大学&location='.$weidu.','.$jindu.'&radius=5000&output=json&ak=usHzWa4rzd22DLO58GmUHUGTwgFrKyW5');
            $school_obj = $response->getBody();
            $school_obj = json_decode($school_obj,true);
            //return ($school_obj['results']);
            return (object)['address'=>$sub_address,'province'=>$province,'city'=>$city,'district'=>$district,'school'=>$school_obj['results']];
    });
} 

/**
 * [address_str_sub description]
 * @param  [type]  $str [description]
 * @param  integer $len [3,21地址 9,12省份]
 * @return [type]       [description]
 */
function address_str_sub($address,$len1=3,$len2=21){
    $str = substr($address[$len1],$len2);
    $str =substr($str,0,strlen($str)-1);
    return $str;
}

function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = object_array($value);  
             }  
     }  
     return $array;  
}

//当前api用户
function zcjy_api_user($input){
     $token = explode('_', zcjy_base64_de($input['token']));
     return empty(session('zcjy_api_user_'.$token[0])) ? user_by_id($token[0]) : session('zcjy_api_user_'.$token[0]);
}

//加密
function zcjy_base64_en($str){
    $str = str_replace('/','@',str_replace('+','-',base64_encode($str)));
    return $str;
}

//解密
function zcjy_base64_de($str){
    $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');
    $str = base64_decode(str_replace('@','/',str_replace('-','+',$str)));
    $encoded = mb_detect_encoding($str, $encode_arr);
    $str = iconv($encoded,"utf-8",$str);
    return $str;
}

/**
 * [接口请求回转数据格式]
 * @param  type    $data     [成功/失败提示]
 * @param  integer $code     [0成功 1失败]
 * @param  string  $api      [默认不传是api格式 传web是web格式]
 * @return [type]            [description]
 */
function zcjy_callback_data($data=null,$code=0,$api='api'){
    return $api === 'api'
        ? api_result_data_tem($data,$code)
        : web_result_data_tem($data,$code);
}

/**
 * [把文字加粗并且变色]
 * @param  [type] $string [文字]
 * @param  string $color  [颜色 默认红色]
 * @return [type]         [description]
 */
function tag($string,$color='red'){
    return '&nbsp;&nbsp;<strong style=color:'.$color.'>'.$string.'</strong>&nbsp;&nbsp;';
}



/**
 * [把文字变成链接 并且带上颜色]
 * @param  [type]  $string [文字]
 * @param  [type]  $link   [链接]
 * @param  string  $color  [颜色 默认橙色]
 * @param  boolean $nbsp   [是否加左右间隔]
 * @return [type]          [description]
 */
function a_link($string,$link,$color='orange',$nbsp=true){
     return $nbsp ? '&nbsp;&nbsp;<a target=_blank href='.$link.' style=color:'.$color.'>'.$string.'</a>&nbsp;&nbsp;' : '<a target=_blank href='.$link.' style=color:'.$color.'>'.$string.'</a>';
}


/**
 * [api接口请求回转数据]
 * @param  [type]  $message  [成功/失败提示]
 * @param  integer $code     [0成功 1失败]
 * @return [type]            [description]
 */
function api_result_data_tem($data=null,$status_code=0){
     return response()->json(['status_code'=>$status_code,'data'=>$data]);
}

/**
 * [web程序请求回转数据]
 * @param  [type]  $message  [成功/失败提示]
 * @param  integer $code     [0成功 1失败]
 * @return [type]            [description]
 */
function web_result_data_tem($message=null,$code=0){
    return response()->json(['code'=>$code,'message'=>$message]);
}

function modelRequiredParam($model,$return_array=false){
    $requireds = $model::$rules;
    $attr = [];
    foreach ($requireds as $key => $value) {
        array_push($attr,$key);
    }
    $attr = !$return_array ? implode(',',$attr) : $attr;
   return $attr;
}


function getSettingValueByKey($key){
     return app('setting')->valueOfKey($key);
}

function getSettingValueByKeyCache($key){
    return Cache::remember('getSettingValueByKey'.$key, Config::get('web.cachetime'), function() use ($key){
        return getSettingValueByKey($key);
    });
}


function funcOpen($func_name)
{
    $config  = Config::get('web.'.$func_name);
    return empty($config) ? false : $config;
}

function funcOpenCache($func_name)
{
    return Cache::remember('funcOpen'.$func_name, Config::get('web.cachetime'), function() use ($func_name){
        return funcOpen($func_name);
    });
}

function arrayToString($re1){
    $str = "";
    $cnt = 0;
    foreach ($re1 as $value)
    {
        if($cnt == 0) {
            $str = $value;
        }
        else{
            $str = $str.','.$value;
        }
        $cnt++;
    }
}

//修改env
function modifyEnv(array $data)
{
    $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

    $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

    $contentArray->transform(function ($item) use ($data){
        foreach ($data as $key => $value){
            if(str_contains($item, $key)){
                return $key . '=' . $value;
            }
        }
        return $item;
    });

    $content = implode($contentArray->toArray(), "\n");

    \File::put($envPath, $content);
}

function array_remove($arr, $key){
    if(!array_key_exists($key, $arr)){
        return $arr;
    }
    $keys = array_keys($arr);
    $index = array_search($key, $keys);
    if($index !== FALSE){
        array_splice($arr, $index, 1);
    }
    return $arr;

}




//通过admin对象验证路由权限
function varifyAllRouteByAdminObj($admin,$uri){
    $roles=$admin->roles()->get();
    $status=false;
    if(!empty($roles)) {
        foreach ($roles as $item) {
            $perms = $item->perms()->where('name','like','%'.'*'.'%')->get();
            //dd($perms);
            if(!empty($perms)){
                foreach($perms as $perm){
                    //|| strpos($uri,substr($perm->name,0,strlen($perm->name)-5))!==false
                    if(strpos($uri,substr($perm->name,0,strlen($perm->name)-2))!==false){
                        $status=true;
                    }
                }
            }
        }
        return $status;
    }else{
        return false;
    }
}

//通过路由名验证当前登录管理员是否有权限
function varifyAdminPermByRouteName($route_name){
    $admin=Auth::guard('admin')->user();
    $status_perm=true;
    if (!$admin->can($route_name)) {
           // if(!varifyAllRouteByAdminObj($admin,$route_name)) {
                $status_perm=false;
           // }
    }
    return $status_perm;
}

//自动根据tid匹配功能分组或者返回功能个数
function autoMatchRoleGroupNameByTid($tid,$get_length=true){
    $group_func=Config::get('rolesgroupfunc');
    $match_attr=[];
    $length=1;
    foreach ($group_func as $item){
        if($item['tid']==$tid){
            array_push($match_attr,$item['word']);
            $length=$item['length'];
        }
    }
    if($get_length) {
        return $length;
    }else{
        return count($match_attr)?$match_attr[0]:'未命名';
    }
}

function autoReturnGroupByModal($modal_name,$times){
    if($times>1){
        return;
    }
    $group_func=Config::get('rolesgroupfunc');
    $match_word=[];
    foreach ($group_func as $item){
        if($item['modal']==$modal_name){
            array_push($match_word,$item['word']);
        }
        return $match_word;
    }

}

//根据pid获取上级地区的路由
function varifyPidToBackByPid($pid){
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
function getFreightInfoByCitiesId($cities_id){
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

/**
 * 指定位置插入字符串
 * @param $str  原字符串
 * @param $i    插入位置
 * @param $substr 插入字符串
 * @return string 处理后的字符串
 */
function insertToStr($str, $i, $substr){
    //指定插入位置前的字符串
    $startstr="";
    for($j=0; $j<$i; $j++){
        $startstr .= $str[$j];
    }

    //指定插入位置后的字符串
    $laststr="";
    for ($j=$i; $j<strlen($str); $j++){
        $laststr .= $str[$j];
    }

    //将插入位置前，要插入的，插入位置后三个字符串拼接起来
    $str = $startstr . $substr . $laststr;

    //返回结果
    return $str;
}


function getCitiesNameById($cities_id)
{
    $city=Cities::find($cities_id);
    if(!empty($city)) {
        return $city->name;
    }else{
        return null;
    }
}

/**
 * 验证是否展开
 * @return [int] [是否展开tools 0不展开 1展开]
 */
function varifyTools($input,$order=false){
    $tools=0;
    if(count($input)){
        $tools=1;
        if(array_key_exists('page', $input) && count($input)==1) {
            $tools = 0;
        }
        if($order){
            if(array_key_exists('menu_type', $input) && count($input)==1) {
                $tools = 0;
            }
        }
    }
    return $tools;
}

/**
 * 倒序分页显示
 * @parameter [object]
 * @return [array] [desc]
 */
function descAndPaginateToShow($obj){
    if(!empty($obj)){
      return  $obj->orderBy('created_at','desc')->paginate(defaultPage());
    }else{
        return [];
    }
}

/**
 * 默认分页数量
 * @parameter []
 * @return [int] [每页显示数量]
 */
function defaultPage(){
    return empty(getSettingValueByKey('records_per_page')) ? 15 : getSettingValueByKey('records_per_page');
}


//截取内容
function sub_content($str, $num=120){
        global $Briefing_Length;
        mb_regex_encoding("UTF-8");
        $Foremost = mb_substr($str, 0, $num);
        $re = "<(\/?) 
    (P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|TABLE|TR|TD|TH|INPUT|SELECT|TEXTAREA|OBJECT|A|UL|OL|LI| 
    BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|SPAN)[^>]*(>?)";
        $Single = "/BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|BR/i";

        $Stack = array(); $posStack = array();

        mb_ereg_search_init($Foremost, $re, 'i');

        while($pos = mb_ereg_search_pos()){
            $match = mb_ereg_search_getregs();

            if($match[1]==""){
                $Elem = $match[2];
                if(mb_eregi($Single, $Elem) && $match[3] !=""){
                    continue;
                }
                array_push($Stack, mb_strtoupper($Elem));
                array_push($posStack, $pos[0]);
            }else{
                $StackTop = $Stack[count($Stack)-1];
                $End = mb_strtoupper($match[2]);
                if(strcasecmp($StackTop,$End)==0){
                    array_pop($Stack);
                    array_pop($posStack);
                    if($match[3] ==""){
                        $Foremost = $Foremost.">";
                    }
                }
            }
        }

        $cutpos = array_shift($posStack) - 1;
        $Foremost =  mb_substr($Foremost,0,$cutpos,"UTF-8");
        return strip_tags($Foremost);

}

//截取内容中的图片
function get_content_img($text){   
  
    //取得所有img标签，并储存至二维数组 $match 中   
    preg_match_all('/<img[^>]*>/i', $text, $match);   
      
    return $match;
}

//是否受到限制
function limit($amount,$project_money){

    return $amount < $project_money ? true : false;
}

//替换上传图片的url
function replace_img_url($image_attr){

   return str_replace("../../","/",implode('', $image_attr));
}

/**
 * 获取企业、项目的收藏状态
 * @param  [string] $type        [获取类型]
 * @param  [int]    $id          [对应id]
 * @return [int]                 [状态位]
 */
function getCollectionStatus($type,$id){
    $user = auth('web')->user();
    if($type=='project'){
        return $user->projects()->whereRaw('projects.id = '.$id)->count();
    }else{
        return $user->caompanys()->whereRaw('caompanies.id = '.$id)->count();
    }
}

/**
 * 纠错信息的选项 多少个
 */
function getErrorList(){
      $list= preg_replace("/\n|\r\n/", "_",getSettingValueByKey('error_info_list'));
      $list_arr = explode('_',$list);
      return $list_arr;
}

/**
 * 项目金额的选项 多少个
 */
function projectMoneyList(){
      $list= preg_replace("/\n|\r\n/", "_",getSettingValueByKey('project_money_list'));
      $list_arr = explode('_',$list);
      return $list_arr;
}

function getFrontDefaultPage(){
    return empty(getSettingValueByKey('front_take'))?16:getSettingValueByKeyCache('front_take');
}


function iconv_system($str){   
    global $config; 
    $result = iconv($config['app_charset'], 
    $config['system_charset'], $str); 
      if (strlen($result)==0) {  
           $result = $str; 
       }   
       return $result;
}

