<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('media_id',30)->nullable()->comment('mediaId');
            $table->string('original_id',60)->nullable()->comment('原始微信素材id');
            $table->integer('parent_id')->nullable()->default(0)->comment('父id');
            $table->enum('type', ['article','image', 'voice', 'video','text'])->comment('素材类型');
            $table->tinyInteger('is_multi')->nullable()->default(0)->comment('是否是多图文 0 否 1 是'); 
            $table->tinyInteger('can_edited')->nullable()->default(0)->comment('是否可编辑 0 不可 1 可编辑'); 
            $table->string('title',200)->nullable()->comment('标题'); //文字素材无标题
            $table->string('description',360)->nullable()->comment('摘要');
            $table->string('author',24)->nullable()->comment('作者');
            $table->text('content')->nullable()->comment('内容');
            $table->string('cover_media_id')->nullable()->comment('封面 media_id');
            $table->string('cover_url')->nullable()->comment('封面url'); 
            $table->tinyInteger('show_cover_pic')->default(0)->comment('是否显示封面 0 不显示 1 显示');
            $table->tinyInteger('created_from')->nullable()->default(0)->comment('0 微信同步到微易 1自微易同步到微信');
            $table->string('source_url')->nullable()->comment('内容连接资源'); 
            $table->string('content_url')->nullable()->comment('原文链接');

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
        Schema::drop('wechat_materials');
    }
}
