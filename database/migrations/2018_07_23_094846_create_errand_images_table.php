<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateErrandImagesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errand_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->nullable()->comment('图片地址');
         
            $table->integer('errand_task_id')->unsigned();
            $table->foreign('errand_task_id')->references('id')->on('errand_tasks');

            $table->index(['id', 'created_at']);
            $table->index('errand_task_id');

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
        Schema::drop('errand_images');
    }
}
