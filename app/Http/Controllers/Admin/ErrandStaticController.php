<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;
use Carbon\Carbon;

class ErrandStaticController extends AppBaseController
{

    public function index(Request $request)
    {
        $input=$request->all();
        $start_time = Carbon::today();
        $end_time = null;
       	if(array_key_exists('time_type', $input) && !empty($input['time_type'])){
                if($input['time_type'] == 'day'){
                     $start_time = Carbon::today();
                     $end_time = Carbon::tomorrow();
                }
                elseif($input['time_type'] == 'week'){
                     $start_time = Carbon::today()->startOfWeek();
                     $end_time = Carbon::today()->endOfWeek();
                }
                elseif ($input['time_type'] == 'month') {
                     $start_time = Carbon::today()->startOfMonth();
                     $end_time = Carbon::today()->endOfMonth();
                }
                elseif ($input['time_type'] == 'custom'){

                    if(array_key_exists('time_start',$input) && !empty($input['time_start'])){
                        $start_time = $input['time_start'];
                    }

                   if(array_key_exists('time_end',$input) && !empty($input['time_end'])){
                        $end_time = $input['time_end'];
                    }

                }
        }#默认当天
        else{
               $start_time = Carbon::today();
               $end_time = Carbon::tomorrow();
        }

        $statics = app('zcjy')->staticsErrand($start_time,$end_time);
        
        return view('admin.statics.index')
            ->with('input',$input)
            ->with('statics',$statics);
    }

}
