// JavaScript Document

$sns_jq(document).ready(function($){
	/* Begin: Show o hide cpanel */
	$('#sns_magic_btn').click(function(){
		if(!$(this).hasClass('open')){
			$('#sns_cpanel').animate({
				'left':'-311px'
			}, 200, function(){
				$('#sns_cpanel').show().animate({
					'left':'0px'
				});
			});
			$(this).addClass('open');
			return;
		}else{
			$('#sns_cpanel').animate({
				'left':'0px'
			}, 200, function(){
				$('#sns_cpanel').show().animate({
					'left':'-311px'
				});
			});
			$(this).removeClass('open');
			return;
		}
	});
	/* End: Show o hide cpanel */
});

function cPReset(_cookie){
	for (i=0;i<_cookie.length;i++) {
		if(getCookie(TMPL_NAME+'_'+_cookie[i])!=undefined){
			createCookie (TMPL_NAME+'_'+_cookie[i], '', -1);
		}
	}
	window.location.reload(true);
}

function cPApply () {
	var elems = document.getElementById('sns_cpanel').getElementsByTagName ('*');
	var usersetting = {};
	for (i=0;i<elems.length;i++) {
		var el = elems[i];
	    if (el.name && (match=el.name.match(/^sns_ccofig_(.*)$/))) {
	        var name = match[1];
	        var value = '';
	        if (el.tagName.toLowerCase() == 'input' && (el.type.toLowerCase()=='radio' || el.type.toLowerCase()=='checkbox')) {
	        	if (el.checked) value = el.value;
	        } else {
	        	value = el.value;
	        }
			if(trim(value)){
				if (usersetting[name]) {
					if (value) usersetting[name] = value + ',' + usersetting[name];
				} else {
					usersetting[name] = value;
				}
			}
	    }
	}
	for (var k in usersetting) {
		name = TMPL_NAME + '_' + k; //alert(name);
		value = usersetting[k];
		createCookie(name, value, 365);
	}
	window.location.reload(true);
}