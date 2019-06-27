@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">用户列表</h1>
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
                        <label for="order_delivery">微信昵称</label>
                       <input type="text" class="form-control" name="name" placeholder="微信昵称" @if (array_key_exists('name', $input))value="{{$input['name']}}"@endif>
                    </div>
                    
                    <div class="form-group col-lg-2 col-md-3 col-sm-12 col-xs-12">
                        <label for="order_delivery">手机号</label>
                       <input type="text" class="form-control" name="mobile" placeholder="手机号" @if (array_key_exists('mobile', $input))value="{{$input['mobile']}}"@endif>
                    </div>

              {{--       <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <label for="snumber">提成金额排序</label>
                            <select name="distribut_money" class="form-control">
                                    <option value="0" @if(!array_key_exists('distribut_money',$input)) selected="selected" @endif>全部</option>
                                    <option value="asc" @if(array_key_exists('distribut_money',$input) && $input['distribut_money']=='asc') selected="selected" @endif>升序</option>
                                    <option value="desc" @if(array_key_exists('distribut_money',$input) && $input['distribut_money']=='desc') selected="selected" @endif>倒序</option>
                            </select>
                    </div> --}}
        
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
                    @include('admin.user.table')
            </div>
        </div>
         <div class="text-center">
             <div class="tc"><?php echo $users->appends($input)->render(); ?></div>
         </div>
    </div>
@endsection

