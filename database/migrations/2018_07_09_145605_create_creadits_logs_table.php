<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreaditsLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creadits_logs', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('num')->comment('积分数量');
            $table->enum('type',['扣除','获得'])->nullable()->default('扣除')->comment('类型');
            $table->string('reason')->nullable()->comment('理由');
            $table->string('reason_des')->nullable()->comment('理由描述');
            $table->integer('project_error_id')->nullable()->default(0)->comment('纠错id');

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
        Schema::drop('creadits_logs');
    }
}
