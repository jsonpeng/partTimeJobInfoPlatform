<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskTemsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_tems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('模板任务名称');
            $table->string('content')->comment('模板任务内容');
            $table->string('tag')->nullalbe()->default('__')->comment('标记区分符号');

            $table->index(['id', 'created_at']);
            
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
        Schema::drop('task_tems');
    }
}
