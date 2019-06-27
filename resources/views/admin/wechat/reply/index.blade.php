
@extends('layouts.app')

@section('css')
    <style type="text/css">
        .alert{
            background-color: #eee;
        }
        .box-tools{
            display: block;
        }
    </style>
@stop

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
                                <div style="margin-top: 10px;">
                                    <button type="button" class="btn btn-flat btn-primary">关键词自动回复</button>
                                    <a type="button" class="btn btn-default" href="/zcjy/wechat/reply/rpl-follow">被关注时回复</a>
                                    <a type="button" class="btn btn-default" href="/zcjy/wechat/reply/rpl-no-match">无匹配时回复</a>
                                </div>

                                <div><button class="btn btn-primary" id="add-button" @click="switchDisplay(true)" v-if='canseen' style="margin-top: 20px; margin-bottom: 20px;">添加规则</button></div>

                                <!-- 右侧菜单设置 --> 
                                <div class="nav-tabs-custom row" id="nav-tabs-custom" v-if='canseen' style="margin-top: 20px;">
                                    <div style="padding: 15px;">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">规则名<small class="lite-gray">规则名最多60个字</small></label>
                                            <input type="email" v-model='ruleNmae' class="form-control" id="exampleInputEmail1" placeholder="请输入规则名" maxlength="60">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">关键子 <small class="lite-gray">多个关键字用英文逗号分隔，最多输入30字</small></label>
                                            <input type="email" v-model='ruleKeyWord' class="form-control" id="exampleInputEmail1" placeholder="请输入触发关键字" maxlength="30">
                                        </div>
                                        <div class="form-group">
                                            <label>触发方式: </label>
                                            <label style="margin-right: 15px; margin-left: 10px;">
                                                <input type="radio" v-model='ruleTriggerType' class="minimals" value="equal" /> 等于
                                            </label>
                                            <label>
                                                <input type="radio" v-model='ruleTriggerType' class="minimals" value="contain" /> 包含
                                            </label>
                                        </div>
                                    </div>
                                    <div class="box" style="padding: 15px">
                                        <label style="margin-bottom: 15px;">回复内容: </label>
                                        <replies v-for="(item, index) in replies" :item=item :pos="index"></replies>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <!-- 顶部切换按钮 -->
                                        <li><a href="#textmodal" data-toggle="modal" aria-expanded="true"><i class="fa fa-comments"></i> <span>文字</span></a></li>
                                        <!--li @click=changeType('article')><a href="#material-selector" data-toggle="modal" aria-expanded="true"><i class="fa fa-photo"></i> <span>图文消息</span></a></li>
                                        
                                        <li @click=changeType('image')><a href="#material-selector" data-toggle="modal" aria-expanded="true"><i class="fa fa-camera-retro"></i> <span>图片</span></a></li>
                                        <li @click=changeType('voice')><a href="#material-selector" data-toggle="modal" aria-expanded="true"><i class="fa fa-volume-down"></i> <span>语音</span></a></li>
                                        <li @click=changeType('video')><a href="#material-selector" data-toggle="modal" aria-expanded="true"><i class="fa fa-caret-square-o-right"></i> <span>视频</span></a></li-->
                                    </ul>
                                    
                                    <div class="tc col-md-12">
                                        <div class="row" style="padding-bottom: 20px; ">
                                            <div class="btn btn-primary" @click="save">保存</div>
                                            <div class="btn" @click="cancel">取消</div>
                                        </div>
                                        
                                    </div>
                                </div>
                                @foreach ($replies as $item)
                                    <div class="box box-default collapsed-box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">{{$item['name']}}</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse" title="展开/收缩"><i data-widget="collapse"  class="fa fa-plus"></i></button>
                                                <a class="btn btn-box-tool" title="编辑" href="/zcjy/wechat/reply/edit/{{$item['id']}}"><span class="fa fa-edit"></span></a>
                                                <button class="btn btn-box-tool" title="删除" onclick="deleteReply( {{$item['id']}} )"><i onclick="deleteReply( {{$item['id']}} )" class="fa fa-times"></i></button>
                                            </div><!-- /.box-tools -->
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <ul style=" list-style: decimal; padding-left: 10px; ">
                                                @foreach ($item['content'] as $material)
                                                    <li>{{$material['display_type']}}: {{$material['display_name']}}</li>
                                                @endforeach
                                            </ul>
                                        </div><!-- /.box-body -->
                                    </div>
                                @endforeach
                            </div>
                            <!-- 设置自动回复文字 -->
                            <div class="modal fade " id="textmodal">
                                <div class="modal-dialog" >
                                    <div class="modal-content center" style="width: 500px;">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">设置自动回复文字</h4>
                                        </div>
                                        <div class="modal-body" style="height: 200px;">
                                            <div id="texteditor" rowspan='5'></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="save_text_selection">保存</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div>
                       </div>
                    </div>
                </div>
                <!-- Popup itself -->
                <div class="modal fade" id="material-selector">
                    <div class="modal-dialog">
                        <div class="modal-content center media-popu-width">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">选择素材</h4>
                            </div>
                            <div class="modal-body modal-body-matiral">
                                <div class="infinitescroll" style="overflow: hidden;">
                                    <!--div class="material-item-article material-item">
                                        <div class="img"><img src="http://dummyimage.com/800x600/4d494d/686a82.gif&text=placeholder+image"></div> 
                                        <p>标题</p>
                                    </div-->
                                </div>
                            </div>
                            <div class="addmore btn" >点击此处加载更多素材</div>
                            <div id="navigation"><a href="/zcjy/wechat/material/lists?page=1&type=image"></a> </div> 
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="save_material_selection">保存</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <template id="replies">
        <div class="alert">
            <button type="button" class="close" @click='cancelReply'>×</button>
            @{{text}}
        </div>
    </template>

    <script src="{{ asset('vendor/vue.js') }}"></script>
    <script src="{{ asset('vendor/vuex.min.js') }}"></script>
    <script src="{{ asset('vendor/wechat-editor.js') }}"></script>
    <script src="{{ asset('vendor/underscore-min.js') }}"></script>
    <script type="text/javascript">

        function deleteReply(replyID) {

            layer.confirm('确认删除菜单吗？该操作不可恢复!', {
                btn: ['确认','取消'] //按钮
            }, function(){
                //向服务器请求删除数据
                $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                $.ajax({
                    url:'/zcjy/wechat/reply/delete/' + replyID,   //获取菜单信息
                    type:'GET', //GET
                    async:true,    //或false,是否异步
                    timeout:5000,    //超时时间
                    dataType:'text',    //返回的数据格式：json/xml/html/script/jsonp/text
                    beforeSend:function(xhr){
                    },
                    success:function(data,textStatus,jqXHR){
                        console.log(data);
                        if (data == 'success') {
                            layer.confirm('删除成功!', {
                                btn: ['确认','取消'] //按钮
                            }, function(){
                            location.reload();
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
        };
  
        $(document).ready(function(){

            //数据状态保存
            const store = new Vuex.Store({
                state: {
                    page: 1,    //分页加载media的页数
                    type: 'text',   //分页加载的类型
                    display: false,  //是否显示消息编辑内容
                    mediaselection: {img_url:null, name: null, type: null, media_id: null}, //用户选择的media
                    replies: [],
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
                        console.log(payload);
                    },
                    refreshMediaSelection(state, payload){
                        state.mediaselection.img_url = null;
                        state.mediaselection.name = null;
                        state.mediaselection.type = null;
                        state.mediaselection.media_id = null;
                    },
                    addReply(state){
                        if ( state.mediaselection.name == null || (state.mediaselection.type=='text' && state.mediaselection.name == '') ) {
                            return ;
                        }
                        //用户可以多选，这要保存用户的选择
                        if (state.replies.length > 4) {
                            //最多只能加5个
                            layer.msg("最多只能加5个回复!", {icon: 5});
                        } else {
                            state.replies.push({name: state.mediaselection.name, type: state.mediaselection.type, media_id: state.mediaselection.media_id, text: $('textarea[name=text]').text() });
                            console.log(state.replies);
                        }
                    },
                    cancelReply(state, payload){
                        state.replies.splice(payload.pos,1);
                    },
                    switchDisplay(state, status) {
                        state.display = status;
                        console.log(state.display);
                    }
                }
            })

            //单个回复内容设置
            Vue.component('replies', {
                template: "#replies",
                props: ['item', 'pos'],
                computed:{
                    text: function () {
                        switch(this.item.type){
                            case 'article':
                                return '类型：图文    名称：' + this.item.name;
                            break;
                            case 'text':
                                return '类型：文字';
                            break;
                            case 'image':
                                return '类型：图片    名称：' + this.item.name;
                            break;
                            case 'voice':
                                return '类型：声音    名称：' + this.item.name;
                            break;
                            case 'video':
                                return '类型：视频    名称：' + this.item.name;
                            break;
                        }
                        
                    }
                },
                methods: {
                    cancelReply: function () {
                        store.commit('cancelReply', {pos: this.pos});
                    }
                }
            })

            //文本编辑器
            new WeChatEditor($('#texteditor'), {textarea: 'text'});

            //加载更多资源进行选择
            $('div.addmore').on('click', function () {
                LoadMarerial();
            })

            //保存资源选择
            $('#save_material_selection').on('click', function(){
                store.commit('addReply');
                //关闭弹窗
                $('#material-selector .close').click();
            })

            $('#save_text_selection').on('click', function(){
                store.commit('selectionInfo', {
                    img_url:null, 
                    name: '文本回复', 
                    type: 'text', 
                    media_id: null
                });
                store.commit('addReply');
                //关闭弹窗
                $('#textmodal .close').click();
            })

            //菜单选择标签页
            var buttonVue = new Vue({
                el: '#add-button',
                store,
                methods: {
                    switchDisplay: function () {
                        store.commit('switchDisplay', true);
                    }
                },
                computed: {
                    canseen: function () {
                        return !store.state.display;
                    }
                }
            });

            //菜单选择标签页
            var mediaVue = new Vue({
                el: '#nav-tabs-custom',
                store,
                data: {
                    media: null, //media数据
                    display_type: 'text', //tab显示类型
                    //canseen: store.state.display,
                    view_url: '',
                    text: '',
                    ruleNmae: '',
                    ruleKeyWord: '',
                    ruleTriggerType:'equal',
                },
                computed: {
                    replies: function () {
                        return store.state.replies;
                    },
                    canseen: function () {
                        return store.state.display;
                    },
                    isText: function () {
                        return this.display_type == 'text';
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
                methods: {
                    changeType: function (type) {
                        //设置资源请求信息
                        store.commit('pageInfo', {type: type, page: 1});
                        store.commit('refreshMediaSelection');
                        //清空已经加载的内容
                        $('.infinitescroll').empty();
                        LoadMarerial();
                        console.log('clicked:'+type);
                    },
                    save: function () {
                        var replaydata = {
                            name: this.ruleNmae,
                            trigger_keywords: this.ruleKeyWord,
                            trigger_type: this.ruleTriggerType,
                            replies: store.state.replies
                        };
                        _self = this;
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url:'/zcjy/wechat/reply/store',   //获取菜单信息
                            data: replaydata,
                            type:'POST', //GET
                            async:true,    //或false,是否异步
                            timeout:5000,    //超时时间
                            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                            beforeSend:function(xhr){
                                console.log(xhr)
                                console.log('发送前')
                            },
                            success:function(data,textStatus,jqXHR){
                                location.reload();
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

                    },
                    cancel: function () {
                        store.commit('switchDisplay', false);
                    }
                }
            })

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
                console.log(requesturl);
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