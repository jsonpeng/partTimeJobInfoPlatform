<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->increments('id');
            $table->float('price')->default(0)->comment('价格');
            $table->enum('pay_platform', ['微信支付', '支付宝', '微信(PAYSAPI)', '管理员操作'])->default('微信支付')->comment('支付平台');
            $table->enum('order_pay', ['未支付','已支付'])->default('未支付');
            $table->timestamp('paytime')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('支付时间');
            $table->string('pay_no')->default('')->comment('平台订单号');
            $table->string('out_trade_no')->default('')->comment('商户订单号');

            $table->string('remark')->default('')->nullable()->comment('用户留言');

            $table->enum('type', [
                    '普通',
                    '升级'
                ])->comment('订单类型');

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
        Schema::drop('orders');
    }
}
