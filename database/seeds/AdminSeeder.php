<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();

        $super_admin_user = Admin::create([
            'name' => 'admin',
            'email' => 'admin@foxmail.com',
            'password'=>Hash::make('zcjy123'),
            'type' => '超级管理员',
            'system_tag'=>1
        ]);
    }
}
