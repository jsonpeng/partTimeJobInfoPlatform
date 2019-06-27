<?php

namespace App\Repositories;

use App\Models\ProjectError;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class CompanyErrorRepository
 * @package App\Repositories
 * @version March 26, 2018, 1:29 am UTC
 *
 * @method CompanyError findWithoutFail($id, $columns = ['*'])
 * @method CompanyError find($id, $columns = ['*'])
 * @method CompanyError first($columns = ['*'])
*/
class ProjectErrorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'reason',
        'company_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProjectError::class;
    }

    public function attachInfo($errors,$user=null){
        foreach ($errors as $key => $value) {
           $value['user'] = $value->user()->first();
           $value['project'] = $value->project()->first();
           $value['project_user'] =  $value['project']->user()->first();
           $value['company'] = $value['project_user']->caompany()->first();
           if($value->send_type == '发起'){
                $value['other_user'] = user_by_id($value->other_user_id);
            }
            else{
                $value['other_user'] = user_by_id($value->other_user_id);
            }
        }
        return $errors;
    }
}
