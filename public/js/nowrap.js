$(document).ready(function() {
	$(".nowrap").each(function(){
		var maxwidth=8;
		if($(this).text().length>maxwidth){
			$(this).text($(this).text().substring(0,maxwidth));
			$(this).html($(this).html()+'…');
		}
	});
});

