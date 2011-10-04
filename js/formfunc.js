$(function(){
	$("#photoBtn").click(function(){
		$("#photoArea").toggle(500);
	});
	
	$("#linkBtn").click(function(){
		$("#linkArea").toggle(500);
	});
	
	$("#imageUploadSubmit").click(function(e){
		e.preventDefault();
		ajaxFileUpload();
	});
	$("#linkSubmit").click(function(e){
		e.preventDefault();
		shortUrl();
	});
});

function ajaxFileUpload()
{
	tipStart("正在上传图片...");
	
    $.ajaxFileUpload({
        url:'ajax/uploadPhoto.php',
        secureuri:false,
        fileElementId:'imageFile',
        dataType: 'json',
        success: function (data, status)
        {
            if(typeof(data.result) != 'undefined' && data.result == "success") {
            	$("#textbox").val($("#textbox").val() + data.url);
				tipEnd("图片上传完成");
        		$("#photoArea").fadeOut(1500);
            } else {
				tipEnd("图片上传失败，请重试", true);
            }
        },
        error: function (data, status, e){
        	tipEnd("图片上传失败，请求无响应", true);
        }
    })
    return false;
}  

function shortUrl() {

	tipStart("正在缩短网址...");
	
	var longurl = $("#longurl").val();
	var type = $("#shortUrlType").val();
	$.ajax({
		url: "ajax/shortUrl.php",
		type: "POST",
		dataType: "text",
		data: "longurl=" + longurl + "&type=" + type,
		success: function(msg) {
			if ($.trim(msg).indexOf("error") < 0) {
            	$("#textbox").val($("#textbox").val() + $.trim(msg));
	        	tipEnd("缩短网址成功");
            	$("#linkArea").fadeOut(1500);
			} else {
	        	tipEnd("缩短网址失败，请确认网址格式或选择其他短网址。", true);
			}
		},
        error: function (msg){
        	tipEnd("缩短网址失败，请求无响应", true);
        }
	});
}
