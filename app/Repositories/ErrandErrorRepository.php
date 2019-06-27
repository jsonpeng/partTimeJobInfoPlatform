<?php

namespace App\Repositories;

use App\Models\ErrandError;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ErrandErrorRepository
 * @package App\Repositories
 * @version July 10, 2018, 2:49 pm CST
 *
 * @method ErrandError findWithoutFail($id, $columns = ['*'])
 * @method ErrandError find($id, $columns = ['*'])
 * @method ErrandError first($columns = ['*'])
*/
class ErrandErrorRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'reason',
        'errand_id',
        'user_id',
        'task_id',
        'status',
        'send_type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ErrandError::class;
    }

    public function attachInfo($errors,$user=null){
        foreach ($errors as $key => $value) {
          if($value->send_type == '收到'){
             $task =$value->task()->first();
             if(!empty($task)){
                if($user->id == $task->user_id ){
                  $value['publisher'] = user_by_id($task->errand_id);
                }
                else{
                  $value['publisher'] = user_by_id($task->user_id);
                }
             }
          }
          else{
             $value['publisher'] = $value->publisher()->first();
          }
          $value['errander'] = $value->errander()->first();
          $value['task'] = $value->task()->first();
        }
        return $errors;
    }
}
