
@extends('layouts.app')

@section('content')
    <div class="container-fluid" style="padding: 30px 15px;">
        <div class="row">
            <div class="col-sm-3 col-lg-2">
                <ul class="nav nav-pills nav-stacked nav-email">
                    <li class="{{ Request::is('zcjy/wechat/menu/menu') ? 'active' : '' }}">
                        <a href="{!! route('wechat.menu') !!}">
                            <span class="badge pull-right"></span>
                            <i class="fa fa-user"></i> 菜单设置
                        </a>
                    </li>
                    <li class="{{ Request::is('zcjy/wechat/reply*') ? 'active' : '' }}">
                        <a href="{!! route('wechat.reply') !!}">
                            <span class="badge pull-right"></span>
                            <i class="fa fa-users"></i> 回复消息
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-sm-9 col-lg-10">
                <div class="content pdall0-xs">
                    <div class="box box-primary form">
                        <div class="box-body">
                            <div class="container">
                                <div class="row">
                                    <div style="margin-top: 10px;">
                                        <a type="button" class="btn btn-default" href="/zcjy/wechat/reply">关键词自动回复</a>
                                        <a type="button" class="btn btn-default" href="/zcjy/wechat/reply/rpl-follow">被关注时回复</a>
                                        <button type="button" class="btn btn-flat btn-primary">无匹配时回复</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- 右侧菜单设置 -->
                                    <div class="col-lg-12 col-md-12" style="padding: 0; margin-top: 10px;">
                                        <div class="nav-tabs-custom" id="nav-tabs-custom" v-if='canseen'>
                                            <ul class="nav nav-tabs">
                                                <!-- 顶部切换按钮 -->
                                                <li v-bind:class="{ active: isText }" @click=changeType('text')><a href="#tab_3" data-toggle="tab" aria-expanded="true"><i class="fa fa-comments"></i> <span>文字</span></a></li>
                                                <!--li v-bind:class="{ active: isImage }" @click=changeType('image')><a href="#tab_4" data-toggle="tab" aria-expanded="false"><i class="fa fa-camera-retro"></i> <span>图片</span></a></li>
                                                <li v-bind:class="{ active: isVoice }" @click=changeType('voice')><a href="#tab_5" data-toggle="tab" aria-expanded="false"><i class="fa fa-volume-down"></i> <span>语音</span></a></li>
                                                <li v-bind:class="{ active: isVideo }" @click=changeType('video')><a href="#tab_6" data-toggle="tab" aria-expanded="true"><i class="fa fa-caret-square-o-right"></i> <span>视频</span></a></li-->
                                            </ul>
                                            <div class="tab-content">
                                                <!-- 文本类型 -->
                                                <div class="tab-pane" v-bind:class="{ active: isText }" id="tab_3">
                                                    <div id="texteditor" rowspan='5'></div>
                                                </div><!-- /.tab-pane -->
                                                <!-- 图片 -->
                                                <div class="tab-pane" v-bind:class="{ active: isImage }" id="tab_4">
                                                    <material-selector :item = media :display_type=display_type class="m-type-image"></material-selector>
                                                </div><!-- /.tab-pane -->
                                                <!-- 声音 -->
                                                <div class="tab-pane" v-bind:class="{ active: isVoice }" id="tab_5">
                                                    <material-selector :item = media :display_type=display_type class="m-type-voice"></material-selector>
                                                </div><!-- /.tab-pane -->
                                                <!-- 视频 -->
                                                <div class="tab-pane" v-bind:class="{ active: isVideo }" id="tab_6">
                                                    <material-selector :item = media :display_type=display_type class="m-type-video"></material-selector>
                                                </div><!-- /.tab-pane -->
                                                </div><!-- /.tab-content -->
                                                <div class="tc col-md-12">
                                                    <div class="row" style="padding-bottom: 20px; margin-top: 20px;">
                                                        <div class="btn btn-primary" @click="save">保存</div>
                                                        <div class="btn" @click="cancel" v-if='cancancel'>删除</div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                <!-- Popup itself
                <div id="test-popup" class="media-popup mfp-hide">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">选择素材</h4>
                            </div>
                            <div class="modal-body modal-body-matiral">
                                <div class="infinitescroll" style="overflow: hidden;">
                                </div>
                            </div>
                            <div class="addmore btn" >点击此处加载更多素材</div>
                            <div id="navigation"><a href="/zcjy/wechat/material/lists?page=1&type=image"></a> </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="save_material_selection">保存</button>
                            </div>
                        </div>
                    </div>
                </div> -->
@endsection

@section('scripts')

    <!-- 素材选择 -->
    <template id="material-selector">
        <div>
            <!-- 当前选择的素材 -->
            <div class="material-item-article material-item material-selection" v-show="materialSelected"> </div>
            <a class="material-item-article material-item open-popup-link" href="#test-popup">
                <div class="img"><i class="fa fa-plus"></i></div>
                <p class="tc lite-gray">从素材库中选择</p>
            </a>
        </div>
    </template>

    <script src="{{ asset('vendor/vue.js') }}"></script>
    <script src="{{ asset('vendor/vuex.min.js') }}"></script>
    <script src="{{ asset('vendor/wechat-editor.js') }}"></script>
    <script src="{{ asset('vendor/underscore-min.js') }}"></script>
    <script src="{{ asset('vendor/jquery.infinitescroll.min.js') }}"></script>
    <script type="text/javascript">
  
        $(document).ready(function(){
            //数据状态保存
            const store = new Vuex.Store({
                state: {
                    page: 1,    //分页加载media的页数
                    type: 'text',   //分页加载的类型
                    mediaselection: {img_url:null, name: null, type: null, media_id: null,} //用户选择的media
                },
                mutations: {
                    pageInfo (state, payload) {
                        state.page = payload.page;
                        state.type = payload.type;
                    },
                    selectionInfo (state, payload) {
                        state.mediaselection.img_url = payload.img_url;
                        state.mediaselection.name = payload.name;
                        state.mediaselection.type = payload.type;
                        state.mediaselection.media_id = payload.media_id;
                    }
                }
            })

            //文本编辑器
            var wechatEditor = new WeChatEditor($('#texteditor'), {textarea: 'text'});

            //加载更多资源进行选择
            $('div.addmore').on('click', function () {
                LoadMarerial();
            })

            //保存资源选择
            $('#save_material_selection').on('click', function(){
                //展示选择的资源
                display_setting_material();
                //关闭弹窗
                $('.mfp-close').click();
            })

            //素材选择
            Vue.component('material-selector', {
                template: "#material-selector",
                props: ['item', 'display_type'],
                data: function () {
                    return {
                    };
                },
                methods: {
                },
                computed: {
                    materialSelected: function(){
                        return this.display_type == store.state.mediaselection.type;
                    }
                },
                mounted (){
                    _self = this;
                    //显示当前用户设置的资源
                    display_setting_material();

                    //初始化资源选择弹出框
                    /*
                    $('.open-popup-link').magnificPopup({
                        type:'inline',
                        midClick: true, // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
                        callbacks: {
                            open: function() {
                                //设置资源请求信息
                                store.commit('pageInfo', {type: _self.display_type, page: 1});
                                //清空已经加载的内容
                                $('.infinitescroll').empty();
                                LoadMarerial();
                            },
                            close: function() {
                              // Will fire when popup is closed
                            }
                            // e.t.c.
                        }
                    });
                    */
                }
            });

            //菜单选择标签页
            var mediaVue = new Vue({
                el: '#nav-tabs-custom',
                store,
                data: {
                    media: null, //media数据
                    display_type: 'text', //tab显示类型
                    canseen: true,
                    view_url: '',
                    text: '',
                    menu: {},
                    cancancel: false,
                },
                computed: {
                    isText: function () {
                        return this.display_type == 'text';
                    },
                    isView: function () {
                        return this.display_type == 'view';
                    },
                    isArticle: function () {
                        return this.display_type == 'article';
                    },
                    isImage: function () {
                        return this.display_type == 'image';
                    },
                    isVoice: function () {
                        return this.display_type == 'voice';
                    },
                    isVideo: function () {
                        return this.display_type == 'video';
                    },
                },
                created:function(){
                    //this.refreshMenu();
                },
                mounted () {
                    _self = this;
                    //加载现有的配置
                    $.ajax({
                        url:'/zcjy/wechat/reply/no-match-reply',   //获取菜单信息
                        type:'GET', //GET
                        async:true,    //或false,是否异步
                        timeout:5000,    //超时时间
                        dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text

                        success:function(data,textStatus,jqXHR){
                            //console.log('关注回复内容');
                            //console.log(data);
                            if (data) {
                                _self.cancancel = true;
                                //console.log(data.content[0]);
                                _self.media = data.content[0];
                                _self.display_type =  _self.media.type;
                                
                                //$('.wechat-editor-content').text( _self.media.content);
                                $('.wechat-editor-content').empty();
                                setTimeout(function(){
                                    $('.wechat-editor-content').append(wechatEditor.textToEmotion(_self.media.content));
                                }, 500);
                                //资源类型变换后，更新加载信息
                                store.commit('pageInfo', {type:  _self.media.type, page: 1});

                                store.commit('selectionInfo', {img_url: _self.media.source_url, name:  _self.media.title, type:  _self.media.type, media_id:  _self.media.media_id,});
                                
                                display_setting_material();
                                //清空已经加载的内容
                                $('.infinitescroll').empty();
                                //LoadMarerial(); 
                                
                            }else{
                               //没有设置
                            }
                            
                        },
                        error:function(xhr,textStatus){
                            console.log('错误')
                            console.log(xhr)
                            console.log(textStatus)
                        },
                    });
                },
                methods: {
                    changeType: function (type) {
                        this.display_type = type;
                    },
                    cancel: function () {

                        layer.confirm('删除后，关注该公众号的用户将不再接收该回复，确定删除？', {
                            btn: ['确认','取消'] //按钮
                        }, function(){
                            //向服务器请求删除数据
                            $.ajax({
                                url:'/zcjy/wechat/reply/delete-event/no-match',   //获取菜单信息
                                type:'GET', //GET
                                async:true,    //或false,是否异步
                                timeout:5000,    //超时时间
                                dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                                beforeSend:function(xhr){
                                },
                                success:function(data,textStatus,jqXHR){
                                    if (data) {
                                        layer.confirm('删除成功', {
                                            btn: ['确认','取消'] //按钮
                                        }, function(){
                                            _self.cancancel = false;
                                            _self.media = null;
                                            _self.display_type =  'text';
                                            $('.wechat-editor-content').text('');
                                            //资源类型变换后，更新加载信息
                                            store.commit('pageInfo', {type:  'text', page: 1});
                                            store.commit('selectionInfo', {img_url: null, name:  '', type:  'text', media_id:  null,});
                                            //清空已经加载的内容
                                            $('.infinitescroll').empty();
                                            
                                        });
                                    } else {
                                        layer.msg("删除失败!", {icon: 5});
                                    }
                                },
                                error:function(xhr,textStatus){
                                    console.log(xhr);
                                    console.log(textStatus);
                                    layer.msg("删除失败!", {icon: 5});
                                },
                                complete:function(){
                                }
                            });


                            //向服务器请求删除数据
                            $.ajax({
                                url:'/zcjy/wechat/reply/delete-event/follow',   //获取菜单信息
                                type:'GET', //GET
                                async:true,    //或false,是否异步
                                timeout:5000,    //超时时间
                                dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                                beforeSend:function(xhr){
                                },
                                success:function(data,textStatus,jqXHR){
                                    if (data) {
                                        layer.confirm('删除成功', {
                                            btn: ['确认','取消'] //按钮
                                        }, function(){
                                           _self.cancancel = false;
                                            _self.media = null;
                                            _self.display_type =  'text';
                                            $('.wechat-editor-content').text('');
                                            //资源类型变换后，更新加载信息
                                            store.commit('pageInfo', {type:  'text', page: 1});
                                            store.commit('selectionInfo', {img_url: null, name:  '', type:  'text', media_id:  null,});
                                            //清空已经加载的内容
                                            $('.infinitescroll').empty();
                                            
                                        });
                                    } else {
                                        layer.msg("删除失败!", {icon: 5});
                                    }
                                },
                                error:function(xhr,textStatus){
                                    console.log(xhr);
                                    console.log(textStatus);
                                    layer.msg("删除失败!", {icon: 5});
                                },
                                complete:function(){
                                }
                            });
                        });

                        swal({
                            title: "确认删除?", 
                            text: "删除后，关注该公众号的用户将不再接收该回复，确定删除？",
                            type: "warning",
                            showCancelButton: true,
                            cancelButtonText: "取消",
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "确认!",
                            closeOnConfirm: false
                        },
                        function(){
                            
                        });
                        

                    },
                    save: function () {
                        //更新菜单信息type菜单类型（必须）  content（文字回复内容，可选）  media_id（资源编号，可选）  url（view链接，可选）
                        var menu_id = this.menu.id;
                        var type = this.display_type;
                        var text = $('textarea[name=text]').text();
                        var media_id = store.state.mediaselection.media_id;
                        var view_url = this.view_url;
                        var postdata = {
                            name: '无匹配回复',
                            type: this.display_type,
                            text: $('textarea[name=text]').text(),
                            media_id: media_id,
                            reply_type: 'no-match',

                        }
                        $.ajax({
                            url: '/zcjy/wechat/reply/save-event-reply',   //获取菜单信息
                            type:'GET', //GET
                            data: postdata,
                            async:true,    //或false,是否异步
                            timeout:5000,    //超时时间
                            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                            beforeSend:function(xhr){
                                console.log(xhr)
                                console.log('发送前')
                            },
                            success:function(data,textStatus,jqXHR){
                                _self.cancancel = true;
                                layer.msg("保存成功!", {icon: 1});
                            },
                            error:function(xhr,textStatus){
                                console.log('错误')
                                console.log(xhr)
                                console.log(textStatus)
                            },
                            complete:function(){
                                console.log('结束')
                            }
                        });
                    }
                }
            })
    
            //加载资源
            function LoadMarerial() {
                var requesturl = '/zcjy/wechat/material/lists?page='+ store.state.page + '&type='+ store.state.type;
                console.log(requesturl);
                $.ajax({
                    url: requesturl,
                    type:'GET', //GET
                    async:true,    //或false,是否异步
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    beforeSend:function(xhr){
                        console.log(xhr)
                        console.log('发送前')
                    },
                    success:function(data,textStatus,jqXHR){
                        console.log(data['data']);
                        if(data['current_page'] == data['last_page']){
                            //最后一页
                            $('.addmore').text('没有更多的素材可供加载').delay(2000).hide(2000);
                        }
                        //store.state.page = ++store.state.page;
                        store.commit('pageInfo', {type: store.state.type, page: ++store.state.page})
                        //组装HTML
                        var items = data['data'];
                        var items_length = items.length;
                        for (var i = 0; i < items_length; i++) {
                            switch (items[i].type){
                                case 'article':
                                    $('.infinitescroll').append(
                                    "<div class='material-item-article material-item' media_id='" + items[i].media_id + "' media_tpye='" + items[i].type + "'>\
                                        <div class='img'><img src='"+ items[i].cover_url +"'></div> \
                                        <p>"+ items[i].title +"</p>\
                                    </div>"
                                    );
                                break;
                                case 'image':
                                    $('.infinitescroll').append(
                                    "<div class='material-item-article material-item' media_id='" + items[i].media_id + "' media_tpye='" + items[i].type + "'>\
                                        <div class='img'><img src='"+ items[i].source_url +"'></div> \
                                        <p>"+ items[i].title +"</p>\
                                    </div>"
                                    );
                                break;
                                case 'voice':
                                    $('.infinitescroll').append(
                                    "<div class='material-item-article material-item' media_id='" + items[i].media_id + "' media_tpye='" + items[i].type + "'>\
                                        <div class='img-voice'><p>"+ items[i].title +"</p></div></div>"
                                    );
                                break;
                                case 'video':
                                    $('.infinitescroll').append(
                                    "<div class='material-item-article material-item' media_id='" + items[i].media_id + "' media_tpye='" + items[i].type + "'>\
                                        <div class='img-video'><p>"+ items[i].title +"</p></div></div>"
                                    );
                                break;
                            }
                        }
                        $('.infinitescroll .material-item-article').unbind('click');
                        $('.infinitescroll .material-item-article').on('click', function(){
                            $('.infinitescroll .material-item-article').removeClass('imclicked');
                            $(this).addClass('imclicked');
                            store.commit('selectionInfo', {
                                img_url:$(this).find('img').attr('src'), 
                                name: $(this).find('p').text(), 
                                type: $(this).attr('media_tpye'), 
                                media_id: $(this).attr('media_id')
                            });
                            console.log('你选择的是：'+ store.state.mediaselection.media_id);
                        })

                    },
                    error:function(xhr,textStatus){
                        console.log('错误')
                        console.log(xhr)
                        console.log(textStatus)
                    },
                    complete:function(){
                        console.log('结束')
                    }
                })
            }

            function display_setting_material() {
                
                if (store.state.mediaselection.name == null) {
                    return ;
                }
                $('.m-type-'+store.state.mediaselection.type+' .material-selection').empty();

                switch (store.state.mediaselection.type){
                    case 'article':
                        $('.m-type-'+store.state.mediaselection.type+' .material-selection').append(
                        "<div><div class='img'><img src='"+ store.state.mediaselection.img_url +"'></div> \
                            <p>"+ store.state.mediaselection.name +"</p></div>"
                        );
                    break;
                    case 'image':
                        $('.m-type-'+store.state.mediaselection.type+' .material-selection').append(
                        "<div class='img'><img src='"+ store.state.mediaselection.img_url +"'></div> \
                            <p>"+ store.state.mediaselection.name +"</p>"
                        );
                    break;
                    case 'voice':
                        $('.m-type-'+store.state.mediaselection.type+' .material-selection').append(
                        "<div class='img-voice'><p>"+ store.state.mediaselection.name +"</p></div>"
                        );
                    break;
                    case 'video':
                        $('.m-type-'+store.state.mediaselection.type+' .material-selection').append(
                        "<div class='img-video'><p>"+ store.state.mediaselection.name +"</p></div>"
                        );
                    break;
                }
            }
        });
    </script>
@endsection