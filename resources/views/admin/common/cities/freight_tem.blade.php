@extends('layouts.app_tem')

@section('css')
<style type="text/css">
.area_list li{
    list-style: none;
    display: inline-block;
    margin-right: 5px;
}
</style>
@endsection
<!--商品多选-->
@section('content')
    <div class="container-fluid" >
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <section class="content-header mb10-xs">
                    <h1 class="pull-left"><strong style="color:red;">{!! $cities->name !!}</strong>对应的运费模板信息({!! count($freight_tem) !!}条)</h1>
                </section>

                <div class="content pdall0-xs">
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <div class="box box-primary">
                          
                        <div class="box-body">
                          
                        
                                <table class="table table-responsive" id="freightTems-table">
                                            <thead>
                                                <tr>
                                                <th>名称</th>
                                                <th>计价方式</th>
                                                <th>是否使用了系统默认</th>
                                                <th>首/件/重/体积</th>
                                                <th>运费</th>
                                                <th>续/件/重/体积</th>
                                                <th>续费</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($freight_tem as $freightTem)
                                                <tr>
                                                    <td>{!! $freightTem['name'] !!}</td>
                                                    <td>{!! $freightTem['freight_type'] !!}</td>
                                                    <td>{!! $freightTem['use_default'] !!}</td>
                                                    <td>{!! $freightTem['freight_first_count'] !!}</td>
                                                    <td>{!! $freightTem['the_freight'] !!}</td>
                                                    <td>{!! $freightTem['freight_continue_count'] !!}</td>
                                                    <td>{!! $freightTem['freight_continue_price'] !!}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                            </table>
                           
                        </div>
                    
                    </div>
                 
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script type="text/javascript">
</script>
@endsection