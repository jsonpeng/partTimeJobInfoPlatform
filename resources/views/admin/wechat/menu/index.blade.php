
@extends('layouts.app')


@section('css')
    <style type="text/css">
    </style>
@endsection

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
                    <li class="{{ Request::is('zcjy/wechat/reply') ? 'active' : '' }}">
                        <a href="{!! route('wechat.reply') !!}">
                            <span class="badge pull-right"></span>
                            <i class="fa fa-users"></i> 回复消息
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-sm-9 col-lg-10">
                <div class="container">
                    <div class="row">
                        <!-- 左侧菜单列表 -->
                        <section class="col-lg-4 col-md-4">
                            <h4>菜单设置</h4>
                            <div id="app" class="row">
                                <menu-item v-for="item in menuitem" :item=item></menu-item>
                                <add-parent-menu v-if="seen" :menuitem=menuitem></add-parent-menu>
                            </div>
                        </section>

                        <!-- 右侧菜单设置 -->
                        <div class="col-lg-8 col-md-8" >

                            <div class="nav-tabs-custom display-none" id="nav-tabs-custom" v-if='canseen' style="margin-top: 80px;">
                                <ul class="nav nav-tabs">
                                    <h4 style="margin-left: 10px; color: #18c74a">@{{menu.name}}</h4>
                                    <!-- 顶部切换按钮 -->
                                    <li v-bind:class="{ active: isView }" @click=changeType('view')><a href="#tab_1" data-toggle="tab" aria-expanded="false"> <i class="fa fa-chain"></i> <span>跳转网页</span></a></li>
                                    <!--li v-bind:class="{ active: isArticle }" @click=changeType('article')><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-photo"></i> <span>图文消息</span></a></li-->
                                    <li v-bind:class="{ active: isText }" @click=changeType('text')><a href="#tab_3" data-toggle="tab" aria-expanded="true"><i class="fa fa-comments"></i> <span>文字</span></a></li>
                                    <!--li v-bind:class="{ active: isImage }" @click=changeType('image')><a href="#tab_4" data-toggle="tab" aria-expanded="false"><i class="fa fa-camera-retro"></i> <span>图片</span></a></li>
                                    <li v-bind:class="{ active: isVoice }" @click=changeType('voice')><a href="#tab_5" data-toggle="tab" aria-expanded="false"><i class="fa fa-volume-down"></i> <span>语音</span></a></li>
                                    <li v-bind:class="{ active: isVideo }" @click=changeType('video')><a href="#tab_6" data-toggle="tab" aria-expanded="true"><i class="fa fa-caret-square-o-right"></i> <span>视频</span></a></li-->
                                </ul>
                                <div class="tab-content">
                                    <!-- view类型 -->
                                    <div class="tab-pane" v-bind:class="{ active: isView }" id="tab_1">
                                        <b class="lite-gray">订阅者点击该子菜单会跳到以下链接</b>
                                        <div class="row form-group form-horizontal" style="margin-top: 15px;">
                                            <div class="col-md-3" style="text-align: center;"> <label class="control-label">页面地址</label> </div>
                                            <div class="col-md-9"> <input class="form-control" id="viewurl" v-model="view_url"></input> </div>
                                            <div class="col-md-9 col-md-offset-3 lite-gray"> 从公众号图文消息中选择 </div>
                                        </div>
                                    </div><!-- /.tab-pane -->
                                    <!-- 图文类型 -->
                                    <div class="tab-pane" v-bind:class="{ active: isArticle }" id="tab_2">
                                        <material-selector :item = media :display_type=display_type class="m-type-article"></material-selector>
                                    </div><!-- /.tab-pane -->
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
                                <div class="tc"><div class="btn-primary" style="height: 40px; line-height: 40px; margin-top: 50px; cursor: pointer" @click="save">保存</div></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- 一级菜单 -->
    <script type="text/x-template" id="menu-item">
        <div>
            <div class="box">
                <!-- 编辑菜单名称 -->
                <div class="box-header with-border" v-bind:class="classA" style="display: none;">
                    <div>
                        <div class="col-md-8"><input type="" name="" class="form-control"  v-model="item.name" maxlength="6"></div>
                         <button class="btn col-md-4" @click="changetextEnd">确定</button>
                    </div>
                </div>

                <!-- 一级菜单显示 -->
                <div class="box-header with-border" v-bind:class="classB" @click.self='editmenu' >
                    <h3 class="box-title" @click='editmenu'>@{{item.name}} <small style="color: #ccc;">一级菜单</small></h3>
                    <!-- 菜单操作 -->
                    <div class="box-tools pull-right">
                        <!-- 编辑文章 -->
                        <button class="btn btn-box-tool" @click="changetext" title="编辑"><i class="fa fa-edit"></i></button>
                        <!-- 添加子菜单 -->
                        <button class="btn btn-box-tool" @click="addmenu" title="添加二级菜单" v-if="canAddChild"><i class="fa fa-plus"></i></button>
                        <!-- 删除子菜单 -->
                        <button class="btn btn-box-tool" @click="deletemenu" title="删除"><i class="fa fa-times"></i></button>
                    </div>
                </div>

                <!-- 二级菜单显示 -->
                <div class="box-body" style="display: block;">
                    <!-- 二级菜单 -->
                    <menu-child v-for="pitem in item['sub_buttons']" :item=pitem :key="pitem.id"></menu-child>
                    <!-- 新建二级菜单 -->
                    <add-child-menu v-if="seen" :submenus=item.sub_buttons.length :parent_id=item.id></add-child-menu>
                </div><!-- /.box-body -->
            </div>
            
        </div>
    </script>
    
    <!-- 二级菜单 -->
    <template id="menu-child">
        <div class="box">
            <!-- 编辑菜单名称 -->
            <div class="box-header with-border" v-bind:class="classA" style="display: none;">
                <div>
                    <div class="col-md-8"><input type="" name="" class="form-control"  v-model="item.name" maxlength="7"></div>
                     <button class="btn col-md-4" @click="changetextEnd">确定</button>
                </div>
            </div>

            <!-- 二级菜单显示 -->
            <div class="box-header" v-bind:class="classB" @click.self='editmenu'>
                <h4 class="box-title" @click='editmenu'>@{{item.name}} <small style="color: #ccc;">二级菜单</small></h4>
                <div class="box-tools pull-right">
                    <!-- 编辑文章 -->
                    <button class="btn btn-box-tool" @click="changetext" title="编辑"><i class="fa fa-edit"></i></button>
                    <!-- 删除子菜单 -->
                    <button class="btn btn-box-tool" @click="deletemenu" title="删除"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
    </template>

    <!-- 添加一级菜单 -->
    <template id="add-parent-menu">
        <div class="add-parent-menu">
            <div class="col-md-6 input area displaynone"><input type="" name="" class="form-control" placeholder="请输入菜单名称" v-model="name" maxlength="6"></div>
            <button class="btn btn-primary col-md-3 confirm displaynone" @click="confirm">确定</button>
            <button class="btn col-md-3 cancel displaynone" @click="cancel">取消</button>
            <button class="btn col-md-12 add" @click="add" style="margin-top: 15px;"> 添加一级菜单 </button>
        </div>
    </template>

    <!-- 添加二级菜单 -->
    <template id="add-child-menu">
        <div class="add-child-menu">
            <div class="col-md-6 input area"><input type="" name="" class="form-control" placeholder="请输入菜单名称" v-model="name" maxlength="7"></div>
            <button class="btn btn-primary col-md-3 confirm" @click="confirm">确定</button>
            <button class="btn col-md-3 cancel" @click="cancel">取消</button>
        </div>
    </template>
    
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
            $('#nav-tabs-custom').removeClass('display-none');
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
            //定义组件
            var Event = new Vue();

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
            })

            //菜单选择标签页
            var mediaVue = new Vue({
                el: '#nav-tabs-custom',
                store,
                data: {
                    media: null, //media数据
                    display_type: null, //tab显示类型
                    canseen: false,
                    view_url: '',
                    text: '',
                    menu: {},

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
                    //接受传递过来的菜单编辑消息
                    Event.$on("edit_menu", function (item) {
                        _self = this;
                        _self.menu = item;
                        //有二级菜单的一级菜单
                        if (item.type == 'click' && item.key == null) {
                            this.canseen = false;
                            return ;
                        } else {
                            //延迟显示，防止闪烁
                            //this.canseen = true;
                        }
                        //显示菜单设置
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url:'/zcjy/wechat/menu/single/'+item.id,   //获取菜单信息
                            type:'GET', //GET
                            async:true,    //或false,是否异步
                            timeout:5000,    //超时时间
                            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                            beforeSend:function(xhr){
                                console.log(xhr)
                                console.log('发送前')
                            },
                            success:function(data,textStatus,jqXHR){
                                console.log(data);
                                _self.menu = data;
                                if (data.type == 'view') {
                                    //view直接显示网址就好
                                    _self.display_type = 'view';
                                    _self.canseen = true;
                                    $('#nav-tabs-custom').show();
                                    _self.view_url= data.key
                                } else {
                                    //EVENT
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });
                                    $.ajax({
                                        url:'/zcjy/wechat/material/by-event-key/'+data.key,   //获取菜单信息
                                        type:'GET', //GET
                                        async:true,    //或false,是否异步
                                        timeout:5000,    //超时时间
                                        dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                                        beforeSend:function(xhr){
                                            //console.log(xhr)
                                            //console.log('发送前')
                                        },
                                        success:function(data,textStatus,jqXHR){
                                            _self.media = data;
                                            _self.display_type = data.type;
                                            _self.canseen = true;
                                            $('#nav-tabs-custom').show();
                                            _self.view_url= '';
                                            $('.wechat-editor-content').empty();
                                            if (data.type == 'text') {
                                                setTimeout(function(){
                                                    $('.wechat-editor-content').append(wechatEditor.textToEmotion(data.content));
                                                }, 500);
                                            }
                                            
                                            //$('textarea[name=text]').text(data.content);
                                            //资源类型变换后，更新加载信息
                                            store.commit('pageInfo', {type: data.type, page: 1});
                                            if (data.type == 'article') {
                                                store.commit('selectionInfo', {img_url:data.cover_url, name: data.title, type: data.type, media_id: data.media_id,});
                                            } else {
                                                store.commit('selectionInfo', {img_url:data.source_url, name: data.title, type: data.type, media_id: data.media_id,});
                                            }
                                            
                                            display_setting_material();
                                            //清空已经加载的内容
                                            $('.infinitescroll').empty();
                                            //LoadMarerial();
                                        },
                                        error:function(xhr,textStatus){
                                            //console.log('错误')
                                            //console.log(xhr)
                                            //console.log(textStatus)
                                        },
                                        complete:function(){
                                            //console.log('结束')
                                        }
                                    });
                                }
                            },
                            error:function(xhr,textStatus){
                                //console.log('错误')
                                //console.log(xhr)
                                //console.log(textStatus)
                            },
                            complete:function(){
                                //console.log('结束')
                            }
                        });
                        
                        //console.log(item);
                        //alert(a.name);
                    }.bind(this));
                },
                methods: {
                    changeType: function (type) {
                        this.display_type = type;
                    },
                    save: function () {
                        //更新菜单信息type菜单类型（必须）  content（文字回复内容，可选）  media_id（资源编号，可选）  url（view链接，可选）
                        var menu_id = this.menu.id;
                        var type = this.display_type;
                        var content = $('textarea[name=text]').text();
                        var media_id = store.state.mediaselection.media_id;
                        var view_url = this.view_url;

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url:'/zcjy/wechat/menu/update-menu-event?type='+type+'&menu_id='+menu_id+'&content='+content+'&media_id='+media_id+'&view_url='+view_url,   //获取菜单信息
                            type:'GET', //GET
                            async:true,    //或false,是否异步
                            timeout:5000,    //超时时间
                            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                            beforeSend:function(xhr){
                                //console.log(xhr)
                                //console.log('发送前')
                            },
                            success:function(data,textStatus,jqXHR){
                                layer.msg("保存成功!", {icon: 1});
                                //console.log(data);
                                //swal("保存成功!", "", "success"); 
                            },
                            error:function(xhr,textStatus){
                                //console.log('错误')
                                //console.log(xhr)
                                //console.log(textStatus)
                            },
                            complete:function(){
                                //console.log('结束')
                            }
                        });
                    }
                }
            })

            
            //二级菜单
            Vue.component('menu-child', {
                template: "#menu-child",
                props: ['item'],
                data: function () {
                    return {
                        classA: 'level02-'+this.item.id,
                        classB: 'level02-header-'+this.item.id
                    };
                },
                methods: {
                    editmenu: function () {
                        Event.$emit("edit_menu", this.item);
                    },
                    changetext: function () {
                        $('.level02-'+this.item.id).show();
                        $('.level02-header-'+this.item.id).hide();
                    },
                    changetextEnd: function () {
                        $('.level02-'+this.item.id).hide();
                        $('.level02-header-'+this.item.id).show();
                        update_menu_name(this.item.id, this.item.name);
                    },
                    deletemenu: function() {
                        _self = this;

                        layer.confirm('确认删除菜单吗？该操作不可恢复!', {
                            btn: ['确认','取消'] //按钮
                        }, function(){
                            delete_menu(_self.item.id);
                        });
                    }
                }
            })

            //添加子菜单按钮
            Vue.component('add-child-menu', {
                template: "#add-child-menu",
                props: ['parent_id', 'submenus'],
                data: function() {
                    return {name: ''}
                },
                methods: {
                    confirm: function(){
                        add_menu(this.parent_id, this.name, this.submenus);
                        Event.$emit("hidden_create_level2_menu");
                    },
                    cancel: function() {
                        Event.$emit("hidden_create_level2_menu");
                        this.name = '';
                    },
                }
            })

            //添加一级菜单按钮
            Vue.component('add-parent-menu', {
                template: "#add-parent-menu",
                props: ['menuitem'],
                data: function() {
                    return {name: ''}
                },
                mounted () {
                    //接收A组件的数据
                    Event.$on("level01_menu_added", function (a) {
                        this.cancel();
                    }.bind(this));
                },
                methods: {
                    add: function () {
                        $('.add-parent-menu .confirm, .add-parent-menu .cancel, .add-parent-menu .area').show();
                        $('.add-parent-menu .add').hide();
                    },
                    confirm: function(){
                        add_menu(0, this.name, this.submenus);
                    },
                    cancel: function() {
                        $('.confirm, .cancel, .area').hide();
                        $('.add').show();
                        this.name = '';
                    },
                }
            })
            
            Vue.component('menu-item', {
                template: '#menu-item',
                props: ['item'],
                data: function () {
                    return {
                        classA: 'level01-'+this.item.id,
                        classB: 'level01-header-'+this.item.id,
                        seen: false,
                    };
                },
                mounted () {
                    Event.$on("hidden_create_level2_menu", function (a) {
                        this.seen = false;
                    }.bind(this));
                },
                computed: {
                    canAddChild: function () {
                       return this.item['sub_buttons'].length < 5
                    }
                },
                methods: {
                    editmenu: function () {
                        Event.$emit("edit_menu", this.item);
                    },
                    changetext: function () {
                        $('.level01-'+this.item.id).show();
                        $('.level01-header-'+this.item.id).hide();
                    },
                    changetextEnd: function () {
                        $('.level01-'+this.item.id).hide();
                        $('.level01-header-'+this.item.id).show();
                        update_menu_name(this.item.id, this.item.name);
                    },
                    addmenu: function() {
                        this.seen = true;
                    },
                    deletemenu: function() {
                        _self = this;
                        layer.confirm('确认删除菜单吗？该操作不可恢复!', {
                            btn: ['确认','取消'] //按钮
                        }, function(){
                            delete_menu(_self.item.id);
                        });
                    }
                }
            })

            var app = new Vue({
                el: '#app',
                data: {
                    menuitem: [],
                },
                computed: {
                    seen: function () {
                       return this.menuitem.length < 3
                    }
                },
                created:function(){
                    this.refreshMenu();
                },
                mounted () {
                    //接收A组件的数据
                    Event.$on("refresh_menu_list", function (a) {
                        this.refreshMenu();
                    }.bind(this));
                },
                methods: {
                    reverseMessage: function () {
                        //console.log(this.menuitem);
                        //alert(this.menuitem);
                    },
                    refreshMenu: function () {
                        var _self=this;
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url:'/zcjy/wechat/menu/lists',
                            type:'GET', //GET
                            async:true,    //或false,是否异步
                            timeout:5000,    //超时时间
                            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                            beforeSend:function(xhr){
                                //console.log(xhr)
                                //console.log('发送前')
                            },
                            success:function(data,textStatus,jqXHR){
                                _self.menuitem = data;
                                /*
                                for (var i = data.length - 1; i >= 0; i--) {
                                    console.log(data[i]);
                                    for (var j = data[i].sub_buttons.length - 1; j >= 0; j--) {
                                        console.log(data[i].sub_buttons[j]);
                                    }
                                }
                                */
                            },
                            error:function(xhr,textStatus){
                                //console.log('错误')
                                //console.log(xhr)
                                //console.log(textStatus)
                            },
                            complete:function(){
                                //console.log('结束')
                            }
                        })
                    }
                }
            })
    
            function delete_menu(id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:'/zcjy/wechat/menu/delete/'+id,
                    type:'GET', //GET
                    async:true,    //或false,是否异步
                    timeout:5000,    //超时时间
                    dataType:'text',    //返回的数据格式：json/xml/html/script/jsonp/text
                    beforeSend:function(xhr){
                        //console.log(xhr)
                        //console.log('发送前')
                    },
                    success:function(data,textStatus,jqXHR){
                        if (data == 'success'){
                            Event.$emit("refresh_menu_list");
                            layer.msg("删除成功!", {icon: 1});
                            //swal("删除成功!", "", "success"); 
                        }
                        else{
                            layer.msg("删除失败!", {icon: 5});
                            //swal("删除失败!", "菜单删除失败，请重试.", "error");
                        }
                    },
                    error:function(xhr,textStatus){
                        //console.log('错误')
                        //console.log(xhr)
                        //console.log(textStatus)
                    },
                    complete:function(){
                        //console.log('结束')
                    }
                })
            }

            function add_menu(parent_id, name, sort) {
              $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                $.ajax({
                    url:'/zcjy/wechat/menu/create?parent_id='+ parent_id +'&name='+ name +'&type=view&key=www.yunlike.cn&sort=0',
                    type:'GET', //GET
                    async:true,    //或false,是否异步
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    beforeSend:function(xhr){
                        //console.log(xhr)
                        //console.log('发送前')
                    },
                    success:function(data,textStatus,jqXHR){
                        //console.log(data);
                        //通知父控件
                        
                        // setTimeout(function(){
                            Event.$emit("refresh_menu_list", '添加事件');
                            Event.$emit("level01_menu_added", '添加一级菜单');
                        //}, 500);
                        layer.msg("添加成功!", {icon: 1});
                        //swal("干得好!", "你已成功的添加了菜单!", "success");
                        
                    },
                    error:function(xhr,textStatus){
                        //console.log('错误')
                        //console.log(xhr)
                        //console.log(textStatus)
                    },
                    complete:function(){
                        //console.log('结束')
                    }
                })
            }

            function update_menu_name(id, name) {
              $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                $.ajax({
                    url:'/zcjy/wechat/menu/update/'+id+'?name='+name,
                    type:'GET', //GET
                    async:true,    //或false,是否异步
                    timeout:5000,    //超时时间
                    dataType:'text',    //返回的数据格式：json/xml/html/script/jsonp/text
                    beforeSend:function(xhr){
                        //console.log(xhr)
                        //console.log('发送前')
                    },
                    success:function(data,textStatus,jqXHR){
                        if (data == 'error'){
                            layer.msg("修改失败!", {icon: 5});
                        }
                        else{
                            Event.$emit("refresh_menu_list");
                            layer.msg("修改成功!", {icon: 1});
                        }
                    },
                    error:function(xhr,textStatus){
                        //console.log('错误')
                        //console.log(xhr)
                        //console.log(textStatus)
                    },
                    complete:function(){
                        //console.log('结束')
                    }
                })
            }
            //首字母变大写
            function ucfirst(str) {
                var str = str.toLowerCase();
                str = str.replace(/\b\w+\b/g, function(word){
                  return word.substring(0,1).toUpperCase()+word.substring(1);
                });
                return str; 
            }
            //加载资源
            function LoadMarerial() {
                var requesturl = '/zcjy/wechat/material/lists?page='+ store.state.page + '&type='+ store.state.type;
                //console.log(requesturl);
                $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                $.ajax({
                    url: requesturl,
                    type:'GET', //GET
                    async:true,    //或false,是否异步
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    beforeSend:function(xhr){
                        //console.log(xhr)
                        //console.log('发送前')
                    },
                    success:function(data,textStatus,jqXHR){
                        
                        //console.log(data);
                        if(data['current_page'] == data['last_page']){
                            //最后一页
                            $('.addmore').text('没有更多的素材可供加载').delay(2000).hide(2000);
                        }else{
                            $('.addmore').text('点击此处加载更多素材').show();
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
                            //console.log('你选择的是：'+ store.state.mediaselection.media_id);
                        })

                    },
                    error:function(xhr,textStatus){
                        //console.log('错误')
                        //console.log(xhr)
                        //console.log(textStatus)
                    },
                    complete:function(){
                        //console.log('结束')
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