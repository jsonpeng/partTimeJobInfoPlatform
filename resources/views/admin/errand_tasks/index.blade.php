@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">跑腿任务列表</h1>
  {{--       <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('errandTasks.create') !!}">添加</a>
        </h1> --}}
    </section>
    <div class="content">
        <div class="clearfix"></div>
         <!--查询搜索框-->
         <div class="box box-default box-solid mb10-xs @if(!$tools) collapsed-box @endif">
            <div class="box-header with-border">
              <h3 class="box-title">查询</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-{!! !$tools?'plus':'minus' !!}"></i></button>
              </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <form id="projects_search">

                    <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <label for="order_delivery">任务名称</label>
                       <input type="text" class="form-control" name="name" placeholder="任务名称" @if (array_key_exists('name', $input))value="{{$input['name']}}"@endif>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <label for="order_delivery">学校名称</label>
                       <input type="text" class="form-control" name="school_name" placeholder="学校名称" @if (array_key_exists('name', $input))value="{{$input['school_name']}}"@endif>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-6">
                        <label for="status">状态</label>
                        <select class="form-control" name="status">
                            <option value="" @if (!array_key_exists('status', $input)) selected="selected" @endif>全部</option>
                            <option value="已发布" @if (array_key_exists('status', $input) && $input['status'] == '已发布') selected="selected" @endif>已发布</option>
                            <option value="待收货" @if (array_key_exists('status', $input) && $input['status'] == '待收货') selected="selected" @endif>待收货</option>
                            <option value="已收货" @if (array_key_exists('status', $input) && $input['status'] == '已收货') selected="selected" @endif>已收货</option>
                        </select> 
                    </div>
                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-6">
                        <label for="pay_status">支付状态</label>
                        <select class="form-control" name="pay_status">
                            <option value="" @if (!array_key_exists('pay_status', $input)) selected="selected" @endif>全部</option>
                            <option value="待付款" @if (array_key_exists('pay_status', $input) && $input['pay_status'] == '待付款') selected="selected" @endif>待付款</option>
                            <option value="已付款" @if (array_key_exists('pay_status', $input) && $input['pay_status'] == '已付款') selected="selected" @endif>已付款</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-1 col-md-1 hidden-xs hidden-sm" style="padding-top: 25px;">
                        <button type="submit" class="btn btn-primary pull-right " onclick="search()">查询</button>
                    </div>
                    <div class="form-group col-xs-6 visible-xs visible-sm" >
                        <button type="submit" class="btn btn-primary pull-left " onclick="search()">查询</button>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        <!--/查询搜索框-->

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.errand_tasks.table')
            </div>
        </div>
        <div class="text-center">
            {!! $errandTasks->appends('')->links() !!}
        </div>
    </div>
@endsection

