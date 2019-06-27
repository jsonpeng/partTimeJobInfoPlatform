@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">订单列表</h1>
      <!--   <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('orders.create') !!}">Add New</a>
        </h1> -->
    </section>
    <div class="content">
        <div class="clearfix"></div>
             <div class="box box-default box-solid mb10-xs {!! !$tools?'collapsed-box':'' !!}">
            <div class="box-header with-border">
              <h3 class="box-title">查询</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-{!! !$tools?'plus':'minus' !!}"></i></button>
              </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <form id="projects_search">

                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-6">
                        <label for="snumber">订单金额</label>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="price_start" placeholder="起" @if (array_key_exists('price_start', $input))value="{{$input['price_start']}}"@endif>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="price_end" placeholder="止" @if (array_key_exists('price_end', $input))value="{{$input['price_end']}}"@endif>
                            </div>
                        </div>
                    </div>
                       <div class="form-group col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <label for="order_delivery">订单类型</label>
                        <select class="form-control" name="order_type">
                            <option value="" @if (!array_key_exists('order_type', $input)) selected="selected" @endif>全部</option>
                            <option value="普通" @if (array_key_exists('order_type', $input) && $input['order_type'] == '普通')  selected="selected" @endif>普通</option>
                             <option value="升级" @if (array_key_exists('order_type', $input) && $input['order_type'] == '升级')  @endif>升级</option>
                
                        </select>
                    </div>
             
                    <div class="form-group col-lg-2 col-md-3 col-sm-4 col-xs-6">
                        <label for="order_pay">支付状态</label>
                        <select class="form-control" name="order_pay">
                            <option value="" @if (!array_key_exists('order_pay', $input)) selected="selected" @endif>全部</option>
                            <option value="未支付" @if (array_key_exists('order_pay', $input) && $input['order_pay'] == '未支付') selected="selected" @endif>未支付</option>
                            <option value="已支付" @if (array_key_exists('order_pay', $input) && $input['order_pay'] == '已支付') selected="selected" @endif>已支付</option>
                        </select>
                    </div>
        
                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-6">
                        <label for="order_pay">下单用户昵称</label>
                        <input type="text" class="form-control" name="nickname" placeholder="下单用户" @if (array_key_exists('nickname', $input))value="{{$input['nickname']}}"@endif>
                    </div>
               
          
                    
                    <div class="form-group col-md-1 hidden-xs hidden-sm" style="padding-top: 25px;">
                        <button type="submit" class="btn btn-primary pull-right" onclick="search()">查询</button>
                    </div>

                    <div class="form-group col-md-1 visible-xs visible-sm">
                        <button type="submit" class="btn btn-primary pull-left" onclick="search()">查询</button>
                    </div>
            </form>
                 
            </div><!-- /.box-body -->
        </div><!-- /.box -->
        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.orders.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
        $('#create_start, #create_end').datepicker({
            format: "yyyy-mm-dd",
            language: "zh-CN",
            todayHighlight: true
        });
</script>
@endsection

