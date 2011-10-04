$(document).ready(function(){
	formFunc();
	timelineFocus();
	
	$(".rt_btn").live("click", function(e){
		e.preventDefault();
		onRT($(this));
	});
	
	$(".favor_btn").live("click", function(e){
		e.preventDefault();
		onFavor($(this));
	});
	$(".delete_btn").live("click", function(e){
		e.preventDefault();
		onDelete($(this), "消息");
	});
});
