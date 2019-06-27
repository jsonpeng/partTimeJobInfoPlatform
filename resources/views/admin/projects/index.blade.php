@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">兼职列表</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('projects.create') !!}">添加</a>
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
                        <label for="order_delivery">兼职名称</label>
                       <input type="text" class="form-control" name="name" placeholder="兼职名称" @if (array_key_exists('name', $input))value="{{$input['name']}}"@endif>
                    </div>

                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-6">
                        <label for="status">兼职类型</label>
                        <select class="form-control" name="industries">
                            <option value="" @if (!array_key_exists('industries', $input)) selected="selected" @endif>全部</option>
                            @foreach ($industries as $item)
                                <option value="{!! $item->id !!}" @if (array_key_exists('industries', $input) && $input['industries'] == $item->id ) selected="selected" @endif>{!! $item->name !!}</option>
                            @endforeach
                        </select> 
                    </div>

                    <div class="form-group col-lg-2 col-md-4 col-sm-6 col-xs-6">
                        <label for="snumber">兼职金额(元)</label>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="price_start" placeholder="起" @if (array_key_exists('price_start', $input))value="{{$input['price_start']}}"@endif>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control" name="price_end" placeholder="止" @if (array_key_exists('price_end', $input))value="{{$input['price_end']}}"@endif>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group col-lg-4 col-md-9 col-sm-12 col-xs-12">
                        <label for="order_pay">地域城市</label>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr0-xs">  
                                <select name="diyu" class="form-control level01" >
                                    <option value="0">请选择地域</option>
                                 {{--    <?php $i=0;?> --}}
                                    @foreach ($diyu as $k => $v)
                                   {{--  <?php $i++;?> --}}
                                    <option value="{!! $k !!}" @if(array_key_exists('diyu',$input) && $input['diyu']== $k) selected="selected" @endif>{!! $k !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr0-xs">    
                                {!! Form::select('cities',$cities,$level02 , ['class' => 'form-control level02']) !!}
                             </div>
                        </div>
                    </div>
               
                    <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-6">
                        <label for="status">审核状态</label>
                        <select class="form-control" name="status">
                            <option value="" @if (!array_key_exists('status', $input)) selected="selected" @endif>全部</option>
                            <option value="审核中" @if (array_key_exists('status', $input) && $input['status'] == '审核中') selected="selected" @endif>审核中</option>
                            <option value="通过" @if (array_key_exists('status', $input) && $input['status'] == '通过') selected="selected" @endif>通过</option>
                            <option value="不通过" @if (array_key_exists('status', $input) && $input['status'] == '不通过') selected="selected" @endif>不通过</option>
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
                    @include('admin.projects.table')
            </div>
        </div>
         <div class="text-center">
             <div class="tc"><?php echo $projects->appends($input)->render(); ?></div>
         </div>
    </div>
@endsection



@section('scripts')
<script>
    $(function(){
        $('.level01').change(function(){
            $('.level02').empty();
            var diyu=$(this).val();
            if(diyu==0){
                  $('.level02').empty();
                  $('.level02').append('<option value="0">请选择地区</option>');
                  return false;
            }
             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/ajax/diyu/getAjaxSelect/"+diyu,
                type:"POST",
                success: function(data) {
                         if(data.code==0){
                            $('.level02').append(data.message);
                         }else{
                            $('.level02').empty();
                         }
                }
            });

        });
    });
</script>
@endsection