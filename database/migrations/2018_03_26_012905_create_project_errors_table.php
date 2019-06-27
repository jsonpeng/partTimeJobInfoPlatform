<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectErrorsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_errors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type')->comment('投诉类型');
            $table->text('reason')->comment('投诉原因');

            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            //发起人 / 收到的人

            $table->string('status')->nullable()->default('审核中')->comment('审核中/已通过');

            $table->enum('send_type', [
                    '发起',
                    '收到'
                ])->nullable()->default('发起')->comment('用户发起的投诉/用户收到的投诉');

            $table->index(['id', 'created_at']);
            $table->index('project_id');
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
        Schema::drop('project_errors');
    }
}
