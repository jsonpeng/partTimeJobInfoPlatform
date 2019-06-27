<?php

use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();

        Setting::create(['name' => 'name', 'value' => '芸来商城', 'group' => 'basic', 'des' => '店铺名称']);
        Setting::create(['name' => 'icp', 'value' => '', 'group' => 'basic', 'des' => 'ICP备案信息']);
        Setting::create(['name' => 'logo', 'value' => '', 'group' => 'basic', 'des' => 'LOGO']);
        Setting::create(['name' => 'seo_title', 'value' => '', 'group' => 'basic', 'des' => '网站标题']);
        Setting::create(['name' => 'seo_des', 'value' => '', 'group' => 'basic', 'des' => '网站描述']);
        Setting::create(['name' => 'seo_keywords', 'value' => '', 'group' => 'basic', 'des' => '网站关键字']);
        Setting::create(['name' => 'service_tel', 'value' => '', 'group' => 'basic', 'des' => '服务电话']);
        Setting::create(['name' => 'weixin', 'value' => '', 'group' => 'basic', 'des' => '微信公众号']);
        Setting::create(['name' => 'inventory_default', 'value' => '20', 'group' => 'basic', 'des' => '默认库存']);
        Setting::create(['name' => 'inventory_warn', 'value' => '3', 'group' => 'basic', 'des' => '库存预警数']);
        Setting::create(['name' => 'freight_free_limit', 'value' => '200', 'group' => 'basic', 'des' => '全场满多少免运费']);
        Setting::create(['name' => 'withdraw_limit', 'value' => '200', 'group' => 'basic', 'des' => '满多少才能提现']);
        Setting::create(['name' => 'withdraw_min', 'value' => '200', 'group' => 'basic', 'des' => '最少提现额度']);
        Setting::create(['name' => 'withdraw_day_max_num', 'value' => '3', 'group' => 'basic', 'des' => '单日最多提现多少次']);
        Setting::create(['name' => 'share_qrcode_img', 'value' => '', 'group' => 'basic', 'des' => '分享二维码底图']);
        Setting::create(['name' => 'account_bind', 'value' => '否', 'group' => 'basic', 'des' => '第三方登录，需绑定账号']);


        Setting::create(['name' => 'sms_platform', 'value' => '阿里云', 'group' => 'sms', 'des' => '短信平台']);
        Setting::create(['name' => 'sms_appkey', 'value' => '', 'group' => 'sms', 'des' => '短信平台[appkey]']);
        Setting::create(['name' => 'sms_secretKey', 'value' => '', 'group' => 'sms', 'des' => '短信平台[secretKey]']);
        Setting::create(['name' => 'sms_send_register', 'value' => '否', 'group' => 'sms', 'des' => '用户注册时是否发送短信']);
        Setting::create(['name' => 'sms_send_password', 'value' => '否', 'group' => 'sms', 'des' => '用户找回密码时是否发送短信']);
        Setting::create(['name' => 'sms_send_account_check', 'value' => '否', 'group' => 'sms', 'des' => '身份验证时是否发送短信']);
        Setting::create(['name' => 'sms_send_order', 'value' => '否', 'group' => 'sms', 'des' => '用户下单时是否发送短信给商家']);
        Setting::create(['name' => 'sms_send_pay', 'value' => '否', 'group' => 'sms', 'des' => '客户支付时是否发短信给商家']);
        Setting::create(['name' => 'sms_send_deliver', 'value' => '否', 'group' => 'sms', 'des' => '商家发货时是否给客户发短信']);
        
        Setting::create(['name' => 'credits_alias', 'value' => '积分', 'group' => 'credits', 'des' => '积分别名']);
        Setting::create(['name' => 'register_credits', 'value' => '0', 'group' => 'credits', 'des' => '注册赠送积分']);
        Setting::create(['name' => 'invite_credits', 'value' => '0', 'group' => 'credits', 'des' => '邀请人获赠积分']);
        Setting::create(['name' => 'credits_switch', 'value' => '是', 'group' => 'credits', 'des' => '积分抵扣开关']);
        Setting::create(['name' => 'credits_min', 'value' => '100', 'group' => 'credits', 'des' => '最低使用积分条件']);
        Setting::create(['name' => 'credits_max', 'value' => '10', 'group' => 'credits', 'des' => '最高抵扣比率%']);
        Setting::create(['name' => 'consume_credits', 'value' => '0', 'group' => 'credits', 'des' => '购物送积分比例']);
        Setting::create(['name' => 'credits_rate', 'value' => '10', 'group' => 'credits', 'des' => '1元能兑换多少积分']);

        Setting::create(['name' => 'auto_complete', 'value' => '7', 'group' => 'order', 'des' => '自动确认收货时间']);
        Setting::create(['name' => 'after_sale_time', 'value' => '15', 'group' => 'order', 'des' => '多少天内可申请售后']);
        Setting::create(['name' => 'inventory_consume', 'value' => '下单成功', 'group' => 'order', 'des' => '减库存的时机  下单成功  支付成功']);
        Setting::create(['name' => 'user_level_switch', 'value' => '不开启', 'group' => 'order', 'des' => '开启 不开启 是否开启会员等级功能，不同的会员等级可以享受不同的折扣']);
        Setting::create(['name'=>'order_expire_time','value'=>'','group'=>'order','des'=>'订单支付超时时间(单位为分钟，0表示永不过期)']);

        Setting::create(['name' => 'email_host', 'value' => '', 'group' => 'email', 'des' => 'smtp host']);
        Setting::create(['name' => 'email_port', 'value' => '', 'group' => 'email', 'des' => 'smtp port']);
        Setting::create(['name' => 'email_username', 'value' => '', 'group' => 'email', 'des' => '邮箱登录名']);
        Setting::create(['name' => 'email_password', 'value' => '', 'group' => 'email', 'des' => '邮箱密码']);
        Setting::create(['name' => 'email_encrypt', 'value' => '', 'group' => 'email', 'des' => '加密设置']);
        Setting::create(['name' => 'order_notify_email', 'value' => '', 'group' => 'email', 'des' => '订单提示邮箱']);

        Setting::create(['name' => 'distribution', 'value' => '否', 'group' => 'distribution', 'des' => '是否开启分销 ']);
        Setting::create(['name' => 'distribution_condition', 'value' => '是', 'group' => 'distribution', 'des' => '是否需购买商品才能成为分销']);
        Setting::create(['name' => 'distribution_type', 'value' => '订单', 'group' => 'distribution', 'des' => '按"商品"提成  按"订单"金额提成']);
        Setting::create(['name' => 'distribution_percent', 'value' => '0', 'group' => 'distribution', 'des' => '订单金额提成比例']);
        Setting::create(['name' => 'distribution_selft', 'value' => '0', 'group' => 'distribution', 'des' => '购买者提成点']);
        Setting::create(['name' => 'distribution_level1_name', 'value' => '0', 'group' => 'distribution', 'des' => '一级分销商名称']);
        Setting::create(['name' => 'distribution_level1_percent', 'value' => '0', 'group' => 'distribution', 'des' => '一级分销商提成比例']);
        Setting::create(['name' => 'distribution_level2_name', 'value' => '0', 'group' => 'distribution', 'des' => '二级分销商名称']);
        Setting::create(['name' => 'distribution_level2_percent', 'value' => '0', 'group' => 'distribution', 'des' => '二级分销商提成比例']);
        Setting::create(['name' => 'distribution_level3_name', 'value' => '0', 'group' => 'distribution', 'des' => '三级分销商名称']);
        Setting::create(['name' => 'distribution_level3_percent', 'value' => '0', 'group' => 'distribution', 'des' => '三级分销商提成比例']);

        Setting::create(['name' => 'feie_sn', 'value' => '', 'group' => 'printer', 'des' => '飞蛾小票打印机SN']);
        Setting::create(['name' => 'feie_user', 'value' => '', 'group' => 'printer', 'des' => '飞蛾小票打印机USER']);
        Setting::create(['name' => 'feie_ukey', 'value' => '', 'group' => 'printer', 'des' => '飞蛾小票打印机UKEY']);

        Setting::create(['name' => 'records_per_page', 'value' => '15', 'group' => 'other', 'des' => '每页显示信息条目']);
        Setting::create(['name' => 'category_level', 'value' => '3', 'group' => 'other', 'des' => '分类信息等级数']);

    }
}


