$sns_jq(document).ready(function($){
	var cshow_timer = 0;
	var chide_timer = 0;
	$('.mini-cart').mouseenter(function(){
		clearTimeout(cshow_timer);
		clearTimeout(chide_timer);
		cshow_timer = setTimeout(function(){ //Emulate HoverIntent
			$('.mini-cart .block-content').stop().show(500, function(){
				$('.mini-cart .block-content').css({"display":"block"});
			});
		}, 100);
	}).mouseleave(function(){ //return;
		clearTimeout(cshow_timer);
		clearTimeout(chide_timer);
		chide_timer = setTimeout(function(){ //Emulate HoverIntent
			$('.mini-cart .block-content').stop().hide(500, function(){
				$('.mini-cart .block-content').css({"display":"none", "width":"", "height":""});
			});
		}, 100);
    });
});