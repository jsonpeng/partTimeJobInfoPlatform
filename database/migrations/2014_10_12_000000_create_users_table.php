<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('head_image')->nullable()->comment('头像');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('mobile')->nullable()->comment('手机');
            $table->string('openid')->nullable()->comment('微信OPEN ID');
            $table->string('unionid')->nullable()->comment('公众平台ID');
            $table->integer('credits')->default(0)->comment('用户信誉积分');
            $table->float('user_money')->default(0)->comment('用户余额');

            $table->timestamp('last_login')->nullable()->comment('最后登录日期');
            $table->string('last_ip')->nullable()->comment('最后登录IP');
            $table->string('province')->nullable()->default('')->comment('省');
            $table->string('city')->nullable()->default('')->comment('市');
            $table->string('district')->nullable()->default('')->comment('区');

            $table->enum('type', [
                    '个人',
                    '企业'
                ])->nullable()->default('个人')->comment('个人用户/企业用户');
            $table->string('school')->nullable()->comment('所在学校');
            
            $table->index(['id', 'created_at']);
       
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
