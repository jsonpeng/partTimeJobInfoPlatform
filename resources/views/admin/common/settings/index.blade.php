@extends('layouts.app')


@section('content')
<section class="content pdall0-xs pt10-xs">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li>
                <a href="javascript:;">
                    <span style="font-weight: bold;">通用设置</span>
                </a>
            </li>
            <li class="active">
                <a href="#tab_1" data-toggle="tab">系统设置</a>
            </li>

       {{--      <li>
                <a href="#tab_2" data-toggle="tab">企业设置</a>
            </li>

            <li>
                <a href="#tab_6" data-toggle="tab">项目金额设置</a>
            </li> --}}

            <li>
                <a href="#tab_8" data-toggle="tab">其他设置</a>
            </li>

      {{--        <li>
                <a href="#tab_9" data-toggle="tab">分享背景图片设置</a>
            </li> --}}
   
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="box box-info form">
                <!-- form start -->
                <div class="box-body">
                    <form class="form-horizontal" id="form1">
                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">系统名称</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" maxlength="60" placeholder="系统名称" value="{{ getSettingValueByKey('name') }}"></div>
                        </div>
             
                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">新注册用户初始信誉积分</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="user_basic_credits" maxlength="60" placeholder="用户初始信誉积分" value="{{ getSettingValueByKey('user_basic_credits') }}">
                                    <span class="input-group-addon">积分</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">用户信誉积分低于多少积分无法使用系统</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="user_min_credits" maxlength="60" placeholder="用户信誉积分低于多少积分无法使用系统" value="{{ getSettingValueByKey('user_min_credits') }}">
                                     <span class="input-group-addon">积分</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">单次投诉扣除信誉积分</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="error_del_credits" maxlength="60" placeholder="单次投诉扣除信誉积分" value="{{ getSettingValueByKey('error_del_credits') }}">
                                    <span class="input-group-addon">积分</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">一个月内没收到投诉增长积分</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="without_add_credits" maxlength="60" placeholder="一个月内没收到投诉增长积分" value="{{ getSettingValueByKey('without_add_credits') }}">
                                    <span class="input-group-addon">积分</span>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">校购跑腿单次任务最低打赏金额</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="errand_min_price" maxlength="60" placeholder="校购跑腿单次任务最低打赏金额" value="{{ getSettingValueByKey('errand_min_price') }}">
                                    <span class="input-group-addon">元</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">校购跑腿单次任务打赏平台提取比例</label>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="platform_scale" maxlength="60" placeholder="100制" value="{{ getSettingValueByKey('platform_scale') }}">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">用户单次最低满多少元提现<span class="required">(企业付款最低1元起)</span></label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="min_withdrawal_price" maxlength="60" placeholder="用户单次最低满多少元提现" value="{{ getSettingValueByKey('min_withdrawal_price') }}">
                                    <span class="input-group-addon">元</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="names" class="col-sm-3 control-label">企业用户是否可以报名兼职</label>
                            <div class="col-sm-3">
                                <select name="company_whether_sign" class="form-control">
                                    <option value="1" @if(getSettingValueByKey('company_whether_sign')) selected="selected" @endif>是</option>
                                    <option value="0" @if(!getSettingValueByKey('company_whether_sign')) selected="selected" @endif>否</option>
                                </select>
                            </div>
                        </div>
                   
        
                    {{--     <div class="form-group">
                            <label for="address" class="col-sm-3 control-label">地址</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"  name="address" placeholder="地址" value="{{ getSettingValueByKey('address') }}">
                                 <a class="inline-block pd10" onclick="openMap('address')">在地图中设定</a>
                            </div>
                        </div> --}}


                    </form>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-left" onclick="saveForm(1)">保存</button>
                </div>
                <!-- /.box-footer --> </div>
        </div>

        <!-- /.tab-pane -->
  
        <div class="tab-pane" id="tab_2">
            <div class="box box-info form">
                <!-- form start -->
                <div class="box-body">
                    <form class="form-horizontal" id="form2">


                   <div class="form-group">
                            <label for="weixin" class="col-sm-3 control-label">企业名称</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control"  name="company_name" placeholder="企业名称" value="{{ getSettingValueByKey('company_name') }}">
                            </div>
                    </div>
                        
                     <div class="form-group">
                            <label for="weixin" class="col-sm-3 control-label">加入会员介绍</label>
                            <div class="col-sm-9">
                                <textarea  class="form-control"  name="join_club" placeholder="加入会员介绍" value="">{{ getSettingValueByKey('join_club') }} </textarea>
                            </div>
                      </div>

                       <div class="form-group">
                            <label for="weixin" class="col-sm-3 control-label">会员特权描述</label>
                            <div class="col-sm-9">
                                <textarea  class="form-control"  name="member_des" placeholder="会员特权描述" value="">{{ getSettingValueByKey('member_des') }} </textarea>
                            </div>
                      </div>
               
                       <div class="form-group">
                            <label for="weixin" class="col-sm-3 control-label">佣金说明 :</label>
                            <div class="col-sm-9">
                                <textarea type="text" class="form-control"  name="distribute_shuoming" rows="4" placeholder="佣金说明">{{ getSettingValueByKey('distribute_shuoming') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="weixin" class="col-sm-3 control-label">企业纠错原因</label>
                            <div class="col-sm-9">
                                <textarea class="form-control"  id="error_info_list" name="error_info_list" placeholder="纠错信息列表(多个选择使用回车换行，一行一个选项)" rows="{!! count(getErrorList()) !!}">{!! getSettingValueByKey('error_info_list') !!}</textarea>
                                <p class="help-block">多个选择使用回车换行，一行一个选项</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-left" onclick="saveForm(2)">保存</button>
                </div>
            </div>
        </div>

        
          <div class="tab-pane" id="tab_6">
            <div class="box box-info form">
                <!-- form start -->
                <div class="box-body">
                    <form class="form-horizontal" id="form6">

                        <div class="form-group">
                            <label for="weixin" class="col-sm-3 control-label">项目金额选项(单位:元)</label>
                            <div class="col-sm-9">
                                <textarea class="form-control"  id="project_money_list" name="project_money_list" placeholder="项目金额选项(多个选择使用回车换行，一行一个选项)" rows="{!! count(projectMoneyList()) !!}">{!! getSettingValueByKey('project_money_list') !!}</textarea>
                                <p class="help-block">多个选择使用回车换行，一行一个选项</p>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-left" onclick="saveForm(6)">保存</button>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="tab_8">
            <div class="box box-info form">
                <!-- form start -->
                <div class="box-body">
                    <form class="form-horizontal" id="form8">

                        <div class="form-group">
                            <label for="feie_sn" class="col-sm-3 control-label">后台每页显示记录数量</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="records_per_page" value="{{ getSettingValueByKey('records_per_page') }}"></div>
                        </div>

                        <div class="form-group">
                            <label for="feie_sn" class="col-sm-3 control-label">前端列表每页显示数量</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" name="front_take" value="{{ getSettingValueByKey('front_take') }}"></div>
                        </div>

                    </form>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-left" onclick="saveForm(8)">保存</button>
                </div>
            </div>
        </div>

        <div class="tab-pane" id="tab_9">
            <div class="box box-info form">
                <!-- form start -->
                <div class="box-body">
                    <form class="form-horizontal" id="form9">
                        <div class="form-group">
                            <label for="user_center_share_bg" class="col-sm-3 control-label">个人中心分享背景</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="image9" name="user_center_share_bg" placeholder="个人中心分享背景图片设置" value="{{ getSettingValueByKey('user_center_share_bg') }}">
                           <div class="input-append">
                                    <a data-toggle="modal" href="javascript:;" data-target="#myModal" class="btn" type="button" onclick="changeImageId('image9')">选择图片</a>
                                    <img src="@if(getSettingValueByKey('user_center_share_bg')) {{ getSettingValueByKey('user_center_share_bg') }} @endif" style="max-width: 100%; max-height: 150px; display: block;">
                                </div>
                            </div>
                        </div>
                    
                    </form>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-left" onclick="saveForm(9)">保存</button>
                </div>
            </div>
        </div>

    </div>
    <!-- /.tab-content -->
</div>
</section>
@endsection

@include('admin.partials.imagemodel')

@section('scripts')
<script src="{{ asset('js/select.js') }}"> </script>
<script>
        function saveForm(index){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/zcjy/settings/setting",
                type:"POST",
                data:$("#form"+index).serialize(),
                success: function(data) {
                  if (data.code == 0) {
                    layer.msg(data.message, {icon: 1});
                  }else{
                    layer.msg(data.message, {icon: 5});
                  }
                },
                error: function(data) {
                  //提示失败消息

                },
            });  
        }
        //纠错信息列表高度自适应
        // $("#error_info_list").height($("#error_info_list")[0].scrollHeight);
        // $("#error_info_list").on("keyup keydown", function(){
        //     console.log(this.scrollHeight);
        //     $(this).height(this.scrollHeight-10);
        // });

        $('#error_info_list,#project_money_list').keypress(function(e) {  
            var rows=parseInt($(this).attr('rows'));
            // 回车键事件  
           if(e.which == 13) {  
                rows +=1;
           }  
           $(this).attr('rows',rows);
       }); 
    </script>
@endsection