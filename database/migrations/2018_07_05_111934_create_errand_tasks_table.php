<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErrandTasksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errand_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('跑腿任务名称');
            $table->string('remark')->nullable()->comment('备注');
            $table->float('give_price')->nullable()->default(1)->comment('打赏金额');
            $table->string('price_type')->nullable()->default('无需额外费用')->comment('物品金额类型 无需额外费用/需支付物品费用');
            $table->float('item_cost')->nullable()->default(0)->comment('物品费用');
            $table->integer('wait_buyer_enter')->nullalbe()->default(0)->comment('等待买手确认物品费用 0不管1的话等买手确认费用');
            $table->integer('remain_time')->nullable()->default(0)->comment('剩余时间');
            $table->integer('wish_time_hour')->nullable()->comment('期望送达的时间(小时)');
            $table->integer('wish_time_minute')->nullable()->comment('期望送达的时间(分钟)');
            $table->string('mobile')->comment('发布人手机号');
            $table->enum('status', [
                    '已发布',
                    '待收货',
                    '已收货',
                    '已取消'
                ])->nullalbe()->default('已发布')->comment('任务状态');
            $table->string('tem_word1')->comment('模板关键字1');
            $table->string('tem_word2')->comment('模板关键字2');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('城市');
            $table->string('district')->nullable()->comment('区域');
            $table->string('address')->comment('地址');
            $table->float('lat')->comment('纬度');
            $table->float('lon')->comment('经度');
            $table->string('school_name')->comment('学校名称');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('tem_id')->unsigned();
            $table->foreign('tem_id')->references('id')->on('task_tems');

            $table->index(['id', 'created_at']);
            $table->index('user_id');
            $table->index('tem_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('errand_tasks');
    }
}
