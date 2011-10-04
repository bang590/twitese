$(function(){
	$(".delete_btn").click(function(e){
		e.preventDefault();
		
		var $this = $(this);
		var message_id = $.trim($this.parent().parent().find(".status_id").text());
		var confirm = window.confirm("确定要删除id为" + message_id + "的消息?");
		
		if (confirm) {
			tipStart("删除id为" + message_id + "的私信中...");
			$.ajax({
				url: "ajax/delete.php",
				type: "POST",
				data: "message_id=" + message_id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().remove();
						tipEnd("删除id为" + message_id + "的私信成功");
					} else {
						tipEnd("删除私信出错，请重试", true);
					}
				},
				error: function(msg) {
					tipEnd("删除私信出错,请求无响应", true);
				}
			});
		}
	});
	
	if (!isMobile) {
		$(".msg_replie_btn").hide();
		$(".delete_btn").hide();
	}
	
	$(".timeline").find("li").hover(function(){
		$(this).find(".msg_replie_btn").css("display", "inline-block");
		$(this).find(".delete_btn").css("display", "inline-block");
	}, function(){
		$(this).find(".msg_replie_btn").hide();
		$(this).find(".delete_btn").hide();
	});

	$(".msg_replie_btn").click(function(e){
		e.preventDefault();
		$("#sent_id").val($(this).parent().parent().find(".status_word").find(".user_name").text());
		$("#textbox").focus();
	});
});
