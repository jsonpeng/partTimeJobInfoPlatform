<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            
            if(!Schema::hasColumn('projects', 'caompanie_name')){
                $table->string('caompanie_name')->nullable()->comment('公司名称');
            }

            if(!Schema::hasColumn('projects', 'time_set')){
                $table->enum('time_set', [
                    '小时',
                    '天',
                    '周',
                    '月'
                ])->nullable()->default('天')->comment('兼职时间(金额)');
            }

            if(!Schema::hasColumn('projects', 'is_top')){
                $table->integer('is_top')->nullable()->default(0)->comment('0不置顶1置顶');
            }

            if(!Schema::hasColumn('projects', 'company_status')){
                $table->integer('company_status')->nullable()->default(0)->comment('企业查看状态 0可查看 1不可查看');
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
