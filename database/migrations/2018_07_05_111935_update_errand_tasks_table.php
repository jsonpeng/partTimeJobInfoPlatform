<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateErrandTasksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        Schema::table('errand_tasks', function (Blueprint $table) {
            if(!Schema::hasColumn('errand_tasks', 'errand_status')){
                $table->enum('errand_status',[
                        '待送达',
                        '确认送达',
                        '已收款'
                    ])->nullable()->default('待送达')->comment('买手状态');
            }
            if(!Schema::hasColumn('errand_tasks', 'errand_id')){
                $table->integer('errand_id')->nullable()->default(0)->comment('买手id');
            }
            if(!Schema::hasColumn('errand_tasks', 'pay_status')){
                $table->enum('pay_status',[
                            '未支付',
                            '已支付'
                        ])->nullable()->default('未支付')->comment('支付状态');
            }
            if(!Schema::hasColumn('errand_tasks', 'remain_time_hour')){
                $table->integer('remain_time_hour')->nullable()->default(0)->comment('剩余时间(小时)');
            }
            if(!Schema::hasColumn('errand_tasks', 'remain_time_min')){
                $table->integer('remain_time_min')->nullable()->default(0)->comment('剩余时间(分钟)');
            }
            if(!Schema::hasColumn('errand_tasks', 'current_remain_time')){
                $table->timestamp('current_remain_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('实际剩余时间');
            }
            if(!Schema::hasColumn('errand_tasks', 'current_wish_time')){
                $table->timestamp('current_wish_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('实际期望时间');
            }
            if(!Schema::hasColumn('errand_tasks', 'pay_price')){
                $table->float('pay_price')->nullable()->default(0)->comment('发布者支付金额');
            }
            if(!Schema::hasColumn('errand_tasks', 'platform_price')){
                $table->float('platform_price')->nullable()->default(0)->comment('平台提取佣金');
            }
            if(!Schema::hasColumn('errand_tasks', 'errander_get_price')){
                $table->float('errander_get_price')->nullable()->default(0)->comment('买手应得金额');
            }
            if(!Schema::hasColumn('errand_tasks', 'out_trade_no')){
                $table->string('out_trade_no')->nullable()->default('')->comment('商户订单号');
            }
            if(!Schema::hasColumn('errand_tasks', 'wish_time_hour')){
                $table->integer('wish_time_hour')->nullalbe()->change();
            }
            if(!Schema::hasColumn('errand_tasks', 'wish_time_minute')){
                $table->integer('wish_time_minute')->nullalbe()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
