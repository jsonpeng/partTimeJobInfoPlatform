@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">兼职类型列表</h1>
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('industries.create') !!}">添加</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.industries.table')
            </div>
        </div>
        <div class="text-center">
             <div class="tc"><?php echo $industries->appends($input)->render(); ?></div>
         </div>
    </div>
@endsection

