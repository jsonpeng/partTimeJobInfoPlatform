@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑会员
        </h1>
   </section>
   <div class="content" >
       @include('adminlte-templates::common.errors')
       <div class="box box-primary" style="margin-bottom: 0px;">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($users, ['route' => ['users.update', $users->id], 'method' => 'patch']) !!}

                        @include('admin.user.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
{{--   <div class="content">

      <div class="box box-solid" style="margin-bottom:0px;">
        <div class="box-header with-border">
            <h3 class="box-title">加入成功的用户</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row" style="font-weight: bold;">
                 <div class="col-md-3 col-xs-5">头像</div>
                 <div class="col-md-2 col-xs-2">姓名</div>
                 <div class="col-md-3 col-xs-2">微信昵称</div>
                 <div class="col-md-4 col-xs-3 hidden-xs">时间</div>
            </div>

            @foreach ($share_users as $item)
              <div class="row">
                   <div class="col-md-3 col-xs-5"><a href="/zcjy/users/{!! $item->id !!}/edit" target="_blank"><img src="{!! $item->head_image !!}"  style="max-width: 100%;height: 40px;"/></a></div>
                   <div class="col-md-2 col-xs-2">{!! $item->name !!}</div>
                   <div class="col-md-3 col-xs-2">{!! $item->nickname !!}</div>
                   <div class="col-md-4 col-xs-3 hidden-xs">{!! $item->share_time !!}</div>
              </div>
            @endforeach

        </div>
        <!-- /.box-body -->
      </div>
      <div class="text-center">{!! $share_users->appends($input)->links() !!}</div>


       <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">购买成功的用户</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row" style="font-weight: bold;">
                 <div class="col-md-3 col-xs-5">头像</div>
                 <div class="col-md-2 col-xs-2">姓名</div>
                 <div class="col-md-3 col-xs-2">微信昵称</div>
                 <div class="col-md-4 col-xs-3 hidden-xs">时间</div>
               
            </div>
        
             @foreach ($buy_users as $item)
              <div class="row">
                   <div class="col-md-3 col-xs-5"><a href="/zcjy/users/{!! $item->id !!}/edit" target="_blank"><img src="{!! $item->head_image !!}"  style="max-width: 100%;height: 40px;"/></a></div>
                   <div class="col-md-2 col-xs-2">{!! $item->name !!}</div>
                   <div class="col-md-3 col-xs-2">{!! $item->nickname !!}</div>
                   <div class="col-md-4 col-xs-3 hidden-xs">{!! $item->share_time !!}</div>
              </div>
            @endforeach
        
        </div>
        <!-- /.box-body -->
      
    </div>
  </div> --}}
@endsection

@include('admin.partials.imagemodel')