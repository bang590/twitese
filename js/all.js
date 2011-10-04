$(function(){
	formFunc();
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
	
	$(".delete_btn").live("click", function(e){
		e.preventDefault();
		onDelete($(this), "消息");
	});

	$(".ort_btn").live("click", function(e){
		e.preventDefault();
		onORT($(this));
	});
	
	if (/i686/.test(navigator.userAgent) && /U;/.test(navigator.userAgent)) {
	} else {
		$(".msg_replie_btn").hide();
	}
	
	$(".timeline").find("li").hover(function(){
		$(this).find(".msg_replie_btn").css("display", "inline-block");
	}, function(){
		$(this).find(".msg_replie_btn").hide();
	});
	
	$("#allTimeline li").each(function(i,o) {
		if ($(o).text().indexOf("@" + $("#sideid").text()) > -1) {
			$(o).css("background-color", "#f9f6ea").hover(function(){}, function(){})
		}
	});
	/*
	$("#submit_btn").click(function(e){
		updateStatus();
		e.preventDefault();
	});*/
	timelineFocus();
	document.onclick = function(){
		document.title =document.title.replace(/(\([0-9]+\))/g, "");
		$(".allHighLight").text($(".allHighLight").text().replace(/(\([0-9]+\))/g, ""))
	}
	
	$("#allReplies").hide();
	$("#allMessage").hide();
	$("#allTimelineBtn").click(function(){
		$("#allTimeline").show();
		$("#allReplies").hide();
		$("#allMessage").hide();
		$("#allTimelineBtn").addClass("allHighLight");
		if ($("#allRepliesBtn").hasClass("allHighLight")) $("#allRepliesBtn").removeClass("allHighLight");
		else $("#allMessageBtn").removeClass("allHighLight");
		$("#allTimelineBtn").text($("#allTimelineBtn").text().replace(/(\([0-9]+\))/g, ""));
	})
	
	$("#allRepliesBtn").click(function(){
		$("#allTimeline").hide();
		$("#allReplies").show();
		$("#allMessage").hide();
		$("#allRepliesBtn").addClass("allHighLight");
		if ($("#allTimelineBtn").hasClass("allHighLight")) $("#allTimelineBtn").removeClass("allHighLight");
		else $("#allMessageBtn").removeClass("allHighLight");
		$("#allRepliesBtn").text($("#allRepliesBtn").text().replace(/(\([0-9]+\))/g, ""));
	})
	
	$("#allMessageBtn").click(function(){
		$("#allTimeline").hide();
		$("#allReplies").hide();
		$("#allMessage").show();
		$("#allMessageBtn").addClass("allHighLight");
		if ($("#allRepliesBtn").hasClass("allHighLight")) $("#allRepliesBtn").removeClass("allHighLight");
		else $("#allTimelineBtn").removeClass("allHighLight");
		$("#allMessageBtn").text($("#allMessageBtn").text().replace(/(\([0-9]+\))/g, ""));
	})
	
	$("#refreshBtn").click(function(e){
		e.preventDefault();
		if (updateCount == 0) update();
	})
	
	if (!location.href.split("?")[1] || location.href.split("?")[1] == "p=1") {
		setInterval(function(){
			update();
		}, 3000*60);
	}
	
	var updateCount = 0;
	function update() {
		if ($("#stop_refresh").attr('checked')) return;
		updateDate();
		if (updateCount == 0) {
			updateCount = 1;
			$("#refreshBtn").css("color", "#ccc");
			updateFunc("timeline");
			updateFunc("replies");
			updateFunc("message");
		}
	}

	function updateFunc(type, name, pw) {
		var div, url, btnDiv, param;
		switch (type) {
			case "timeline":
				div = "#allTimeline ol";
				btnDiv = "#allTimelineBtn";
				url = "ajax/updateTimeline.php";
				break;
			case "replies":
				div = "#allReplies ol";
				btnDiv = "#allRepliesBtn";
				url = "ajax/updateReplies.php";
				break;
			case "message":
				div = "#allMessage ol";
				btnDiv = "#allMessageBtn";
				url = "ajax/updateMessage.php";
				break;
		}
		
		var loginUser = $("#sideid").text();
		var lastDiv = $(div + " li:first-child");
		var isJumped = false;
		if (type == 'timeline') {
			while (lastDiv.find(".user_name").text() == loginUser) {
				isJumped = true;
				lastDiv = lastDiv.next();
			}
		}
			
		var since_id = lastDiv.find(".status_id").text();
		param = "since_id=" + since_id + (isJumped ? '&j=1' : '');
		
		$.ajax({
			url: url,
			type: "GET",
			dataType: "text",
			data: param,
			success: function(msg) {
				if ($.trim(msg).indexOf("</li>") > 0) {

					msg = $(msg);
					try {
						msg = showpic_main(msg);
					} catch (e) {}
					$(div).prepend(msg);
					
					var num = 0;
					var navNum = 0;
					if (document.title.match(/\d+/) != null) {
						num = parseInt(document.title.match(/\d+/));
					}
					document.title = "(" + (num+msg.length )+ ")" + document.title.replace(/(\([0-9]+\))/g, "");
		
					if ($(btnDiv).text().match(/\d+/) != null) {
						navNum = parseInt($(btnDiv).text().match(/\d+/));
					}
					$(btnDiv).text($(btnDiv).text().replace(/(\([0-9]+\))/g, "") + "(" + (navNum+msg.length )+ ")");

					hideBtn();
				}
			},
			complete: function(){
				updateCount ++;
				if (updateCount == 4) {
					$("#refreshBtn").css("color", "#000");
					updateCount = 0;
				}
			}
		});
	}
	$("#more_home").click(function(){
		updateMore("home");
	});
	$("#more_replie").click(function(){
		updateMore("replie");
	});
	function updateMore (type) {
		var o = type == "home" ? 
				{div: "#allTimeline ol", url: "ajax/updateTimeline.php", btn$:$("#more_home")} :
				{div: "#allReplies ol", url: "ajax/updateReplies.php", btn$:$("#more_replie")},
			max_id = $(o.div + " li:last .status_id").text();
			param = "max_id=" + max_id;
		o.btn$.attr("disabled", "disabled");
		$.ajax({
			url: o.url,
			type: "GET",
			dataType: "text",
			data: param,
			success: function(msg) {
				if ($.trim(msg).indexOf("</li>") > 0) {
					msg = $(msg);
					$(o.div).append(msg);
					hideBtn();
				}
			},
			complete: function(){
				o.btn$.attr("disabled", "");
			}
		});
	}
});
