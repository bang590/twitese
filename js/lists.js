$(function(){
	$(".rt_btn").live("click", function(e){
		e.preventDefault();
		onRT($(this));
	});
	
	$(".replie_btn").live("click", function(e){
		e.preventDefault();
		onReplie($(this));
	});
	$(".favor_btn").live("click", function(e){
		e.preventDefault();
		onFavor($(this));
	});
	
	$(".unfollow_list").click(function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $(this).parent().parent().find(".rank_name").text().substr(1);

		tipStart("正在取消关注推群" + id + "...");
		$.ajax({
			url: "ajax/list.php",
			type: "POST",
			data: "action=destory&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					tipEnd("取消关注推群" + id + "成功");
					$this.remove();
				} else {
					tipEnd("取消关注推群" + id + "失败，请重试", true);
				}
			},
			error: function(msg) {
				tipEnd("取消关注推群" + id + "出错，请求无响应", true);
			}
		});
		
	});

	$(".delete_list").click(function(e){
		e.preventDefault();
		var $this = $(this);  
		var list_id = $(this).parent().parent().find(".rank_name").text().substr(1);
		var confirm = window.confirm("确定要删除推群" + list_id + "?");
		if (confirm) {
			tipStart("删除推群" + list_id + "中...");
			$.ajax({
				url: "ajax/delete.php",
				type: "POST",
				data: "list_id=" + list_id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().remove();
						tipEnd("删除推群" + list_id + "成功");
					} else {
						tipEnd("删除推群出错失败，请重试", true);
					}
				},
				error: function(msg) {
					tipEnd("删除推群出错，请求无响应", true);
				}
			});
		}
	});
	
	$("#list_create_btn").click(function(e){
		e.preventDefault();
		$("#list_form").toggle("fast");
		$("#list_name").focus().val("");
		$("#list_description").val("");
		$("#list_protect").removeAttr("checked");
		$("#pre_list_name").val("");
		$("#is_edit").val(0);
	});
	
	$(".edit_list").click(function(e){
		e.preventDefault();
		var parent = $(this).parent().parent();
		var list_name = parent.find(".rank_name").text().split("/")[1];
		var list_description = parent.find(".rank_description").text().slice(3);
		var list_protect = parent.find(".rank_count").text().indexOf("隐私群") > 0;

		$("#list_form").show("fast");
		$("#list_name").focus().val(list_name);
		$("#list_description").val(list_description);
		if (list_protect) { 
			$("#list_protect").attr("checked", "checked");
		} else {
			$("#list_protect").removeAttr("checked");
		}
		$("#is_edit").val(1);
		$("#pre_list_name").val(list_name);
	})
	
	
	
	$(".add_member").click(function(e){
		e.preventDefault();
		$("#member_form").remove();
		var position = $(this).position();
		var liPosition = $(this).parent().parent().parent().position();
		var list_name = $(this).parent().parent().find(".rank_name").text().split("/")[1];
		$('<form method="POST" action="./lists.php?t=1" id="member_form">' +
	    	'<span>成员ID:(以英文逗号隔开，示例：bang590,twitter)</span>' +
	    	'<span><textarea type="text" name="list_members" id="list_members"></textarea></span>' +
	    	'<input type="hidden" name="member_list_name" value="' + list_name + '" />' +
	    	'<span><input type="submit" id="member_submit" value="提交" /> <input type="button" id="member_cancel" value="取消" /></span>' +
	    '</form>').appendTo("#statuses").css("left", liPosition.left + position.left).css("top", liPosition.top + position.top + 30);
		
		$("#member_cancel").click(function(){
			$("#member_form").remove();
		})
	})
	
});