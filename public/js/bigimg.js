$(document).ready(function() {
	function showImg(outdiv,indiv,bigimg,thiselement){  
	    var winW = $(window).width();  
	    var winH = $(window).height();  
	    var src = thiselement.children().attr('src'); 
	    console.log(src);
	    $(bigimg).children().attr("src",src);  
	    
	        var imgW = thiselement.width();
	        var imgH = thiselement.height();
	        console.log(imgW,imgH);
	        var scale= imgW/imgH;          
             
            var w=(winW-imgW*1.8)/2;  
            var h=(winH-imgH*1.8)/2;  
            $(bigimg).css("width",imgW*1.8+'px'); 
            console.log(w,h);      
            $(indiv).css({"left":w,"top":h});     
	        $(outdiv).fadeIn("fast");  
	        $(outdiv).click(function(){  
	            $(this).fadeOut("fast");  
	        });                               
	     
	}  
	$('.imgclass').click(function() {
		showImg($('#outdiv'),$('.indiv'),$('#bigimg'),$(this))
	});
	
	
});