<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      
         $schedule->call(function () {
            #自动处理提现订单
            app('zcjy')->autoDealWithDrawLog();
            #自动清理超时的跑腿任务
            app('zcjy')->errandTaskRepo()->dealTimeoutTasks();
         })->everyMinute();

         #每月自动赠送积分
         $schedule->call(function () {
           app('zcjy')->autoGiveCredits();
         })->monthly();
         //monthly
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
