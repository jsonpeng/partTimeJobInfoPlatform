<?php

use Illuminate\Database\Seeder;
use App\User;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();


        $user2 = User::create([
            'name' => '管理员测试用户',
            'nickname' => '管理员',
            'mobile' => '18717160163',
            'openid' => 'odh7zsgI75iT8FRh0fGlSojc9PWM',
            'type'  => '企业'
        ]);
    }
}
