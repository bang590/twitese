$(function(){
	timelineFocus();
	$(".rt_btn").click(function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onRT($(this));
		} else {
			$("#info_head").before('<h2>发推</h2>' + formHTML);
			formFunc();
			onRT($(this));
		}
	});
	
	$(".replie_btn").click(function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onReplie($(this));
		} else {
			$("#info_head").before('<h2>发推</h2>' + formHTML);
			formFunc();
			onReplie($(this));
		}
	});

	$(".ort_btn").live("click", function(e){
		e.preventDefault();
		onORT($(this));
	});
		
	$("#list_send_btn").click(function(e){
		e.preventDefault();
		if ($("#textbox").length == 0) {
			$("#info_head").before('<h2>发推</h2>' + formHTML);
			formFunc();
		}
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
