@extends('layouts.app')


@section('css')
    <style type="text/css">
        .box-body{
            background-color: #fff;
        }
        .hidden{
          display: none;
        }
    </style>
@endsection

@section('content')

    <div class="content">
        <div class="clearfix"></div>
        
        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box-header with-border">
            <h3 class="box-title">校购统计</h3>
          {{--   <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box-tools --> --}}
        </div><!-- /.box-header -->

         <form >
          <div class="box-body">
              
                  <div class="form-group">
                      <label>选择时间</label>
                      <select class="form-control" name="time_type">
                          <option value="day" @if (array_key_exists('time_type', $input) && $input['time_type'] == 'day' || !array_key_exists('time_type', $input)) selected="selected" @endif>日报</option>
                           <option value="week" @if (array_key_exists('time_type', $input) &&  $input['time_type'] == 'week') selected="selected" @endif>周报</option>
                          <option value="month" @if (array_key_exists('time_type', $input) && $input['time_type'] == 'month') selected="selected" @endif>月报</option>
                          <option value="custom" @if (array_key_exists('time_type', $input) && $input['time_type'] == 'custom') selected="selected" @endif>自定义</option>
                      </select>
                  </div>
              
          </div>
    

        <div class="box-body">
            <div id="form_search" @if (array_key_exists('time_type', $input) && $input['time_type'] == 'custom') class="show" @else class="hidden" @endif style="margin-left: -15px;">
                <div class="form-group col-md-4">
                    <label>起始时间</label>
                    <input type="text" class="form-control" name="time_start" id="time_start" placeholder="开始时间"  @if (array_key_exists('time_start', $input))value="{{substr($input['time_start'],0,10)}}"@endif  {!! Request::is('memberCount*') || Request::is('memberCount/month') ? 'disabled' : '' !!}>
                </div>
                <div class="form-group col-md-4">
                    <label>结束时间</label>
                    <input type="text" class="form-control" name="time_end" id="time_end" placeholder="结束时间" @if (array_key_exists('time_end', $input))value="{{substr($input['time_end'],0,10)}}"@endif {!! Request::is('memberCount*') || Request::is('memberCount/month') ? 'disabled' : '' !!}>
                </div>

                <div class="form-group col-md-2">
                    <label>操作</label>
                    <button type="submit" class="btn btn-primary pull-right form-control">查询</button>
                </div>
            </div>
        </div><!-- /.box-body -->
        </form>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="ion ion-ios-cart-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">校购任务总数量</span>
                            <span class="info-box-number">{{ $statics->all_tasks_num }}</span>
                            <span class="info-box-text">校购完成任务数量</span>
                            <span class="info-box-number">{{ $statics->achieve_tasks_num }}</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="ion ion-ios-cart-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">校购任务成功支付金额</span>
                            <span class="info-box-number">{{ $statics->pay_price }}</span>
                            <span class="info-box-text">校购超时失败任务数量</span>
                            <span class="info-box-number">{{ $statics->timeout_tasks_num }}</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-cart-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">平台托管金额</span>
                            <span class="info-box-number">{{ $statics->platform_price }}</span>
                            <span class="info-box-text">用户累计完成提现</span>
                            <span class="info-box-number">{{ $statics->achieve_withdraw_price }}</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->

                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">平台分润</span>
                            <span class="info-box-number">{{ $statics->platform_profits }}</span>
                            <span class="info-box-text">买手累计获取</span>
                            <span class="info-box-number">{{ $statics->errander_price }}</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->

            </div>
        </div>


      
{{--             <div class="box box-primary">
                <div class="box-body">
                    <table class="table table-responsive" id="orders-table">
                        <thead>
                            <tr>
                                <th>代理商昵称</th>
                                <th>注册商户数</th>
                                <th>旗下商户购买套餐数量</th>
                                <th>旗下商户购买套餐总额</th>
                                <th>提成总额</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->nickname }}</td>
                                <td>{!! $admin->shanghu_num !!}</td>
                                <td>{!! $admin->shanghu_buy_num !!}</td>
                                <td>{!!  $admin->shanghu_buy_price !!}</td>
                                <td> {!!  $admin->ticheng_sum_price !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="text-center">
                {!! $admins->appends("")->links() !!}
            </div> --}}
    </div>
@endsection


@section('scripts')
    <script type="text/javascript">
        $('select[name=time_type]').change(function(){

            if($(this).val() == 'custom'){
                $('#form_search').removeClass('hidden');
            }
            else{
              $('#form_search').addClass('hidden');
              location.href = '?time_type='+$(this).val();
            }
           

        });
        $('#time_start, #time_end').datepicker({
            format: "yyyy-mm-dd",
            language: "zh-CN",
            todayHighlight: true
          });
    </script>
@endsection


