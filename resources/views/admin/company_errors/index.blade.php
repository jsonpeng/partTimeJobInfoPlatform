@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">投诉列表</h1>
     {{--    <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('companyErrors.create') !!}">Add New</a>
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
                        <label for="company_delivery">纠错原因</label>
                       <input type="text" class="form-control" name="reason" placeholder="纠错原因" @if (array_key_exists('reason', $input))value="{{$input['reason']}}"@endif>
                    </div>

               {{--      <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <label for="snumber">企业</label>
                            <select name="company_id" class="form-control">
                                    <option value="0" @if(!array_key_exists('company_id',$input)) @endif>全部</option>
                                @foreach ($companys as $item)
                                    <option value="{!! $item->id !!}" @if(array_key_exists('company_id', $input)) @if($item->id==$input['company_id']) selected="selected" @endif @endif>{!! $item->name !!}</option>
                                @endforeach
                            </select>
                    </div> --}}

                    <div class="form-group col-lg-3 col-md-4 col-sm-6 col-xs-6">
                        <label for="snumber">状态</label>
                            <select name="status" class="form-control">
                                    <option value="" @if(!array_key_exists('status',$input)) @endif>全部</option>
                                    <option value="审核中" @if(array_key_exists('status',$input)) @endif>审核中</option>
                                    <option value="已通过" @if(array_key_exists('status',$input)) @endif>已通过</option>
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
                    @include('admin.company_errors.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection


@section('scripts')
<script>
    function actionList(obj,id){
             var that=obj;
             var status=$(that).text()=='未读'?1:0;
             var text=$(that).text()=='未读'?'已读':'未读';
             var classs=$(that).text()=='未读'?'success':'danger';
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url:"/ajax/company/"+id+"/updateErrorInfo",
                  data:{
                    status:status
                  },
                  type:"GET",
                  success:function(data){
                    if(data.code==0){
                           layer.msg(data.message, {icon: 1});
                           $(that).parent().html('<span class="btn btn-'+classs+' btn-xs" onclick="actionList(this,'+id+')">'+text+'</span>');
                    }else{
                           layer.msg(data.message, {icon: 5});
                    }   
                  }
              });
    }
</script>
@endsection

