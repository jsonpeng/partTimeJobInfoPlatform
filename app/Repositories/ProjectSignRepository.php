<?php

namespace App\Repositories;

use App\Models\ProjectSign;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ProjectSignRepository
 * @package App\Repositories
 * @version July 3, 2018, 12:47 pm CST
 *
 * @method ProjectSign findWithoutFail($id, $columns = ['*'])
 * @method ProjectSign find($id, $columns = ['*'])
 * @method ProjectSign first($columns = ['*'])
*/
class ProjectSignRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'self_des',
        'project_id',
        'user_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProjectSign::class;
    }

    /**
     * 检查一下用户有没有报名过该兼职
     */
    public function varifyUserWhetherSign($project_id,$user_id){
        $status = false;
        $signs = ProjectSign::where('project_id',$project_id)->where('user_id',$user_id)->first();
        if(!empty($signs)){
            $status = '已报名过该兼职,不可重复报名!';
        }
        return $status;
    }

    /**
     * [当前已录用人数和结算人数]
     * @param  [type] $project_id [description]
     * @return [type]             [description]
     */
    public function nowSignNum($project_id){
        $sign_pro = ProjectSign::where('project_id',$input['project_id'])
        ->get();
        $sign_pro = $sign_pro->filter(function($item,$key){
            return $item->status == '已录用' || $item->status == '已结算';
        }); 
        return count($sign_pro);
    }
    
}
