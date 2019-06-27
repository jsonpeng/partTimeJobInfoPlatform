<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_image', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('caompanies');

            $table->index(['id', 'created_at']);
            $table->index('company_id');
            $table->timestamps();
           // $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_image');
    }
}
