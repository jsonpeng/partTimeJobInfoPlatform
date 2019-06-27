<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCaompaniesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caompanies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('企业名称');
            $table->string('mobile')->comment('电话');
            $table->string('weixin')->nullable()->comment('微信');
            $table->integer('province')->nullable()->default(0)->comment('省');
            $table->integer('city')->nullable()->default(0)->comment('市');
            $table->integer('district')->nullable()->default(0)->comment('区');
            $table->string('detail')->nullable()->comment('详细地址');
            $table->longtext('intro')->nullable()->comment('企业介绍');
            $table->integer('view')->nullable()->default(0)->comment('浏览量');
            $table->integer('collect')->nullable()->default(0)->comment('收藏量');
            $table->string('lat')->nullable()->comment('纬度');
            $table->string('lon')->nullable()->comment('经度');
            $table->string('contact_man')->nullable()->comment('联系人姓名');
            $table->string('status')->nullable()->default('审核中')->comment('审核状态0审核中1通过2不通过');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->index(['id', 'created_at']);
            $table->index('user_id');

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
        Schema::drop('caompanies');
    }
}
