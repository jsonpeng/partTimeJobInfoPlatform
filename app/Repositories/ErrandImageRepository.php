<?php

namespace App\Repositories;

use App\Models\ErrandImage;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ErrandImageRepository
 * @package App\Repositories
 * @version July 23, 2018, 9:48 am CST
 *
 * @method ErrandImage findWithoutFail($id, $columns = ['*'])
 * @method ErrandImage find($id, $columns = ['*'])
 * @method ErrandImage first($columns = ['*'])
*/
class ErrandImageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'url',
        'errand_task_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ErrandImage::class;
    }
}
