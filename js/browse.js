$(function(){
	timelineFocus();
	$(".rt_btn").click(function(){
		if ($("#textbox").length > 0) {
			onRT($(this));
		} else {
			$("#browse_title").after(formHTML);
			formFunc();
			onRT($(this));
		}
	});
	$(".replie_btn").click(function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onReplie($(this));
		} else {
			$("#browse_title").after(formHTML);
			formFunc();
			onReplie($(this));
		}
	});
	$(".favor_btn").live("click", function(){
		onFavor($(this));
	});
	$(".ort_btn").live("click", function(e){
		e.preventDefault();
		onORT($(this));
	});
});