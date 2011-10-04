<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/formfunc.js"></script>
<?php if (!isset($_sentText)) { ?>
<h2>随便说说</h2>
<div id="sent_function">
	<?php if(isBasicAuth()){?>
    <a href="javascript:void(0)" id="photoBtn"><img src="img/photo.gif" /></a>
    <?php }?>
	<a href="javascript:void(0)" id="linkBtn"><img src="img/link.gif" /></a>
</div>
<span id="tip">还可以输入<b>140</b>个字</span>
<?php } ?>
<?php if(isBasicAuth()){?>
<form enctype="multipart/form-data" action="ajax/uploadPhoto.php" method="post" id="photoArea">
	<span>图片上传：</span>
	<input name="image" id="imageFile" type="file" />
	<input type="submit" id="imageUploadSubmit" value="提交" />
</form>
<?php }?>
<div id="linkArea">
	<label>缩短URL：</label><input type="text" name="longurl" id="longurl" />
	<select name="shortUrlType" id="shortUrlType">
		<option value="aa.cx">aa.cx</option>
		<option value="is.gd">is.gd</option>
		<option value="s8.hk">s8.hk</option>
	</select>
	<input type="button" value="提交" id="linkSubmit" />
</div>
<form action="index.php" method="post" id="update_form">
	<textarea name="status" id="textbox"><?php if (isset($_sentText)) echo $_sentText ?></textarea>
	<input type="hidden" id="in_reply_to" name="in_reply_to" value="<?php echo isset($_sentInReplyTo) ? $_sentInReplyTo : 0 ?>" />
	<input type="submit" id="submit_btn" title="按Ctrl+Enter可以快捷发送" value="发送" />
</form>
<div class="clear"></div>