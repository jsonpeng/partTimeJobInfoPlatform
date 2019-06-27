$.extend({    
    /**
     * [设置指定输入的最大长度]
     * @param {[string]} attribute      [属性]
     * @param {[array]} keyword_arr     [属性关键字数组]
     * @param {[int]} length            [description]
     */
   setInputLengthByName:function(attribute,keyword_arr,length){  
        for(var i=keyword_arr.length-1;i>=0;i--){
            $('input['+attribute+'='+keyword_arr[i]+']').attr('maxlength',length);
        }
    },
    /**
     * [后台/前端 ajax请求通用接口]
     * @param  {[string]}   request_url         [请求地址]
     * @param  {Function}   callback            [成功回调]
     * @param  {Object}     request_parameters  [请求参数]
     * @param  {String}     method              [HTTP请求方法]
     * @return {[type]}                         [description]
     */
    zcjyRequest:function(request_url,callback,request_parameters = {},method = "GET"){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:request_url,
                type:method,
                data:request_parameters,
                success: function(data) {
                    console.log(data.code);
                    if(data.code == 0){
                        if(typeof(callback) == 'function'){
                            callback(data.message);
                        }
                    }
                    else{
                        callback(false);
                        if(data.code != 3){
                            layer.msg(data.message, {icon: 5});
                        }
                    }
                }
            });
    },
    /**
     * [给必填/必选字段加上提示]
     * @param  {string}  name_array [description]
     * @param  {Boolean} select     [description]
     * @return {[type]}             [description]
     */
    zcjyRequiredParam:function(name_array,select=false){
           name_array = name_array.split(',');
           select = select ? '选' : '填';
           for(var i=name_array.length-1;i>=0;i--){
                $('label[for='+name_array[i]+']').after('<span class=required>(必'+select+')</span>');
           }
    }
});

$.fn.extend({    
    /**
     * 限制number类型的输入 后期可继续扩展
     * @param 传入参数  [int/string] {整形/字符串} _lengths  [长度/类型]
     * @return 
     */
   numberInputLimit:function(_lengths){    
       $(this).bind("keyup paste",function(){
            if(_lengths <= 11){
            //替换字母特殊字符 用于整形浮点等     
            this.value=this.value.replace(/[^\d.]/g,"");
            }
           //截取最大长度 
           //针对数据库常用字符串 推荐使用191
           //针对数据库常用数量    推荐使用8 11
            if(this.value.length > _lengths){
                this.value=this.value.slice(0,_lengths);
            }
            //针对100以内 百分比
            if(_lengths == 3){
                if(this.value > 100){
                    this.value = 100;
                }
            }
            //针对商城分类
            if(_lengths == 1 || _lengths== 'category'){
                 if(this.value > 3){
                    this.value = 3;
                }
            } 
        });    
    },
     /**
     * [限制图片的长度 超过规范长度给出错误提示]
     * @param  {[int]}  _imgmaxlength [图片url最大长度]
     * @return {[type]}               [description]
     */
    imgInputLimit:function(_imgmaxlength){
        //图片长度限制
        $(this).bind('change',function(){
            //长度超出数据库规范限制
            if($(this).val().length>=_imgmaxlength){
                //置空输入框
                $(this).val("");
                //去除图片
                $(this).parent().find('img').remove();
                //给出错误提示弹框
                layer.msg("图片 不能大于 "+_imgmaxlength+" 个字符,请修改图片名称后重试", {
                            icon: 5
                });
            }
        });
    }     
});

//常用字符串
$('input[type=text]').attr('maxlength',191);
//常用手机号
$('input[name=service_tel]').numberInputLimit(11);
//分类等级
$('input[name=category_level]').numberInputLimit(1);
//百分比
$('input[name=consume_credits],input[name=credits_max],#value').numberInputLimit(3);
//价格数字
$('input[name=price],input[name=sort],input[name=product_num],input[name=inventory_default],input[name=buy_limit],input[name=expire_hour],input[name=member],input[name=market_price],input[name=freight_free_limit],input[name=cost],input[name=records_per_page],input[name=inventory_warn],input[name=given],input[name=max_count],input[name=base],input[name=sales_count],input[name=inventory],input[name=weight],input[name=amount],input[name=discount]').numberInputLimit(8);
//图片长度限制提示
$('input[name=image]').imgInputLimit(256);
//指定标签后面加上必填
// $.zcjyRequired(['name']);