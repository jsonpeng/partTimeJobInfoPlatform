@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">会员列表</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('userLevels.create') !!}">添加</a>
        </h1>
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
                        <label for="order_delivery">会员名称</label>
                       <input type="text" class="form-control" name="name" placeholder="会员名称" @if (array_key_exists('name', $input))value="{{$input['name']}}"@endif>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <label for="snumber">访问金额</label>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="amount_start" placeholder="起" @if (array_key_exists('amount_start', $input))value="{{$input['amount_start']}}"@endif>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="amount_end" placeholder="止" @if (array_key_exists('amount_end', $input))value="{{$input['amount_end']}}"@endif>
                            </div>
                        </div>
                    </div>
        
                    <div class="form-group col-lg-4 col-md-4 col-sm-6 col-xs-6">
                        <label for="snumber">售价</label>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="price_start" placeholder="起" @if (array_key_exists('price_start', $input))value="{{$input['price_start']}}"@endif>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="price_end" placeholder="止" @if (array_key_exists('price_end', $input))value="{{$input['price_end']}}"@endif>
                            </div>
                        </div>
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
                    @include('admin.user_levels.table',['input'=>$input])
            </div>
        </div>
         <div class="text-center">
             <div class="tc"><?php echo $userLevels->appends($input)->render(); ?></div>
         </div>
    </div>
@endsection

