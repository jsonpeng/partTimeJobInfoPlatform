<style type="text/css">
    @media (min-width: 768px){
        .modal-dialog {
            width: 600px;
            margin: 150px auto;
        }
    }
</style>
<div class="modal fade" id="myModal2"  aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">媒体库</h4>
            </div>
            <div class="modal-body" style="padding:0px; margin:0px; width: 1000px;">
                <iframe id="image2" width="1000" height="500" src="/filemanager/dialog.php?type=1&field_id=product_image" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">
      function responsive_filemanager_callback(field_id){
        if (field_id == 'product_image') {
            var url=jQuery('#'+field_id).val();
            //向后台添加商品图片
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/zcjy/pointImages",
                type:"POST",
                data:'url=' + url + '&point_id=' + $("#point_id").val(),
                success: function(data) {
                    //提示成功消息
                    console.log(data);
                    $('.images').append(
                        "<div class='image-item' id='product_image_"+data.id+"'>\
                            <img src='" + data.url + "' alt=' style='max-width: 100%;'>\
                            <div class='tr'><div class='btn btn-danger btn-xs' onclick='deletePic("+data.id+")'>删除</div></div>\
                        </div>"
                        )
                },
                error: function(data) {
                    //提示失败消息

                },
            });
        } else {
            var url=jQuery('#'+field_id).val();
            jQuery('#'+field_id).parent().find('img').attr('src', url);
        }
    }
</script>