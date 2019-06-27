<?php

namespace App\Repositories;

use App\Models\RefundLog;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class RefundLogRepository
 * @package App\Repositories
 * @version July 27, 2018, 6:28 pm CST
 *
 * @method RefundLog findWithoutFail($id, $columns = ['*'])
 * @method RefundLog find($id, $columns = ['*'])
 * @method RefundLog first($columns = ['*'])
*/
class RefundLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'price',
        'reason',
        'content'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RefundLog::class;
    }
}
