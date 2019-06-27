@extends('layouts.app')

@section('content')
    <div class="container-fluid" >
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <section class="content-header mb15">
                    <h1 class="pull-left ">地区设置</h1>
                    <h1 class="pull-right">
                     
                    </h1>
                </section>
                <div class="content pdall0-xs">
                    <div class="clearfix"></div>

                    @include('flash::message')

                    <div class="clearfix"></div>

                   <a href="{!! varifyPidToBackByPid($pid) !!}" class="inline-block pd10"><i class="fa fa-level-up"></i>返回上级地区</a>
                     <a class="child_city_add inline-block pd15"  href="{!! route('cities.child.create',[$pid]) !!}">添加地区</a>

                    <div class="box box-primary mb10-xs mt15">

                        <div class="box-body">
                                @include('admin.common.cities.table_child')
                        </div>
                    </div>
                    <div class="text-center">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    function showFreightTemList(cid){
         var url = "/zcjy/cities/frame/freighttem/"+cid;
            layer.open({
                type: 2,
                title: '查看运费模板信息',
                shadeClose: true,
                shade: 0.2,
                area: ['60%', '60%'],
                content: url
            });
    }
</script>
@endsection

