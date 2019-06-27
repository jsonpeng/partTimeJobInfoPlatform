<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Illuminate\Support\Facades\Artisan;
use Log;

class AppBaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }

     /**
     * 后台显示获取分页数目
     * @return [int] [分页数目]
     */
    public function defaultPage(){
        return empty(getSettingValueByKey('records_per_page')) ? 15 : getSettingValueByKey('records_per_page');
    }

    /**
     * 验证是否展开
     * @return [int] [是否展开tools 0不展开 1展开]
     */
    public function varifyTools($input,$order=false){
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
     * 倒序显示带分页
     */
    public function descAndPaginateToShow($obj){
       if(!empty($obj)){
      		return $obj->orderBy('created_at','desc')->paginate($this->defaultPage());
	    }else{
	        return [];
	    }
    }

    /**
     * 查询索引初始化状态
     */
    public function defaultSearchState($obj){
         if(!empty($obj)){
            return $obj::where('id','>',0);
         }else{
            return [];
         }
    }
}
