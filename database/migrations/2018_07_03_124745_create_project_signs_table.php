<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectSignsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_signs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('报名人名称');
            $table->string('self_des')->comment('报名人自我描述');

            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->enum('status', [
                    '已报名',
                    '已录用',
                    '已结算',
                    '已拒绝'
                ])->nullable()->default('已报名')->comment('报名状态');
            
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
        Schema::drop('project_signs');
    }
}
