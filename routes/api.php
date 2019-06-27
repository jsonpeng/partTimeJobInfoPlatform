<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	$api->group(['middleware' => 'api'], function($api){
		
		#测试接口
		$api->get('login_id/{id}','App\Http\Controllers\API\CommonController@loginId');

		//小程序公用部分接口
		$api->group(['prefix' => 'mini_program'], function ($api) {
			#用户登录
			$api->get('login', 'App\Http\Controllers\API\CommonController@loginMiniprogram');

			$api->group(['middleware' => 'zcjy_api_user'], function ($api) {

				### 图片上传
				$api->post('upload_images','App\Http\Controllers\API\CommonController@uploadImage');
				
				### 获取用户信息
				$api->get('me', 'App\Http\Controllers\API\CommonController@userInfo');

				### 发起意见反馈 
				$api->get('publish_feedback', 'App\Http\Controllers\API\CommonController@publishFeedBack');

				### 用户的信用记录
				$api->get('credit_logs','App\Http\Controllers\API\CommonController@userCreditsLog');

				### 收到的/发起的投诉
				$api->get('publish_and_receive_error/{platform_type?}','App\Http\Controllers\API\CommonController@publishAndReceiveError');

				### 更改绑定手机号
				$api->get('change_mobile','App\Http\Controllers\API\CommonController@changeMobile');
				
			});
		});

		//兼职
		$api->group(['prefix' => 'part_job'], function ($api) {

			#根据省份名称模糊获取id
			$api->get('get_province_id_by_name','App\Http\Controllers\API\PartJobController@getProvinceIdByName');
			#省份列表
			$api->get('provinces_list','App\Http\Controllers\API\PartJobController@getBasicProvince');
			#根据上一级id获取对应的子列表
			$api->get('cities_list','App\Http\Controllers\API\PartJobController@getCitiesList');
			#获取所有兼职类型
			$api->get('type_all','App\Http\Controllers\API\PartJobController@getAllJianZhiType');
			#获取兼职列表
			$api->get('list_all','App\Http\Controllers\API\PartJobController@getJianZhiList');
		
			/**
			 * 需要登录认证后使用
			 */
			$api->group(['middleware' => 'zcjy_api_user'], function ($api) {

				#获取兼职详情
				$api->get('detail/{id}','App\Http\Controllers\API\PartJobController@getJianZhiDetail');

				#个人中心的报名列表
				$api->get('auth_signs','App\Http\Controllers\API\PartJobController@userProjectSigns');
				/**
				 * 高于系统信誉积分可以使用
				 */
				$api->group(['middleware' => 'zcjy_api_credits_limit'],function($api) {

					#用户确认收款
					$api->get('enter_project_price/{project_sign_id}','App\Http\Controllers\API\PartJobController@userEnterPorjectPrice');
					#发起兼职报名
					$api->get('auth_publish_sign','App\Http\Controllers\API\PartJobController@publishProjectSign');
					#申请为企业用户
					$api->get('auth_apply_company','App\Http\Controllers\API\PartJobController@applyForCompanyUser');
					#用户投诉兼职
					$api->get('auth_error_company/{project_id}','App\Http\Controllers\API\PartJobController@userErrorPorject');
					#用户删除自己的报名记录
					$api->get('auth_del_sign/{id}','App\Http\Controllers\API\PartJobController@userDelSelfSigns');

				});

				/**
				 * 企业用户才可以操作
				 */
				$api->group(['middleware' => 'zcjy_api_company_user'], function ($api) {

					#企业用户发布的招聘信息
					$api->get('auth_publish_companys','App\Http\Controllers\API\PartJobController@companyPublishProject');
					
					#企业用户获取对应兼职招聘的报名名单
					$api->get('publish_project_sign/{project_id}','App\Http\Controllers\API\PartJobController@companyPublishProjectSign');
					/**
					 * 高于系统信誉积分可以使用
					 */
					$api->group(['middleware'=> 'zcjy_api_credits_limit'],function($api) {

						#用户完善企业信息
						$api->get('auth_complete_company_info/{id}','App\Http\Controllers\API\PartJobController@completeCompanyInfo');
						#企业用户更新单个招聘人员信息
						$api->get('auth_update_project_sign/{id}','App\Http\Controllers\API\PartJobController@companyUpdateProjectSign');

						#企业用户发布招聘兼职信息
						$api->get('auth_publish_project','App\Http\Controllers\API\PartJobController@companyActionPublishProject');
						#企业投诉个人
						$api->get('company_error_auth/{user_id}','App\Http\Controllers\API\PartJobController@companyErrorUser');
						#企业用户撤销兼职
						$api->get('company_cancle_project/{id}','App\Http\Controllers\API\PartJobController@companyCancleProject');
						#企业用户删除自己发布的兼职
						$api->get('company_del_project/{id}','App\Http\Controllers\API\PartJobController@companyDelProject');
						#企业用户删除用户的报名记录
						$api->get('company_del_sign/{id}','App\Http\Controllers\API\PartJobController@compnayDelUserSigns');

					});

				});
			});
		});
		
		//跑腿
		$api->group(['prefix' => 'errand'], function ($api) {

			#所有的任务模板列表
			$api->get('all_tems','App\Http\Controllers\API\ErrandController@allTems');
			#对应学校的跑腿任务
			$api->get('school_tasks','App\Http\Controllers\API\ErrandController@schoolTasks');
			#跑腿任务详情
			$api->get('task_detail/{id}','App\Http\Controllers\API\ErrandController@errandTaskDetail');

			/**
			 * 需要登录认证后使用
			 */
			$api->group(['middleware' => 'zcjy_api_user'], function ($api) {

					#添加选择学校
					$api->get('select_and_add_school','App\Http\Controllers\API\ErrandController@selectAndAddSchool');
					#发布者/买手的任务列表
					$api->get('tasks/{type?}','App\Http\Controllers\API\ErrandController@publisherTasks');
					#我的钱包 收入/支出
					$api->get('my_task_log','App\Http\Controllers\API\ErrandController@myPublishAndErrandLog');
					/**
					 * 高于系统信誉积分可以使用
					 */
					$api->group(['middleware' => 'zcjy_api_credits_limit'],function($api) {

						#发起提现
						$api->get('publish_withdraw','App\Http\Controllers\API\ErrandController@publishWithDraw');
						#发布跑腿任务
						$api->get('publish_task','App\Http\Controllers\API\ErrandController@publishErrandTask');
						#发布人删除任务
						$api->get('del_task/{id}','App\Http\Controllers\API\ErrandController@delErrandTask');
						#发布人取消任务
						$api->get('cancle_task/{id}','App\Http\Controllers\API\ErrandController@cancleErrandTask');
						#发布人确认收货
						$api->get('enter_receive_task/{id}','App\Http\Controllers\API\ErrandController@publishManEnterTaskRec');
						#买手接单
						$api->get('take_order_task/{id}','App\Http\Controllers\API\ErrandController@errandTakeOrderTask');
						#买手确认送达
						$api->get('enter_arrive_task/{id}','App\Http\Controllers\API\ErrandController@buyerEnterTaskArrive');
						#买手确认物品费用
						$api->get('enter_item_cost_task/{id}','App\Http\Controllers\API\ErrandController@errandEnterItemCostTask');
						#发布者发起任务支付
						$api->get('pay_task/{id}','App\Http\Controllers\API\ErrandController@payErrandTask');
						#投诉发起者/买手
						$api->get('error_task/{id}/{type?}','App\Http\Controllers\API\ErrandController@errorErrandTask');
						#买手取消订单
						$api->get('cancle_order_task/{id}','App\Http\Controllers\API\ErrandController@errandCancleOrderTask');

				});
			});
		});
	});
});