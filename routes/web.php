<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'swagger'], function () {
    Route::get('json', 'API\SwaggerController@getJSON');
});

Route::any('/wechat', 'Admin\Wechat\WechatController@serve');
//地图选择
Route::get('map','Admin\CitiesController@map');
//刷新缓存
Route::post('/clearCache','Controller@clearCache');
//测试
Route::get('test','Controller@test');
/**
 * ajax接口
 */
Route::group(['prefix' => 'ajax','namespace' => 'Admin'], function () {
	 //直接根据id返回市区县地区列表
	Route::post('cities/getAjaxSelect/{id}','CitiesController@CitiesAjaxSelect');
	//根据地域返回对应的城市列表
	Route::post('diyu/getAjaxSelect/{diyu}','CitiesController@DiyuCitiesAjaxSelect');
});
//开启认证
Auth::routes();
/**
 * 认证路由
 */
Route::group([ 'prefix' => 'zcjy', 'namespace' => 'Admin\Auth'], function () {
	Route::get('login', 'AdminAuthController@showLoginForm');
	Route::post('login', 'AdminAuthController@login');
	Route::get('logout', 'AdminAuthController@logout');
});

Route::group([ 'middleware' => ['auth.admin:admin'], 'prefix' => 'zcjy', 'namespace' => 'Admin'], function () {
	//首页
	Route::get('/', 'SettingController@setting');
	
	Route::get('statics', 'ErrandStaticController@index')->name('statics.errand');
	
	/**
	 * 用户会员管理
	 */
	Route::group(['namespace' => 'User'], function () {

	//会员管理
	Route::resource('userLevels', 'UserLevelController');
	//用户管理
	Route::get('users','UserLevelController@index')->name('users.index');
	//编辑用户
	Route::get('users/{user_id}/edit','UserLevelController@userEdit')->name('users.edit');
	//更新用户
	Route::patch('users/{user_id}/update','UserLevelController@userUpdate')->name('users.update');
	//删除用户
	Route::delete('users/{user_id}/delete','UserLevelController@userDelete')->name('users.destroy');
	//恢复会员
	Route::delete('userLevels/{id}/recorver','UserLevelController@recorver')->name('userLevels.recorver');

	});

	//任务模板管理
	Route::resource('taskTems', 'TaskTemController');
	//项目管理
	Route::resource('projects', 'ProjectController');
	//行业类型管理
	Route::resource('industries', 'IndustryController');
	//项目报名列表
	Route::resource('projectSigns','ProjectSignController');

	//跑腿任务
	Route::resource('errandTasks', 'ErrandTaskController');
	//学校管理
	Route::resource('schools', 'SchoolController');
	//意见反馈
	Route::resource('feedBack', 'FeedBackController');
	//校购投诉列表
	Route::resource('errandErrors', 'ErrandErrorController');
	//用户提现记录
	Route::resource('withDrawalLogs', 'WithDrawalLogController');

	/**
	 * 网站设置
	 */
	Route::get('settings/setting', 'SettingController@setting')->name('settings.setting');
	Route::post('settings/setting', 'SettingController@update')->name('settings.setting.update');
	//修改密码
	Route::get('setting/edit_pwd','SettingController@edit_pwd')->name('settings.edit_pwd');
    Route::post('setting/edit_pwd/{id}','SettingController@edit_pwd_api')->name('settings.pwd_update');
	//订单
	Route::resource('orders', 'OrderController');

	//地区设置
    Route::resource('cities','CitiesController');

    //根据pid查看到地区列表
    Route::get('cities/pid/{pid}','CitiesController@ChildList')->name('cities.child.index');
    //为指定父级城市添加地区页面
    Route::get('cities/pid/{pid}/add','CitiesController@ChildCreate')->name('cities.child.create');
    //省市区三级选择
    Route::get('cities/frame/select','CitiesController@CitiesSelectFrame')->name('cities.select.frame');
  	//企业管理
    Route::resource('caompanies', 'CompanyController');
    //企业纠错信息
	 Route::resource('companyErrors', 'ErrorController');
	
	 //微信公众号功能
    Route::group([ 'prefix' => 'wechat'], function () {
    	Route::group([ 'prefix' => 'menu'], function () {
			Route::get('menu', 'Wechat\MenuController@getIndex')->name('wechat.menu');
			Route::get('lists', 'Wechat\MenuController@getLists');
			Route::get('create', 'Wechat\MenuController@getCreate');
			Route::get('delete/{id}', 'Wechat\MenuController@getDelete');
			Route::get('update/{id}', 'Wechat\MenuController@getUpdate');
			Route::get('single/{id}', 'Wechat\MenuController@getSingle');
			Route::post('store', 'Wechat\MenuController@postStore');
			Route::get('update-menu-event', 'Wechat\MenuController@getUpdateMenuEvent');
		});

		Route::group([ 'prefix' => 'reply'], function () {
			Route::get('/', 'Wechat\ReplyController@getIndex');
			Route::get('index', 'Wechat\ReplyController@getIndex')->name('wechat.reply');
			Route::get('rpl-follow', 'Wechat\ReplyController@getRplFollow');
			Route::get('rpl-no-match', 'Wechat\ReplyController@getRplNoMatch');
			Route::get('follow-reply', 'Wechat\ReplyController@getFollowReply');
			Route::get('no-match-reply', 'Wechat\ReplyController@getNoMatchReply');
			Route::get('lists', 'Wechat\ReplyController@getLists');
			Route::get('save-event-reply', 'Wechat\ReplyController@getSaveEventReply');
			Route::post('store', 'Wechat\ReplyController@postStore');
			Route::get('edit/{id}', 'Wechat\ReplyController@getEdit');
			Route::post('update/{id}', 'Wechat\ReplyController@postUpdate');
			Route::get('delete/{id}', 'Wechat\ReplyController@getDelete');
			Route::get('single/{id}', 'Wechat\ReplyController@getSingle');
			Route::get('delete-event/{type}', 'Wechat\ReplyController@getDeleteEvent');
		});

		Route::group([ 'prefix' => 'material'], function () {
			Route::get('by-event-key/{key}', 'Wechat\MaterialController@getByEventKey');
		});
	});
	

});



//Route::resource('creaditsLogs', 'CreaditsLogController');





// Route::resource('errandImages', 'ErrandImageController');

// Route::resource('refundLogs', 'RefundLogController');