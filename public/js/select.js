       $('#province').on('change', function(){
            var newParentID = $('#province').val();
             //$('#district').hide();
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
                url:"/ajax/cities/getAjaxSelect/"+newParentID,
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
                url:"/ajax/cities/getAjaxSelect/"+newParentID,
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


        $('#district').change(function(){
            var detail=$('#province').find("option:selected").text()+$('#city').find("option:selected").text()+$(this).find("option:selected").text();
            var type=$(this).data('type');
            var name=type=="project"?'address':'detail';
            $('input[name='+name+']').val(detail)
        });


        function openMap(type=''){
            var name =type==''?'detail':'address';
            var address=$('input[name='+name+']').val();
            var url="/map?address="+address;
                if($(window).width()<479){
                        layer.open({
                            type: 2,
                            title:'请选择详细地址',
                            shadeClose: true,
                            shade: 0.8,
                            area: ['100%', '100%'],
                            content: url, 
                        });
                }else{
                     layer.open({
                        type: 2,
                        title:'请选择详细地址',
                        shadeClose: true,
                        shade: 0.8,
                        area:['60%', '680px'],
                        content: url, 
                    });
                }
        }


        function call_back_by_map(address,jindu,weidu){
            $('input[name=detail],input[name=address]').val(address);
            $('input[name=lat]').val(weidu);
            $('input[name=lon]').val(jindu);
            layer.closeAll();
        }
