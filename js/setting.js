$(function(){
	$('.style_input').ColorPicker({ 
	    onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		},
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val("#" + hex);
			$(el).ColorPickerHide();
		}
	}).bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
	
	var style = {
			"默认":{headerBg:"#DAD6C0", bodyBg:"#F5F3EC", sideBg:"#F9F8F5", sideNavBg:"#F4F4F4", linkColor:"#3280AB", linkHColor:"#000000", wordColor:"#000000", border:"#C7C5B8", line:"#FFFFFF"}, 
			"天蓝":{headerBg:"#a4ccd6", bodyBg:"#dfe9eb", sideBg:"#eff7ea", sideNavBg:"#f5fcff", linkColor:"#0066CC", linkHColor:"#000000", wordColor:"#000000", border:"#B2D1A3", line:"#FFFFFF"},
			"粉红":{headerBg:"#e696ce", bodyBg:"#fff7fd", sideBg:"#fff5fc", sideNavBg:"#fff9f2", linkColor:"#d145a2", linkHColor:"#000000", wordColor:"#000000", border:"#C7C5B8", line:"#94296F"},
			"黑白":{headerBg:"#080809", bodyBg:"#e6e6e6", sideBg:"#f5f5f5", sideNavBg:"#f0f0f0", linkColor:"#3280AB", linkHColor:"#000000", wordColor:"#333333", border:"#B2D1A3s", line:"#FFFFFF"}};

	$.each(style, function (i,o) {
	    $("#styleSelect").append('<option value="' + i + '">' + i + '</option>');
	});
	$("#styleSelect").change(function(){
	    if ($(this).val() != "n/a") {
	        $.each(style[$(this).val()], function (i,o) {
				$("#"+i).val(o);
	        });
	    }
	});
});