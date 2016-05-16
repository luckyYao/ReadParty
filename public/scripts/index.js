// 登录页：垂直居中布局
	//本地存储
function kset(key,value){window.localStorage.setItem(key,value);}
function kget(key){return window.localStorage.getItem(key);}

var window_height = $(window).height();
var window_width = $(window).width();

function handleNav(id){
	if ($("#"+id).css("display")=='none') {
		$("#"+id).show();
	}else{
		$("#"+id).hide();
	};
	
}
