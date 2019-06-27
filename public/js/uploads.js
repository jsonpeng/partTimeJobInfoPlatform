      var previewsContainer;
      $('#uploads_image').click(function(){
        previewsContainer='#'+$(this).data('box');
      });

      var previewTemplate='<div class="dz-preview dz-file-preview uploads_box"><img class="success_img" data-dz-thumbnail/><input type="hidden" class="imgsrc" data-dz-thumbnail /><input type="hidden" name="'+types+'[]" value=""><span class="dz-progress"></span><div class="zhezhao" style="display:none;" data-status="none"></div><a onclick="del_image(this)" style="display:none;">删除</a></div>';
      //上传的dom对象
      var progress_dom;
      
      var myDropzone = new Dropzone(document.body, {
        //这是负责处理上传的路径
        url:'/ajax/uploads',
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        parallelUploads: 20,
        addRemoveLinks:false,
        maxFiles:6,
        previewTemplate: previewTemplate,
        autoQueue: true, 
        previewsContainer: "#success_image_box", 
        clickable: "#uploads_image",
        uploadMultiple:false 
      });
      myDropzone.on("addedfile", function(file){
        //var fangxiang=getPhotoOrientation(myDropzone.getAcceptedFiles());
        //console.log($(file.previewElement).find('.imgsrc')[0]);

        progress_dom=file.previewElement;
        console.log(progress_dom);
        $('.uploads_box').each(function(){
          console.log($(this).index());
          if($(this).index()==5){
            $('#uploads_image').hide();
          }
          if($(this).index()>=6){
             $(this).remove();
             return false;
          }
        });
      });
      //队列上传过程
      myDropzone.on("totaluploadprogress", function(progress) {
        progress=Math.round(progress);
        $(progress_dom).find('span').text(progress+'%');
      });
      //队列上传结束
      myDropzone.on("queuecomplete", function(progress) {
        $(progress_dom).find('span').text('');
      });
      //上传成功触发的事件
      myDropzone.on("success",function(file,data){
        if(data.code==0){
          console.log('上传成功');
          var success_dom=file.previewElement;
          $(success_dom).find('img').attr('src',data.message.src); 
          $(success_dom).find('input').val(data.message.src); 
          $(success_dom).find('a').show();
          $(success_dom).find('.zhezhao').css('display', 'none');
          $(success_dom).find('.zhezhao').data('status', 'true');  
    
        }else{
               layer.open({
              content: data.message
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            }); 
        }

      });

      function del_image(obj){
        
         $(obj).parent().remove();
      
       // console.log($('.uploads_box').length);
        if($('.uploads_box').length<=5){
          $('#uploads_image').show();
        }
      }       
