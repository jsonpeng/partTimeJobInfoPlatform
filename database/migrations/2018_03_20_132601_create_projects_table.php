<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('兼职名称');
            $table->string('mobile')->nullable()->comment('联系电话');
            $table->string('weixin')->nullable()->comment('微信或QQ');
            $table->float('money')->comment('基本工资金额');
            $table->enum('time_type', [
                    '日',
                    '周',
                    '月'
                ])->comment('结算周期');

            $table->enum('type', [
                    '个人',
                    '企业',
                    '管理员'
                ])->comment('个人发布/企业发布/后台管理员发布');

            $table->enum('length_type',[
                '短期兼职','中期兼职','长期兼职','实习'
            ])->comment('时间类型');

            $table->enum('sex_need', [
                    '男',
                    '女',
                    '不限'
                ])->comment('性别要求');

            $table->integer('province')->nullable()->default(0)->comment('省');
            $table->integer('city')->nullable()->default(0)->comment('市');
            $table->integer('district')->nullable()->default(0)->comment('区');
            $table->string('address')->comment('地址');
            $table->integer('rec_num')->comment('招聘人数');
            $table->longtext('detail')->comment('工作内容');
            
            $table->enum('status', [
                    '审核中',
                    '通过',
                    '不通过',
                    '已撤销'
                ])->comment('审核状态');
            
            $table->enum('pay_status', [
                    '待付款',
                    '已付款'
                ])->comment('付款状态');

            $table->integer('view')->nullable()->default(0)->comment('浏览量');
            $table->integer('collections')->nullable()->default(0)->comment('收藏量');
            // 项目提交人
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamp('start_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('开始时间');
            $table->timestamp('end_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('结束时间');
            $table->string('morning_start_time')->comment('上午开始时间');
            $table->string('morning_end_time')->comment('上午结束时间');
            $table->string('afternoon_start_time')->comment('下午开始时间');
            $table->string('afternoon_end_time')->comment('下午结束时间');
            //企业id
            $table->integer('caompanie_id')->nullable()->comment('企业id');
            $table->timestamps();
            $table->softDeletes();
        });

        //industry_project
        Schema::create('industry_project', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('industry_id')->unsigned();
            $table->foreign('industry_id')->references('id')->on('industries');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');

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
        Schema::drop('industry_project');
        Schema::drop('projects');
    }
}
