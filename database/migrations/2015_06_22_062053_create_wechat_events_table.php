<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key',128)->comment('事件名称');
            $table->enum('type', ['addon','material'])->comment('事件类型');
            $table->string('value',30)->comment('触发值');

            $table->index(['id', 'created_at']);

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
        Schema::drop('wechat_events');
    }
}
