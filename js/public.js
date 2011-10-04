
var formHTML = '<span id="tip">还可以输入<b>140</b>个字</span><form action="index.php" method="post">';
formHTML += '<textarea name="status" id="textbox"></textarea>';
formHTML += '<input type="hidden" id="in_reply_to" name="in_reply_to" value="0" />';
formHTML += '<input type="submit" id="submit_btn" title="按ctrl+enter键发送" value="发送" /></form>';	

var isMobile = (/i686/.test(navigator.userAgent) && /U;/.test(navigator.userAgent)) || (/Opera Mini/.test(navigator.userAgent));
function formFunc() {
	leaveWord();
	$("#textbox").focus();
	$("#textbox").keydown(function(){leaveWord()}).keyup(function(){leaveWord()}).keydown(function(event){
		if (event.ctrlKey && event.keyCode==13) {
			updateStatus();
		}
		});
	
	$("#submit_btn").click(function(e){
		if (!isMobile) {
			e.preventDefault();
			updateStatus();
		}
	});

}
	
function updateStatus(){
	tipStart("发送消息中...");
	var text = $("#textbox").val();
	$.ajax({
		url: "ajax/update.php",
		type: "POST",
		data: "status=" + encodeURIComponent(text) + "&in_reply_to=" + $("#in_reply_to").val(),
		success: function(msg) {
			
			if (msg.indexOf("li") > 0) {
				tipEnd("发送消息成功");
				$("#textbox").val("");
				$("#tip b").html("140");
				if (document.location.href.indexOf("index") > 0 || document.location.href.indexOf("all") > 0) {
					try {
						msg = showpic_main($(msg));
					} catch (e) {}
					$(".timeline").eq(0).prepend(msg);
					
					
					$("#user_stats li:last").find(".count").html(parseInt($("#user_stats li:last").find(".count").text())+1);

					hideBtn();
				}
			} else if (msg.indexOf("empty") >= 0){
				tipEnd("发送消息出错,内容不能为空", true);
			} else {
				tipEnd("发送消息出错,未知错误,请重试", true);
			}
		},
		error: function(msg) {
			tipEnd("发送消息出错,请求无响应", true);
		}
	});
}

function timelineFocus(){	
	if (!isMobile) {
		hideBtn();
	}
	
	$(".timeline").find("li").live("mouseover", function(){
		$(this).find(".replie_btn").css("display", "inline-block");
		$(this).find(".rt_btn").css("display", "inline-block");
		$(this).find(".favor_btn").css("display", "inline-block");
		$(this).find(".delete_btn").css("display", "inline-block");
		$(this).find(".ort_btn").css("display", "inline-block");
	});
	
	$(".timeline").find("li").live("mouseout", function(){
		$(this).find(".replie_btn").hide();
		$(this).find(".rt_btn").hide();
		$(this).find(".favor_btn").hide();
		$(this).find(".delete_btn").hide();
		$(this).find(".ort_btn").hide();
	});
	
	$(".in_reply_to").find("a").live("click", function(e){
		e.preventDefault();
		var exists = $(this).parent().parent().parent().parent().find(".inline_replie");
		if (exists.length == 0) {
			var id = $(this).attr("href").split("=")[1],
				$this = $(this);
			$this.html($this.text() + " <img src=\"img/loading.gif\" />");
			$.ajax({
				url: "ajax/showReplies.php",
				type: "GET",
				data: "id=" + id,
				success: function(msg) {
					
					if (msg.indexOf("error") == -1) {
						$this.find("img").remove();
						var span = $this.parent().parent().parent();
						span.after(msg);
					}
					
				},
				error: function(msg) {
					tipEnd("RT出错,请求无响应", true);
				}
			});
		} else {
			exists.remove();
		}
	});
}

function hideBtn(){
	$(".replie_btn").hide();
	$(".rt_btn").hide();
	$(".favor_btn").hide();
	$(".delete_btn").hide();
	$(".ort_btn").hide();
}
function onFavor($this) {
	var status_id = $.trim($this.parent().parent().find(".status_id").text());
	tipStart("正在收藏id为" + status_id + "的消息...");
	$.ajax({
		url: "ajax/addfavor.php",
		type: "POST",
		data: "status_id=" + status_id,
		success: function(msg) {
			if (msg.indexOf("success") >= 0) {
				tipEnd("收藏消息成功");
			} else if (msg.indexOf("favorited") >= 0){
				tipEnd("此消息已收藏", true);
			} else {
				tipEnd("收藏出错,未知错误,请重试", true);
			}
		},
		error: function(msg) {
			tipEnd("收藏出错,请求无响应", true);
		}
	});
}
function onReplie($this){
	var replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
	$("#textbox").focus();
	$("#textbox").val($("#textbox").val() + "@" + replie_id + " ");
	$("#in_reply_to").val($this.parent().parent().find(".status_id").text());
	leaveWord();
}

function onRT($this){
	var replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
	$("#textbox").val(" RT @" + replie_id + ":" + $this.parent().parent().find(".status_word").text().replace(replie_id, ""));
	if($.browser.msie){   
		var oTextRange = document.getElementById("textbox").createTextRange();   
		oTextRange.collapse(true);   
		oTextRange.select();   
		oTextarea.focus();   
	}else{   
		$("#textbox").select();
		$("#textbox").attr("selectionStart",0);
		$("#textbox").attr("selectionEnd",0);
		$("#textbox").focus();
	}
	leaveWord();
}

function onORT($this){
	var status_id = $.trim($this.parent().parent().find(".status_id").text());
	var confirm = window.confirm("确定要RT此消息?");
	if (confirm) {
		tipStart("RT中...");
		$.ajax({
			url: "ajax/ort.php",
			type: "POST",
			data: "status_id=" + status_id,
			success: function(msg) {
				
				if (msg.indexOf("success") >= 0) {
					
					var li = $this.parent().parent().parent().addClass("retweeted");
					li.addClass("retweeted")
						.find('.ort_btn').remove().end()
						.find('.source').prepend("RT by you ");

					tipEnd("RT成功");
				} else if (msg.indexOf("repeat") >= 0){
					tipEnd("此消息已RT", true);
				}
			},
			error: function(msg) {
				tipEnd("RT出错,请求无响应", true);
			}
		});
	}
}

function onDelete($this, type) {
	var status_id = $.trim($this.parent().parent().find(".status_id").text());
	var confirm = window.confirm("确定要删除id为" + status_id + "的消息?");
	if (confirm) {
		tipStart("删除id为" + status_id + "的" + type + "中...");
		var postData = (type == "消息")? "status_id=": "favor_id=";
		$.ajax({
			url: "ajax/delete.php",
			type: "POST",
			data: postData + status_id,
			success: function(msg) {
				if (msg.indexOf("success") >= 0) {
					$this.parent().parent().parent().remove();
					tipEnd("删除id为" + status_id + "的" + type + "成功");
				} else {
					tipEnd("删除" + type + "出错，未知错误", true);
				}
			},
			error: function(msg) {
				tipEnd("删除" + type + "出错，请求无响应", true);
			}
		});
	}
}
function leaveWord(num) {
	if (!num) num = 140;
	var leave = num-$("#textbox").val().length;
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

function tipEnd(msg, error){
	if (error) {
		$(".fixtip").css({"background": "#ffc9d7", "border": "1px solid #ff3e75", "border-top": "none"});
	}
	$(".fixtip").text(msg);
	tipTimeout = setTimeout(function(){$(".fixtip").fadeOut(1500);tipTimeout=false;}, 2000);
}
function tipStart(msg){
	try {
		if (tipTimeout) clearTimeout(tipTimeout);
	}catch(e){}
	
	$(".fixtip").css({"background": "#fffcaa", "border": "1px solid #ffed00", "border-top": "none"})
		.fadeIn().text(msg);
	
}

function updateDate(){
	$(".date").each(function(){
		var date = new Date($(this).attr("title").replace(/\-/g,'/')),
			now = new Date(),
			differ = (now - date)/1000,
			dateFormated = '';
		
		if (differ < 60) {
			dateFormated = Math.ceil(differ) + "秒前";
		} else if (differ < 3600) {
			dateFormated = Math.ceil(differ/60) + "分钟前";
		} else if (differ < 3600*24) {
			dateFormated = "约" + Math.ceil(differ/3600) + "小时前";
		} else {
			dateFormated = $(this).attr("title")
		}
		$(this).find('a').text(dateFormated);
	});
}

function getCookie(name){
     var strCookie=document.cookie;
     var arrCookie=strCookie.split("; ");
     for(var i=0;i<arrCookie.length;i++){
           var arr=arrCookie[i].split("=");
           if(arr[0]==name)return unescape(arr[1]);
     }
     return "";
}
function setCookie(name,value,expireHours){
	var cookieString=name+"="+escape(value);
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime+expireHours*3600*1000);
		cookieString=cookieString+"; expire="+date.toGMTString();
	}
     document.cookie=cookieString;
} 
$(function(){
	$.ajaxSetup({timeout:15000}); 
	$("#showListBtn").click(function(e){
		var that = $(this);
		e.preventDefault();
		
		if (that.hasClass("dropdown_finish")) {
			$("#showList").remove();
			that.removeClass("dropdown_finish").addClass("dropdown_normal");
			
		} else if (that.hasClass("dropdown_normal")){
			that.removeClass("dropdown_normal").addClass("dropdown_loading");			
			$.ajax({
				url: "ajax/showLists.php",
				type: "GET",
				success: function(msg) {
					that.after(msg);
					that.removeClass("dropdown_loading").addClass("dropdown_finish");
				},
				error: function(msg) {
					that.removeClass("dropdown_loading").addClass("dropdown_normal");
				}
			});
		}
	});

	$("#showTrendsBtn").click(function(e){
		var that = $(this);
		e.preventDefault();
		
		if (that.hasClass("dropdown_finish")) {
			$("#showTrends").remove();
			that.removeClass("dropdown_finish").addClass("dropdown_normal");
			
		} else if (that.hasClass("dropdown_normal")){
			that.removeClass("dropdown_normal").addClass("dropdown_loading");			
			$.ajax({
				url: "ajax/showTrends.php",
				type: "GET",
				success: function(msg) {
					that.after(msg);
					that.removeClass("dropdown_loading").addClass("dropdown_finish");
				},
				error: function(msg) {
					that.removeClass("dropdown_loading").addClass("dropdown_normal");
				}
			});
		}
	});
	
	$("#profileRefresh").click(function(e){
		e.preventDefault();
		var that = $(this);
		if (!that.hasClass('refreshing')) {
			that.addClass('refreshing').html("<img src=\"img/loading.gif\" />");
			$.ajax({
				url: "ajax/profile.php",
				type: "GET",
				dataType: "json",
				success: function(msg) {
					if (msg.statuses >= 0) { 
						$(".count").eq(0).text(msg.friends).end()
							.eq(1).text(msg.followers).end()
							.eq(2).text(msg.statuses);
					}
					that.removeClass('refreshing').html("<img src=\"img/refresh.png\" />");
				},
				complete: function() {
					that.removeClass('refreshing').html("<img src=\"img/refresh.png\" />");
				}
			});
		}
	});
	
	$("#clear_btn").click(function(e){
		e.preventDefault();
		$(".timeline").each(function(){
			while ($(this).find("li").length > 20) {
				var li = $(this).find("li");
				li.eq(li.length-1).remove();
			}
		});
	});
});