<?php

namespace App\Repositories;

use App\Models\WithDrawalLog;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class WithDrawalLogRepository
 * @package App\Repositories
 * @version July 12, 2018, 11:10 am CST
 *
 * @method WithDrawalLog findWithoutFail($id, $columns = ['*'])
 * @method WithDrawalLog find($id, $columns = ['*'])
 * @method WithDrawalLog first($columns = ['*'])
*/
class WithDrawalLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'price',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WithDrawalLog::class;
    }
}
