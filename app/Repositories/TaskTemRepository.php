<?php

namespace App\Repositories;

use App\Models\TaskTem;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class TaskTemRepository
 * @package App\Repositories
 * @version July 2, 2018, 11:02 am CST
 *
 * @method TaskTem findWithoutFail($id, $columns = ['*'])
 * @method TaskTem find($id, $columns = ['*'])
 * @method TaskTem first($columns = ['*'])
*/
class TaskTemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'content',
        'tag'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TaskTem::class;
    }
}
