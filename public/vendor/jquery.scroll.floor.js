(function(window){
	var defaults = {
		floorClass : '.scroll-floor',
		navClass : '.scroll-nav',
		activeClass : 'active',
		activeTop : 100,
		scrollTop : 100,
		delayTime : 200,
		leftGap: 35,
	};

	var preActiveIndex = 0;
	
	var $body = $('body'),floorList = null,navList = null;
	function getItem(_list,newOptions){
		var data = [];
		_list.each(function() {
            var item = {};
            item.$obj = $body.find(this);
            item.$activeTop = $body.find(this).offset().top - newOptions.activeTop;
            item.$scrollTop = $body.find(this).offset().top + newOptions.scrollTop;
            
            data.push(item);
        });
        return data;
	}
	
	function scrollActive(_list,newOptions){
		var nowScrollTop = $(window).scrollTop();
		var data = getItem(floorList,newOptions);
		var indexActive = 0;
		
		$.each(data,function(i,item){
			if(nowScrollTop > item.$activeTop){
				_list.removeClass(newOptions.activeClass).eq(i).addClass(newOptions.activeClass);
				indexActive = i;
				return;
			}
		});

		//检测，左侧菜单是否到了界限外，如果是，则移到界限内 by yangyujiazi
		// var activeItem = _list.eq(indexActive);
		// console.log( 'top' + activeItem.position().top);
		// console.log( 'height' + ($(window).height() - 100) );
		// if ( activeItem.position().top - ($(window).height() - 100) > 0) {
		// 	var marginTop = ($(window).height() - 100 - activeItem.position().top);
		// 	var result = Math.ceil(marginTop/defaults.leftGap) - 1;
		// 	marginTop = result*defaults.leftGap;
		// 	console.log( 'minus' +  marginTop);
		// 	console.log( 'result' +  result );
		// 	if (parseInt(activeItem.parent().css('margin-top')) != marginTop) {
		// 		activeItem.parent().css('margin-top', marginTop);
		// 	}
		// }
	}
	
	function clickActive(_index,newOptions){
		var data = getItem(floorList,newOptions);
    	$('html,body').animate({'scrollTop' : data[_index].$scrollTop},newOptions.delayTime);
    }
	
	var scroll_floor = window.scrollFloor = function(options){
		var newOptions = $.extend({}, defaults, options);
		floorList = $body.find(newOptions.floorClass);
		navList = $body.find(newOptions.navClass);
		
		
		scrollActive(navList,newOptions);
		
        $(window).bind('scroll',function(){scrollActive(navList,newOptions);});
        navList.bind('click',function(){
        	var _index = $body.find(this).index();
        	clickActive(_index,newOptions);
        });
	}
})(window);
