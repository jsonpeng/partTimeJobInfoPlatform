/**
 * 存储表单
 * @param  {[type]} form_attr    [description]
 * @param  {[type]} url          [description]
 * @param  {[type]} redirect_url [description]
 * @return {[type]}              [description]
 */
// function saveForm(form_attr,url,redirect_url){
//         if(!form_data_filter($("#form_"+form_attr).serialize())){
//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                 }
//             });
//             $.ajax({
//                 url:url,
//                 type:"GET",
//                 data:$("#form_"+form_attr).serialize(),
//                 success: function(data) {
//                   if (data.code == 0) {
//                     layer.open({
//                       content: data.message
//                       ,skin: 'msg'
//                       ,time: 2 //2秒后自动关闭
//                     });
//                     setTimeout(function(){
//                         location.href=redirect_url;
//                     },500); 
//                   }else{
//                       layer.open({
//                         content: data.message
//                         ,skin: 'msg'
//                         ,time: 2 //2秒后自动关闭
//                       });
//                     //layer.msg(data.message, {icon: 5});
//                   }
//                 },
//             });  
//         }else{
//             layer.open({
//               content: '输入参数不完整'
//               ,skin: 'msg'
//               ,time: 2 //2秒后自动关闭
//             });
//         }
// }

//过滤空字符串
function form_data_filter(form_data){
    var array = form_data.split('&');
    var status=false;
    //把字符串按&号分隔成数组 得到  字符串数组
      for(var i = 0;i < array.length; i++){
        var kwarr = array[i].split('=');
    //循环将数组中的每个子元素字符串用=号分隔成数组然后判断索引为1的子元素是否存在或为‘' 从而达到了表单判空的目的
        if(kwarr[1]===null || kwarr[1] ===''){
          //alert('除密码外不能存在空值');
          status=true;
        }
      }
     return status; 
}

/**
 * 会员中心
 */
//项目删除
$('.project_delete').click(function(){
    var that=this;
    var id=$(that).data('id');
    // var r=confirm('您确定要删除该项目吗？删除后将无法修复!')
    layer.open({
      content: '您确定要删除该项目吗?'
      ,btn: ['删除', '不要']
      ,yes: function(){
          $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url:'/ajax/project/'+id+'/delete',
          type:"GET",
          success: function(data) {
            if (data.code == 0) {
              layer.open({
                  content: '删除成功'
                  ,skin: 'msg'
                  ,time: 2 //2秒后自动关闭
                });
              $(that).parent().parent().remove();
            }else{
              layer.open({
                content: '删除失败'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
              });
            }
          },
        });
      }
    });
    
      
      
  });
        

  

//暂停 开始
/**
* /ajax/project/'+id+'/update
*  status => 正常 暂停
*/
$('.project_status').click(function(){
    var that=this;
    var id=$(that).data('id');

    var status=$(that).data('status');
    status= (status=='true' || status) ?'暂停':'正常';
    console.log(status);
    // return;
    $.ajaxSetup({
      headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    })
    $.ajax({
      url:'/ajax/project/'+id+'/update',
      type:"GET",
      data:{
          status:status

      },
      success:function(data){
        if(data.code==0){
       
          if(status=='正常'){
            $(that).children().attr('src','/images/play.png');
            $(that).data('status','true');
          }else{
            $(that).children().attr('src','/images/zanting.png');
            $(that).data('status',false);
          }
           
         layer.open({
              content: data.message
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
          
          }else{
               layer.open({
                content: data.message
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
              });
          }

      }
    })
});


//更新
/**
* /ajax/project/'+id+'/show
*  status => 正常 暂停
*/
$('.project_refresh').click(function(){
    var that=this;
    var id=$(that).data('id');
    $.ajaxSetup({
      headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    })
    $.ajax({
      url:'/ajax/project/'+id+'/show',
      type:"GET",
      success:function(data){
        if(data.code==0){
          // alert(data.message);
            var project=data.message;
            var this_obj=$(that).parent().parent();
            console.log(project.status);
            var status=project.status=='正常'?'play':'zanting';
            console.log(status);
            console.log(data);
            //项目名称 item_name
            this_obj.find('.item_name').text(project.name);
            
            //审核状态 new_status
            this_obj.find('.new_status').text('最新状态:'+project.auth_status);
            //项目状态
            this_obj.find('.project_status').children('img').attr('src','/images/'+status+'.png');
            layer.open({
              content: '刷新成功'
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });    
               
        }else{
          // alert(data.message);
            console.log(data);
           layer.open({
              content: '刷新失败'
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
        }
      }
    })
});

//收藏操作
$('.collect_permission').click(function(){
    //项目id
    var id=$(this).data('id');
    //收藏状态  0没收藏 1已经收藏了
    var status=parseInt($(this).data('status'));

        //没收藏0 => 已收藏 1
        //已收藏1 => 没收藏 0
        status= status==0?1:0;

    //project company
    var type=$(this).data('type');
    //请求url
    var url= '/ajax/'+type+'/'+id+'/attach/'+status;
    var that=this;
    //当前数量
    var num=($(this).text()=='' || $(this).text()==null) ? 0 : parseInt($(this).text());

        //变化后的数量
        //status=1 就是从没收藏到已收藏 数量+1 否则就是-1
        num= status ? num+1 : num-1;

        if(num<0){
          num=0;
        }
      $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      })
      $.ajax({
        url:url,
        type:"GET",
        success:function(data){
           if (data.code == 0) {
            //收藏成功 取消收藏成功
            //更新数量
            $(that).text(num);
            //给出提示
             layer.open({
              content: data.message
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
             //更新状态
           $(that).data('status',status);
           console.log(status);
           
           if(status==1){

              $(that).attr('style',"background:url(../../images/collect_color.png) no-repeat left center;")
              
              
              console.log(1);
           }else{
              $(that).attr('style',"background:url(../../images/collect.png) no-repeat left center;")
              
           } 
          }else{
             layer.open({
              content: data.message
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });     
          }
        }
      });
});


//个人中心取消收藏
$('.collect_cancle').click(function(){
    
    var that=this;
    var id=$(that).data('id');
    var type=$(that).data('type');
    var coms=$('#companys_num').text();
       $.ajaxSetup({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
      })
      $.ajax({
        url:'/ajax/'+type+'/'+id+'/attach/0',
        type:"GET",
        success:function(data){
          
           if (data.code == 0) {
            //取消收藏成功
             //更新界面
             layer.open({
              content: data.message
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
             
            $(that).parent().remove();
            console.log(coms);
            coms=coms-1;
            $('#companys_num').text(coms);
          }else{
             layer.open({
              content: data.message
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
       
          }
        }
      });
});
    
//会员购买升级
$('.buy_btn').click(function(){
       var type=$(this).data('type');
       var checked=$(".service").prop("checked");
       var member_card_choose=$('.member-card').hasClass('active');
       var price=parseFloat($('.member-card.active').data('price'));
       var member_id=$('.member-card.active').data('id');
       if(!member_card_choose){
            layer.open({
              content: '请选择会员'
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
            return false;
       }

        if (!checked) {
             layer.open({
              content: '请查看服务条例'
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
            return false;
        }

        if(price<=0 || price=='' ){
            layer.open({
              content: '价格不能为0或者价格格式错误'
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
            return false;
        }

        $.ajaxSetup({
          headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
        });
        $.ajax({
          url:'/memberbuy/'+type,
          type:"GET",
          data:{
            price:price,
            member_id:member_id
          },
          success:function(data){
            if(data.code==0){
                   if (typeof WeixinJSBridge === 'undefined') { 
                      if (document.addEventListener) {
                        document.addEventListener('WeixinJSBridgeReady', onBridgeReady(data.message), false)
                      } else if (document.attachEvent) {
                        document.attachEvent('WeixinJSBridgeReady', onBridgeReady(data.message))
                        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady(data.message))
                      }
                    } else {
                      onBridgeReady(data.message)
                    }
            }
          }
      });
});



 function onBridgeReady(data) {
      data = JSON.parse(data)
      var that = this;
      /* global WeixinJSBridge:true */
      WeixinJSBridge.invoke(
        'getBrandWCPayRequest', {
          'appId': data.appId, // 公众号名称，由商户传入
          'timeStamp': data.timeStamp, // 时间戳，自1970年以来的秒数
          'nonceStr': data.nonceStr, // 随机串
          'package': data.package,
          'signType': data.signType, // 微信签名方式：
          'paySign': data.paySign // 微信签名
        },
        function (res) {
          // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回ok，但并不保证它绝对可靠。
          if (res.err_msg === 'get_brand_wcpay_request:ok') {
            layer.open({
              content: '支付成功'
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
            location.href="/usercenter";
          } else {
            
            
            layer.open({
              content: '支付失败,错误信息: ' + res.err_msg
              ,skin: 'msg'
              ,time: 2 //2秒后自动关闭
            });
          }
        }
      )
    }

var _praisetimes=0;
var _dispraisetimes=0;
/**
 * 
 * @param  {类型}     type        [传dianzan(点赞)或者cai(踩)]
 * @param  {项目id}   project_id  [description]
 * @return {[type]}               [description]
 */
function project_action(type,project_id,obj){
            var num=parseInt($(obj).find('.praise-txt').text());
            num=num+1;
            var ajaxdata=type=='dianzan'?
            {
              dianzan:num
              
            }:{
              cai:num
               
            };
            if(type=='dianzan'){
                   _praisetimes++;
                    if(_praisetimes>1){
                      return false;
                    }
                  }else{
                    _dispraisetimes++;
                    if(_dispraisetimes>1){
                      return false;
                    }
                  }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:'/ajax/project/'+project_id+'/update',
                type:"GET",
                data:ajaxdata,
                success: function(data) {
                    if(data.code==0){
                      //操作成功 更新前端数量
                      if(type=='dianzan'){
                        
                            var praise_img = $("#praise-img");
                            var text_box = $("#add-num");
                            $('#praise').html("<img src='/images/like_color.png' id='praise-img' class='animation' />");
                            
                            text_box.show().html("<em class='add-animation'>+1</em>");

                            $(".add-animation").addClass("hover");
                          }else{
                            var praise_img = $("#dispraise-img");
                            var text_box = $("#disadd-num");
                            $('#dispraise').html("<img src='/images/dislike_color.png' id='dispraise-img' class='animation' />");
                            text_box.show().html("<em class='add-animation'>+1</em>");
                            $(".add-animation").addClass("hover");
                          }
                      $(obj).find('.praise-txt').text(num);
                         
                      
                    }else{
                      //操作失败 给出提示
                      layer.open({
                          content: data.message
                          ,skin: 'msg'
                          ,time: 2 //2秒后自动关闭
                        });
                    }
                }
              });
}
