$(function(){
	$(".delete_btn").hide();
	$(".rank_list").find("li").live("mouseover", function(){
		$(this).find(".delete_btn").css("display", "inline-block");
	});
	
	$(".rank_list").find("li").live("mouseout", function(){
		$(this).find(".delete_btn").hide();
	});
	
	$(".list_delete_btn").click(function(e){
		e.preventDefault();
		var $this = $(this);  
		var list_id = $("h2 strong").text();
		var member_name = $(this).parent().parent().find(".rank_screenname").text();
		member_name = member_name.replace("(","").replace(")","");

		
		var confirm = window.confirm("确定要删除成员" + member_name + "?");
		if (confirm) {
			tipStart("删除成员" + member_name + "中...");
			$.ajax({
				url: "ajax/delete.php",
				type: "POST",
				data: "id=" + list_id + "&list_member=" + member_name,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().remove();
						tipEnd("删除成员" + member_name + "成功");
					} else {
						tipEnd("删除成员出错，请重试", true);
					}
				},
				error: function(msg) {
					tipEnd("删除成员出错，请求无响应", true);
				}
			});
		}
	});
});