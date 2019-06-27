<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageOrientation;
use App\Repositories\CategoryRepository;
use App\Repositories\CaompanyRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\CompanyErrorRepository;

use Illuminate\Support\Facades\Input;
use Redirect,Response;
use Image;
use Log;
use Config;

class AjaxController extends Controller
{
    private $categoryRepository;
    private $caompanyRepository;
    private $projectRepository;
    private $companyErrorRepository;
    public function __construct(
        CategoryRepository $categoryRepo,
        CompanyErrorRepository $companyErrorRepo,
        CaompanyRepository $caompanyRepo,
        ProjectRepository $projectRepo
    )
    {
        $this->categoryRepository=$categoryRepo;
        $this->caompanyRepository=$caompanyRepo;
        $this->projectRepository=$projectRepo;
        $this->companyErrorRepository=$companyErrorRepo;
    }

   /**
    * 更新项目
    * @param  [int] $id [description]
    * @return [object]  [json object]
    */
   public function updateProject(Request $request,$id){

		 return $this->defaultAjaxActionByRepo($this->projectRepository,$request->all(),'update',$id);

   }

   /**
    * 创建项目
    * @param  [type] 	 [description]
    * @return [object] [json object]
    */
   public function storeProject(Request $request){
     
   		return $this->defaultAjaxActionByRepo($this->projectRepository,$request->all(),'store');
   }

	/**
 	 * 查看单个项目详情
 	 * @param  [int] $id [description]
 	 * @return [object]  [json object]
 	 */
   public function showProject(Request $request,$id){

   	 return  $this->defaultAjaxActionByRepo($this->projectRepository,$request->all(),'show',$id);

   }

 	/**
 	 * 删除项目
 	 * @param  [int] $id [description]
 	 * @return [object]  [json object]
 	 */
   public function deleteProject(Request $request,$id){

   	  return $this->defaultAjaxActionByRepo($this->projectRepository,$request->all(),'delete',$id);

   }



   /**
    * 收藏项目 detach attach
    * @param  [int] $id 	   [description]
    * @param  [type] $status [0 取消收藏 1收藏]
    * @return [type]     	   [description]
    */
   public function attachProject(Request $request,$id,$status){

   		return app('user')->attachAction($this->user(),$id,'project',$status);

   }

   /**
    * 创建公司
    * @param  [type]    [description]
    * @return [object]  [json object]
    */
   public function storeCompany(Request $request){
    //return $request->all();
   	return $this->defaultAjaxActionByRepo($this->caompanyRepository,$request->all(),'store');

   }

   /**
    * 收藏公司
    * @param  [int] $id 		   [description]
    * @param [type] $status    [0 取消收藏 1收藏]
    */
 	public function attachCompany(Request $request,$id,$status){
		  
    return app('user')->attachAction($this->user(),$id,'company',$status);

   }

   /**
    * 提交企业纠错信息
    */
   public function submitCompanyErrorInfo(Request $request){

    return $this->defaultAjaxActionByRepo($this->companyErrorRepository,$request->all(),'store');

   }

   /**
    * 更新企业纠错信息
    */
   
   public function updateCompanyErrorInfo(Request $request,$id){

       return $this->defaultAjaxActionByRepo($this->companyErrorRepository,$request->all(),'update',$id,true);

   }

   /**
    * 分页取不同类型的内容
    */
   public function paginageToGetData(Request $request,$type){
        $input=$request->all();
        $skip=empty($input['skip'])?0:$input['skip'];
        $take=empty($input['take'])?16:$input['take'];
        $data=$type;
        #取文章
        if($type=='post'){
          #取分类的别名
          $slug=$input['slug'];
          $data=$this->categoryRepository->getPostByCatSlugWithSkipAndTake($slug,$skip,$take);
        }

        #取企业列表
        if($type=='company'){
          $data=$this->caompanyRepository->getCompanies($skip,$take);
        }

        #取项目
        if($type=='project'){

            #中间类型 3种
            $mid_type=$input['mid_type'];

            #项目类型 2种 项目 需求
            $project_type=$input['project_type'];
          
            #第一种 按金钱
            if($mid_type=='1'){
                #升序还是降序
               $asc=$input['sort'];
               $data=$this->projectRepository->getProjectsByMoneySort($asc,$project_type,$skip,$take);
            }

            #第二种 按地域
            if($mid_type=='2'){
              #地域id
              $diyu=$input['diyu'];
              $data=$this->projectRepository->getProjectsByDiyu($diyu,$project_type,$skip,$take);
            }

            #第三种 按行业类型
            if($mid_type=='3'){
              #行业id
              $hangye=$input['hangye'];
              $data=$this->projectRepository->getProjectsByHangye($hangye,$project_type,$skip,$take);
            }

        }
        
        return ['code'=>0,'message'=>$data];
   }
   


   /**
    * 图片上传
    */
    public function uploadImage(){
        $file =  Input::file('file');

        if(empty($file)) {
      
             return ['code' => 1, 'messsage' => '图片格式不正确'];
        }

        #用户信息
        $user=$this->user();

        #图片文件夹
        $destinationPath = "uploads/user/".$user->id."/";

        if (!file_exists($destinationPath)){
            mkdir ($destinationPath,0777,true);
        }
       
        $extension = $file->getClientOriginalExtension();
        $fileName = str_random(10).'.'.$extension;
        $file->move($destinationPath, $fileName);

        $image_path=public_path().'/'.$destinationPath.$fileName;

        //先把图像转成jpg
        $img = Image::make($image_path);
       // $img->encode('jpeg',90);
        //$img->save($image_path,90);

        //处理图像 如果无法支持就跳过直接下一步处理
        try{
        @$exif=exif_read_data($image_path);
        Log::info('exif :');
        Log::info($exif);

        //判断拍照方向
        if(!empty($exif['Orientation'])) {
           switch($exif['Orientation']) {
            case 8:
             $img->rotate(90);
             break;
            case 3:
             $img->rotate(180);
             break;
            case 6:
             $img->rotate(-90);
             break;
           }
        }
      }catch(Exception $e){

      }
        //压缩尺寸
        $img->resize(640,640);
        //->encode('jpg',90) ->flip('h')rotate(90);
        //图片保存
        $img->save($image_path,70);
        //$image_fangxiang=new ImageOrientation($image_path,'Orientation','1');
        $host='http://'.$_SERVER["HTTP_HOST"];
        #图片路径
        $img_src=$host.'/'.$destinationPath.$fileName;
        return [
            'code' => '0',
            'message' => [
                'src'=>$img_src,
                'current_time' => date('Y-m-d H:i:s'),
                'info'=>''
            ]
        ];
    }
   

}