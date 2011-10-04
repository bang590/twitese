$(function(){
	timelineFocus();
	$(".rt_btn").live("click", function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onRT($(this));
		} else {
			$("#info_head").before('<h2>发推</h2>' + formHTML);
			formFunc();
			onRT($(this));
		}
	});
	
	$(".replie_btn").live("click", function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onReplie($(this));
		} else {
			$("#info_head").before('<h2>发推</h2>' + formHTML);
			formFunc();
			onReplie($(this));
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
	

	$("#list_follow_btn").live("click", function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();

		tipStart("正在关注推群" + id + "...");
		
		$.ajax({
			url: "ajax/list.php",
			type: "POST",
			data: "action=create&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					tipEnd("关注推群" + id + "成功");
					$this.after('<a class="info_btn_hover" id="list_block_btn" href="javascript:void(0)">取消关注</a>');
					$this.remove();
				} else {
					tipEnd("关注推群" + id + "失败，未知错误", true);
				}
			},
			error: function(msg) {
				tipEnd("关注推群" + id + "失败，请求无响应", true);
			}
		});
	});
	
	
	$("#list_block_btn").live("click", function(e){
		e.preventDefault();
		var $this = $(this);
		var id = $("#info_name").text();

		tipStart("正在取消关注推群" + id + "...");
		$.ajax({
			url: "ajax/list.php",
			type: "POST",
			data: "action=destory&id=" + id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					tipEnd("取消关注推群" + id + "成功");
					$this.after('<a class="info_btn" id="list_follow_btn" href="javascript:void(0)">关注推群</a>');
					$this.remove();
				} else {
					tipEnd("取消关注推群" + id + "失败，未知错误", true);
				}
			},
			error: function(msg) {
				tipEnd("取消关注推群" + id + "失败，请求无响应", true);
			}
		});
		
	});
	
	document.onclick = function(){
		document.title =document.title.replace(/(\([0-9]+\))/g, "");
	}
	var args = location.href.split("?")[1];	
	if (!args.split("&")[1] || args.split("&")[1] == "p=1") {
		setInterval(function(){
				update();
		}, 2000*60);
	}
});

function update() {
	if ($("#stop_refresh").attr('checked')) return;
	updateDate();
	var since_id = $(".timeline li:first-child").find(".status_id").text();
	var list_id = $("#info_name").text();
	$.ajax({
		url: "ajax/updateList.php",
		type: "GET",
		dataType: "text",
		data: "id=" + list_id + "&since_id=" + since_id,
		success: function(msg) {
			
			if ($.trim(msg).indexOf("</li>") > 0) {
				
				msg = $(msg);
				try {
					msg = showpic_main(msg);
				} catch (e) {}
				$(".timeline").prepend(msg);
				
				var num = 0;
				if (document.title.match(/\d+/) != null) {
					num = parseInt(document.title.match(/\d+/));
				}
				document.title = "(" + (num+msg.length )+ ")" + document.title.replace(/(\([0-9]+\))/g, "");
				hideBtn();
			}
			
		}
	});
}

