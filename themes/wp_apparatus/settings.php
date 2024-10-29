<!--Settings-->
<div id="settings" class="hide">
	<div class="section-title" id="section-title">Settings:</div>
	<div class="section-body" id="section-body">
		<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
			Tab Override: <input type="checkbox" value="1" name="tab_override" <?php if(app_get_setting('tab_override')) echo 'checked="checked" '; ?>/>
			<br />
			Error Search:	<a href="http://errordatabase.info">ErrorDatabase</a><input type="radio" value="errordatabase" name="error_search" <?php if(app_get_setting('error_search') == 'errordatabase') echo 'checked="checked" '; ?>/>
							Google<input type="radio" value="google" name="error_search" <?php if(app_get_setting('error_search') != 'errordatabase') echo 'checked="checked" '; ?>/>
			<br />
			<input type="submit" name="settings_submit" value="Save" />
			<input type="hidden" name="app_settings" value="tab_override,error_search" />
		</form>
	</div>
</div>
<br />
<!--END Settings-->