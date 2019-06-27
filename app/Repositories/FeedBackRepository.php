<?php

namespace App\Repositories;

use App\Models\FeedBack;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class FeedBackRepository
 * @package App\Repositories
 * @version July 6, 2018, 3:37 pm CST
 *
 * @method FeedBack findWithoutFail($id, $columns = ['*'])
 * @method FeedBack find($id, $columns = ['*'])
 * @method FeedBack first($columns = ['*'])
*/
class FeedBackRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'email',
        'content',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FeedBack::class;
    }
}
