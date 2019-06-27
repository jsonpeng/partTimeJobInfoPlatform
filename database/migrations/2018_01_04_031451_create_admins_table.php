<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) { 
            $table->increments('id'); 
            $table->string('name'); 
            $table->string('email'); 
            $table->string('password'); 
            $table->string('type')->nullable();
            $table->integer('system_tag')->default(0)->comment('系统管理员标记,所有默认添加为0,系统初始化为1');

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
        Schema::drop('admins');
    }
}
