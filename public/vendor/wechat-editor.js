
    var $defaults = {
        textarea: 'content'
    };

    var Emotions = [
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/0.gif",
        title: "微笑"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/1.gif",
        title: "撇嘴"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/2.gif",
        title: "色"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/3.gif",
        title: "发呆"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/4.gif",
        title: "得意"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/5.gif",
        title: "流泪"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/6.gif",
        title: "害羞"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/7.gif",
        title: "闭嘴"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/8.gif",
        title: "睡"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/9.gif",
        title: "大哭"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/10.gif",
        title: "尴尬"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/11.gif",
        title: "发怒"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/12.gif",
        title: "调皮"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/13.gif",
        title: "呲牙"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/14.gif",
        title: "惊讶"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/15.gif",
        title: "难过"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/16.gif",
        title: "酷"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/17.gif",
        title: "冷汗"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/18.gif",
        title: "抓狂"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/19.gif",
        title: "吐"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/20.gif",
        title: "偷笑"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/21.gif",
        title: "可爱"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/22.gif",
        title: "白眼"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/23.gif",
        title: "傲慢"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/24.gif",
        title: "饥饿"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/25.gif",
        title: "困"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/26.gif",
        title: "惊恐"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/27.gif",
        title: "流汗"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/28.gif",
        title: "憨笑"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/29.gif",
        title: "大兵"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/30.gif",
        title: "奋斗"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/31.gif",
        title: "咒骂"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/32.gif",
        title: "疑问"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/33.gif",
        title: "嘘"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/34.gif",
        title: "晕"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/35.gif",
        title: "折磨"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/36.gif",
        title: "衰"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/37.gif",
        title: "骷髅"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/38.gif",
        title: "敲打"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/39.gif",
        title: "再见"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/40.gif",
        title: "擦汗"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/41.gif",
        title: "抠鼻"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/42.gif",
        title: "鼓掌"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/43.gif",
        title: "糗大了"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/44.gif",
        title: "坏笑"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/45.gif",
        title: "左哼哼"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/46.gif",
        title: "右哼哼"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/47.gif",
        title: "哈欠"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/48.gif",
        title: "鄙视"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/49.gif",
        title: "委屈"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/50.gif",
        title: "快哭了"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/51.gif",
        title: "阴险"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/52.gif",
        title: "亲亲"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/53.gif",
        title: "吓"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/54.gif",
        title: "可怜"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/55.gif",
        title: "菜刀"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/56.gif",
        title: "西瓜"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/57.gif",
        title: "啤酒"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/58.gif",
        title: "篮球"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/59.gif",
        title: "乒乓"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/60.gif",
        title: "咖啡"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/61.gif",
        title: "饭"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/62.gif",
        title: "猪头"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/63.gif",
        title: "玫瑰"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/64.gif",
        title: "凋谢"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/65.gif",
        title: "示爱"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/66.gif",
        title: "爱心"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/67.gif",
        title: "心碎"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/68.gif",
        title: "蛋糕"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/69.gif",
        title: "闪电"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/70.gif",
        title: "炸弹"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/71.gif",
        title: "刀"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/72.gif",
        title: "足球"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/73.gif",
        title: "瓢虫"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/74.gif",
        title: "便便"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/75.gif",
        title: "月亮"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/76.gif",
        title: "太阳"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/77.gif",
        title: "礼物"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/78.gif",
        title: "拥抱"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/79.gif",
        title: "强"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/80.gif",
        title: "弱"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/81.gif",
        title: "握手"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/82.gif",
        title: "胜利"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/83.gif",
        title: "抱拳"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/84.gif",
        title: "勾引"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/85.gif",
        title: "拳头"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/86.gif",
        title: "差劲"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/87.gif",
        title: "爱你"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/88.gif",
        title: "NO"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/89.gif",
        title: "OK"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/90.gif",
        title: "爱情"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/91.gif",
        title: "飞吻"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/92.gif",
        title: "跳跳"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/93.gif",
        title: "发抖"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/94.gif",
        title: "怄火"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/95.gif",
        title: "转圈"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/96.gif",
        title: "磕头"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/97.gif",
        title: "回头"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/98.gif",
        title: "跳绳"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/99.gif",
        title: "挥手"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/100.gif",
        title: "激动"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/101.gif",
        title: "街舞"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/102.gif",
        title: "献吻"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/103.gif",
        title: "左太极"
    },
    {
        src: "https://res.wx.qq.com/mpres/htmledition/images/icon/emotion/104.gif",
        title: "右太极"
    }
];

    /**
     * WeChat 编辑器
     *
     * @param {Object} $element
     * @param {Object} $options
     */
    function WeChatEditor ($element, $options) {
        $element = $($element);

        if (!(this instanceof WeChatEditor)) return new WeChatEditor($element, $options);

        this.options = $options || {};

        for (var i in $defaults) {
          if (this.options[i] == null) {
            this.options[i] = $defaults[i];
          }
        }

        this.element = $element;

        this.init();
    }

    /**
     * 初始化
     */
    WeChatEditor.prototype.init = function() {
        this.createEditor();
        this.initEmotions();
    }

    WeChatEditor.prototype.createEditor = function() {
        this.element.append('<div class="wechat-editor"></div>');
        this.createContentBox();
        this.createToolbar();
        this.addCountListener();
        this.element.find('.wechat-editor .wechat-editor-content').focus();
    }

    WeChatEditor.prototype.createContentBox = function() {
        this.element.find('.wechat-editor').append('<div class="wechat-editor-content" contenteditable="true"></div><textarea style="display:none" name="'+ this.options.textarea+ '"></textarea>');
    }

    WeChatEditor.prototype.createToolbar = function() {
        this.element.find('.wechat-editor').append('<div class="wechat-editor-tool-bar">'
                                + '<div class="row">'
                                    + '<div class="col-md-6">'
                                       + '<div class="icon-bar"><a href="javascript:;" class="emotion-btn" title="emotions"><i class="ion-android-happy"></i></a></div>'
                                    + '</div>'
                                    + '<div class="col-md-6">'
                                        + '<div class="tips text-right">还可以输入 <em class="text-counter">599</em> 字</div>'
                                    + '</div>'
                                + '</div>'
                            + '</div>');
    }

    WeChatEditor.prototype.initEmotions = function() {
        var $editorWrapper = this;
        var $editor = this.element.find('.wechat-editor .wechat-editor-content');

        $(document).on('click', '.icon-bar .emotion-btn', function() {
            $editor.focus();

            if (!$(this).find('.emotions').length) {
                $editorWrapper.createEmotionsPicker();
            };

            $editorWrapper.showEmotionsPicker($(this));
        });
    }

    WeChatEditor.prototype.createEmotionsPicker = function() {
        if ($('.emotions').length) {return;};
        var $emotions = $('<div class="emotions"><ul></ul></div>');
        var $emotionsList = $emotions.find('ul');
        var $editor = this.element.find('.wechat-editor .wechat-editor-content');

        Emotions.forEach(function($emotion){
            $emotionsList.append('<li><a href="javascript:;"><img src="'+$emotion.src+'" data-title="'+$emotion.title+'"/></a></li>');
        });

        $('body').append($emotions);

        this.addEmotionListener();
    }

    WeChatEditor.prototype.addCountListener = function () {
        var self = this;
        $(document).on("DOMCharacterDataModified input DOMSubtreeModified", '.wechat-editor-content', function() {
            var $editor = $(this);
            var $coutViewer = $editor.siblings('.wechat-editor-tool-bar').find('.text-counter');
            var $emotions = $editor.find('img');
            var $emotionsLenth = 0;

            // 表情长度
            $emotions.each(function(index, $el) {
                $emotionsLenth += $(this).attr('data-title').length + 1;
            });

            $coutViewer.html(599 - ($editor.text().length + $emotionsLenth));

            self.syncContent($editor);
        });
    }

    WeChatEditor.prototype.textToEmotion = function ($text) {
        if ($text == null) {return ;}
        for ($i in Emotions) {
            $text = $text.replace(new RegExp('/'+Emotions[$i].title, 'g'), '<img src="'+Emotions[$i].src+'" data-title="'+Emotions[$i].title+'" />');
        }
        return $text;
    }

    WeChatEditor.prototype.addEmotionListener = function(){
        var $editor = $(this).closest('.wechat-editor-tool-bar').siblings('.wechat-editor-content');

        $(document).on('click', '.emotions ul li a', function(){
            var $img = $($(this).html());

            $editor.focus();

            if (window.getSelection) {
                var $sel = window.getSelection();

                if ($sel.rangeCount) {
                    var range = $sel.getRangeAt(0);
                    var selectedText = range.toString();
                    range.deleteContents();
                    var newNode = $img.get(0);
                    range.insertNode(newNode);
                    //move the cursor
                    range.setStartAfter(newNode);
                    range.setEndAfter(newNode);
                    $sel.removeAllRanges();
                    $sel.addRange(range);
                }
            } else {
                alert('浏览器不支持：window.getSelection()');
            }
        });

        $(document).on('click', function() {
            $('div.emotions').hide();
        }).on('click', '.emotions, .icon-bar .emotion-btn', function(event){
            event.stopPropagation();
        });
    }

    WeChatEditor.prototype.showEmotionsPicker = function($btn) {
        var $css = {
            position: 'absolute',
            top: $btn.offset().top + $btn.height(),
            left: $btn.offset().left - 4,
            display:'none'
        };

        $('.emotions').css($css).show();
    }

    WeChatEditor.prototype.syncContent = function($editor) {
        var $content = $('<div>' + $editor.html() + '</div>');
        var $textarea = $editor.siblings('textarea:first');

        $content.find('img').each(function(index, el) {
            $(this).replaceWith('/' + $(this).attr('data-title'));
        });

        $content.find('div').each(function(index, el) {
            $(this).replaceWith("\n" + $(this).text());
        });;

        $content.find('br').replaceWith("\n");

        $textarea.text($content.text()).trigger('change');
    }

