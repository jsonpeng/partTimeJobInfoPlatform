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

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <section class="content-header mb10-xs">
                    <h1 class="pull-left">请选择地区</h1>
                </section>

                <div class="content pdall0-xs">
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <div class="box box-primary">
                           <div class="area_list"></div>
                        <div class="box-body text-center">
                             <div class="row select_cities_select">
                        
                            <select class="col-md-4 col-xs-12 col-sm-4" name="province" id="province" size="6">
                                        <option value="0">请选择省份</option>
                                        @foreach($cities_level1 as $item)
                                            <option value="{!! $item->id !!}">{!! $item->name !!}</option>
                                        @endforeach
                            </select>
                            <select  class="col-md-4  col-xs-12 col-sm-4" name="city" id="city" size="6">
                                        <option value="0">请选择城市</option>
                            </select>
                            <select  class="col-md-4  col-xs-12 col-sm-4" name="district" size="6" id="district">
                                        <option value="0">请选择区域</option>
                            </select>
                     
                             </div>
                        </div>
                        <div class="pull-left" style="margin-top:15px;">
                            <input type="button" class="btn btn-success"  value="添加" id="area_add">&nbsp;&nbsp;<input type="button" class="btn btn-success"  value="确定" id="area_enter"></div>
                    </div>
                 
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script type="text/javascript">
    /*
    三级选择
     */
     $('#province').on('change', function(){
            var newParentID = $('#province').val();
             $('#district').hide();
            if (newParentID == 0) {
                $('#city').empty();
                $('#city').append("<option value='0'>请选择城市</option>");
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/zcjy/cities/getAjaxSelect/"+newParentID,
                type:"POST",
                success: function(data) {
                    if(data.code==0){
                    $('#city').empty();
                    $('#city').append("<option value='0'>请选择城市</option>");
                    $('#city').append(data.message);
                }else{
                    $('#city').empty();
                    $('#city').append("<option value='0'>请选择城市</option>");
                }
                },
                error: function(data) {
                  //提示失败消息
                    
                },
            });
        });

        $('#city').on('change', function(){
            var newParentID = $('#city').val();

            if (newParentID == 0) {
                $('#district').empty();
                $('#district').append("<option value='0'>请选择区域</option>");
                $('#district').show();
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/zcjy/cities/getAjaxSelect/"+newParentID,
                type:"POST",
                success: function(data) {
                    if(data.code==0){
                    $('#district').empty();
                    $('#district').append("<option value='0'>请选择区域</option>");
                    $('#district').append(data.message);
                    $('#district').show();
                }else{
                    $('#district').empty();
                    $('#district').append("<option value='0'>请选择区域</option>");
                    $('#district').show();
                }
                   
                },
                error: function(data) {
                  //提示失败消息
                    
                },
            });
        });

        //  添加配送区域
        $('#area_add').click(function(){
            //
            var province = $("#province").val(); // 省份
            var city = $("#city").val();        // 城市
            var district = $("#district").val(); // 县镇
            var text = '';  // 中文文本
            var tpl = ''; // 输入框 html
            var is_set = 0; // 是否已经设置了

            // 设置 县镇
            if(district > 0){
                text = $("#district").find('option:selected').text();
                tpl = '<li><label><input class="checkbox" type="checkbox" checked name="area_list[]" data-name="'+text+'" value="'+district+'">'+text+'</label></li>';
                is_set = district; // 街道设置了不再设置市
            }
            // 如果县镇没设置 就获取城市
            if(is_set == 0 && city > 0){
                text = $("#city").find('option:selected').text();
                tpl = '<li><label><input class="checkbox" type="checkbox" checked name="area_list[]" data-name="'+text+'"  value="'+city+'">'+text+'</label></li>';
                is_set = city;  // 市区设置了不再设省份

            }
            // 如果城市没设置  就获取省份
            if(is_set == 0 && province > 0){
                text = $("#province").find('option:selected').text();
                tpl = '<li><label><input class="checkbox" type="checkbox" checked name="area_list[]" data-name="'+text+'"  value="'+province+'">'+text+'</label></li>';
                is_set = province;

            }

            var obj = $("input[class='checkbox']"); // 已经设置好的复选框拿出来
            var exist = 0;  // 表示下拉框选择的 是否已经存在于复选框中
            $(obj).each(function(){
                if($(this).val() == is_set){  //当前下拉框的如果已经存在于 复选框 中
                    layer.alert('已经存在该区域', {icon: 2});  // alert("已经存在该区域");
                    exist = 1; // 标识已经存在
                }
            })
            if(!exist)
                $('.area_list').append(tpl); // 不存在就追加进 去
        });

        //确定
        $('#area_enter').click(function(){
            var input = $("input[type='checkbox']:checked");
            if (input.length == 0) {
                layer.alert('请添加区域', {icon: 2});
                return false;
            }
            var area_list = new Array();
            input.each(function(i,o){
                var area_id = $(this).attr("value");
                var area_name = $(this).data("name");
                var cartItemCheck = new Area(area_id,area_name);
                area_list.push(cartItemCheck);
            });
            console.log(area_list);
            window.parent.call_back(area_list);
        });

        //地区对象
        function Area(id, name) {
            this.id = id;
            this.name = name;
        }
</script>
@endsection