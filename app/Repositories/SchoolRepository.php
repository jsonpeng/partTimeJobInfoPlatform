<?php

namespace App\Repositories;

use App\Models\School;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SchoolRepository
 * @package App\Repositories
 * @version July 5, 2018, 1:42 pm CST
 *
 * @method School findWithoutFail($id, $columns = ['*'])
 * @method School find($id, $columns = ['*'])
 * @method School first($columns = ['*'])
*/
class SchoolRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'province',
        'city',
        'district',
        'address',
        'lon',
        'lat'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return School::class;
    }
}
