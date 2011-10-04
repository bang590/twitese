$(function(){
	document.getElementById("submit_btn").disabled=false;	
	leaveWord();
	$("#textbox").focus();
	$("#textbox").keydown(leaveWord).keyup(leaveWord).keydown(function(event){
		if (event.ctrlKey && event.keyCode==13) {
			$("form").eq(1).submit();
		}
		});
	
	$(".submit_btn").click(function(){
		document.getElementById("submit_btn").disabled=true;	
	});
	
	$(".timeline").find("li").live("mouseover", function(){
		$(this).find(".replie_btn").css("display", "inline-block");
		$(this).find(".rt_btn").css("display", "inline-block");
		$(this).find(".favor_btn").css("display", "inline-block");
		$(this).find(".delete_btn").css("display", "inline-block");
	});
	
	$(".timeline").find("li").live("mouseout", function(){
		$(this).find(".replie_btn").hide();
		$(this).find(".rt_btn").hide();
		$(this).find(".favor_btn").hide();
		$(this).find(".delete_btn").hide();
	});
	
	$(".rt_btn").live("click", function(){
		$("#textbox").val("RT @" + $(this).parent().find(".status_word").text());
		$("#textbox").focus();
		leaveWord();
	});
	
	$(".replie_btn").live("click", function(){
		var replie_id = $(this).parent().find(".status_word").find(".user_name").text();
		$("#textbox").val("@" + replie_id + " ");
		$("#textbox").focus();
		$("#in_reply_to").val($(this).parent().find(".status_id").text());
		leaveWord();
	});
});



function leaveWord() {
	var leave = 140-$("#textbox").val().length;
	if (leave < 0) {
		$("#tip").css("color","#CC0000");
		$("#tip b").css("color","#CC0000");
		$("#tip").html("已经超出<b>" + (-leave) + "</b>个字");
	} else {
		$("#tip").css("color","#000000");
		$("#tip b").css("color","#000000");
		$("#tip").html("还可以输入<b>" + leave + "</b>个字");
	}
}

