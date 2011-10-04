$(function(){
	$(".follow_btn").hide();
	$(".unfollow_btn").hide();
	$(".rank_list li").hover(function(){
		$(this).find(".follow_btn").show();
		$(this).find(".unfollow_btn").show();
	}, function(){
		$(this).find(".follow_btn").hide();
		$(this).find(".unfollow_btn").hide();
	})
	
	$(".follow_btn").live("click", function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $this.parent().find(".rank_screenname").text().slice(1,-1);

		tipStart("正在关注" + id + "...");
		
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=create&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					tipEnd("关注" + id + "成功");
					$this.after('<a class="unfollow_btn" href="a_relation.php?action=destory&id=' + id + '">[取消关注]</a>');
					$this.remove();
				} else {
					tipEnd("关注" + id + "失败，请重试", true);
				}
			},
			error: function(msg) {
				tipEnd("关注" + id + "失败，请求无响应", true);
			}
		});
	});
	
	$(".unfollow_btn").live("click", function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $this.parent().find(".rank_screenname").text().slice(1,-1);

		tipStart("正在取消关注" + id + "...");;
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=destory&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					tipEnd("取消关注" + id + "成功");
					$this.after('<a class="follow_btn" href="a_relation.php?action=create&id=' + id + '">[关注此人]</a>');
					$this.remove();
				} else {
					tipEnd("取消关注" + id + "失败，请重试", true);
				}
			},
			error: function(msg) {
				tipEnd("取消关注" + id + "失败，请求无响应", true);
			}
		});
		
	});
	
});

