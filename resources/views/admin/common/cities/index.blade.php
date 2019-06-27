@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">

            <div class="col-sm-12 col-lg-12">
                <section class="content-header mb15">
                    <h1 class="pull-left ">地区设置</h1>
                    <h1 class="pull-right">
                       <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('cities.create') !!}">添加</a>
                    </h1>
                </section>
                <div class="content pdall0-xs">
                    <div class="clearfix"></div>

                    @include('flash::message')

                    <div class="clearfix"></div>
                    <div class="box box-primary mb10-xs ">
                        <div class="box-body">
                                @include('admin.common.cities.table')
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
    $('.cities').each(function(){
        //把湖北省替换到安徽 安徽再到最下面
        if($(this).children('td').eq(0).text()=='湖北省'){

            $('.cities_body').append('<tr  class="cities">'+$('.cities_body').children('tr').eq(1).html()+'</tr>');
            $('.cities_body').children('tr').eq(1).html($(this).html());

        }
    });
</script>
@endsection

