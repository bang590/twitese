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
		
	/*
	$(".timeline li").each(function(i,o) {
		if ($(this).find(".status_word").text().indexOf("@" + $("#sideid").text()) > -1) {
			$(this).css("background-color", "#f9f6ea").hover(function(){}, function(){})
		}
	});
	*/
	$(".mention").hover(function(){}, function(){});
	timelineFocus();
	document.onclick = function(){
		document.title =document.title.replace(/(\([0-9]+\))/g, "");
	}
	
	if (!location.href.split("?")[1] || location.href.split("?")[1] == "p=1") {
		setInterval(function(){
				update();
		}, 1000*60);
	}
});

function update() {
	if ($("#stop_refresh").attr('checked')) return;
	updateDate();
	var loginUser = $("#sideid").text();
	var lastDiv = $(".timeline li:first-child");
	var isJumped = false;
	while (lastDiv.find(".user_name").text() == loginUser) {
		isJumped = true;
		lastDiv = lastDiv.next();
	}
		
	var since_id = lastDiv.find(".status_id").text();
	param = "since_id=" + since_id + (isJumped ? '&j=1' : '');
		
	$.ajax({
		url: "ajax/updateTimeline.php",
		type: "GET",
		dataType: "text",
		data: param,
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
