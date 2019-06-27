<style type="text/css">
    @media (min-width: 768px){
        .modal-dialog {
            width: 600px;
            margin: 150px auto;
        }
    }
</style>
<div class="modal fade" id="myModal"  aria-hidden="true">
    <div class="modal-dialog" style="width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">媒体库</h4>
            </div>
            <div class="modal-body" style="padding:0px; margin:0px; width: 1000px;">
                <iframe id="image" width="1000" height="500" src="/filemanager/dialog.php?type=1&field_id=image" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script type="text/javascript">

    function changeImageId(id) {
        $('iframe#image').attr('src', '/filemanager/dialog.php?type=1&field_id=' + id);
    }
    var i=99;
    function responsive_filemanager_callback(field_id){
       var url=$('#'+field_id).val();
        if (field_id == 'company_image') {
                i++;
                //向后台添加企业图片
                console.log('向后台添加企业图片');
                // $('.images').attr('style','border: 1px solid black;margin-top:20px;margin-bottom:20px;padding-left: 10%;');
                $('.images').show();
                $('.images').append(
                            "<div class='image-item' id='company_image_"+i+"'>\
                                <img src='" +url+ "' alt=' style='max-width: 100%;'>\
                                <div class='tr'><div class='btn btn-danger btn-xs' onclick='deletePic("+i+")'>删除</div></div>\
                                <input type='hidden' name='company_images[]' value='"+url+"'>\
                            </div>"
                            );
               // return;
        }
        else if(field_id =='project_image'){
              i++;
                //向后台添加企业图片
                console.log('向后台添加项目图片');
                // $('.images').attr('style','border: 1px solid black;margin-top:20px;margin-bottom:20px;padding-left: 10%;');
                $('.images').show();
                $('.images').append(
                            "<div class='image-item' id='project_image_"+i+"'>\
                                <img src='" +url+ "' alt=' style='max-width: 100%;'>\
                                <div class='tr'><div class='btn btn-danger btn-xs' onclick='deletePic("+i+")'>删除</div></div>\
                                <input type='hidden' name='project_images[]' value='"+url+"'>\
                            </div>"
                            );
        }
              else if(field_id =='errand_image'){
              i++;
                //向后台添加企业图片
                console.log('向后台添加项目图片');
                // $('.images').attr('style','border: 1px solid black;margin-top:20px;margin-bottom:20px;padding-left: 10%;');
                $('.images').show();
                $('.images').append(
                            "<div class='image-item' id='errand_image_"+i+"'>\
                                <img src='" +url+ "' alt=' style='max-width: 100%;'>\
                                <div class='tr'><div class='btn btn-danger btn-xs' onclick='deletePic("+i+")'>删除</div></div>\
                                <input type='hidden' name='images[]' value='"+url+"'>\
                            </div>"
                            );
        }
         else {

        $('#'+field_id).parent().find('img').attr('src', url);
    }

    }
</script>