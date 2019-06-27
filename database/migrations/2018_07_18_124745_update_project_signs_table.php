<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateProjectSignsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_signs', function (Blueprint $table) {
            
            if(!Schema::hasColumn('project_signs', 'mobile')){
                $table->string('mobile')->nullalbe()->comment('报名人电话');
            }

            if(!Schema::hasColumn('project_signs', 'company_status')){
                $table->integer('company_status')->nullable()->default(0)->comment('企业查看状态 0可查看 1不可查看');
            }


            if(!Schema::hasColumn('project_signs', 'user_status')){
                $table->integer('user_status')->nullable()->default(0)->comment('用户查看状态 0可查看 1不可查看');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
