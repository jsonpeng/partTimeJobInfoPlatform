<?php

namespace App\Repositories;

use App\Models\CreaditsLog;
use InfyOm\Generator\Common\BaseRepository;
use Carbon\Carbon;

/**
 * Class CreaditsLogRepository
 * @package App\Repositories
 * @version July 9, 2018, 2:56 pm CST
 *
 * @method CreaditsLog findWithoutFail($id, $columns = ['*'])
 * @method CreaditsLog find($id, $columns = ['*'])
 * @method CreaditsLog first($columns = ['*'])
*/
class CreaditsLogRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'num',
        'type',
        'reason',
        'reason_des',
        'project_error_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CreaditsLog::class;
    }

    //用户上个月的负面记录
    public function userNegativeLog($user_id){
        $month_start = Carbon::now()->subMonth()->startOfMonth();
        $month_end = Carbon::now()->subMonth()->endOfMonth();
        return CreaditsLog::where('user_id',$user_id)
        ->whereBetween('created_at',[$month_start,$month_end])
        ->where('type','扣除')
        ->get();
    }

    //用户这个月的获得记录
    public function userActiveLog($user_id){
        $month_start = Carbon::now()->startOfMonth();
        $month_end = Carbon::now()->endOfMonth();
        return CreaditsLog::where('user_id',$user_id)
        ->whereBetween('created_at',[$month_start,$month_end])
        ->where('type','获得')
        ->get();
    }

    //为用户添加一条获得记录
    public function giveUserLog($user){
        $add_credits = empty(getSettingValueByKey('without_add_credits')) ? 0 : getSettingValueByKey('without_add_credits');
        CreaditsLog::create([
            'user_id'   => $user->id,
            'num'       => $add_credits,
            'type'      => '获得',
            'reason'    => '系统赠送',
            'reason_des' => '信誉良好'
        ]);
        $user->update([
            'credits' => $user->credits+$add_credits
        ]);
    }

}
