$(function(){
	formHTML = "<h2>发推</h2>" + formHTML + "<div class=\"clear\"></div>";
	timelineFocus();
	$(".rt_btn").click(function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onRT($(this));
		} else {
			$("#search_form").after(formHTML);
			formFunc();
			onRT($(this));
		}
	});
	$(".replie_btn").click(function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onReplie($(this));
		} else {
			$("#search_form").after(formHTML);
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