<!--Settings-->
<div id="settings" class="hide">
	<div class="section-title" id="section-title">Settings:</div>
	<div class="section-body" id="section-body">
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			Template: <select name="template">
						<?php
						//generate options
						$templates = app_get_templates();
						foreach($templates as $t) : ?>
						<option value="<?php echo $t; ?>" <?php if($t == app_get_setting('template')) echo 'selected="selected"'; ?>><?php echo $t; ?></option>
						<?php endforeach; ?>
					  </select> <br />
			Tab Override: <input type="checkbox" name="tab_override" <?php if(app_get_setting('tab_override')) echo 'checked="checked" '; ?>/> 
			<br />
			Use Examples Mode: <input onclick="dbSettings()" id="examples_mode" type="checkbox" name="examples_mode" <?php if(app_get_setting('examples_mode')) echo 'checked="checked" '; ?>/>
			<div id="database-settings" class="hide">
				<table>
					<tr><td>Database Name:</td><td><input type="text" name="dbname" value="<?php echo app_get_setting('dbname'); ?>" /></td></tr>
					<tr><td>Database User:</td><td><input type="text" name="dbuser" value="<?php echo app_get_setting('dbuser'); ?>" /></td></tr>
					<tr><td>Database Password:</td><td><input type="password" name="dbpass" value="<?php echo app_get_setting('dbpass'); ?>" /></td></tr>
					<tr><td>Database Host:</td><td><input type="text" name="dbhost" value="<?php echo (app_get_setting('dbhost') == '') ? 'localhost' : app_get_setting('dbhost'); ?>" /></td></tr>
					<tr><td>Table Prefix:</td><td><input type="text" name="dbprefix" value="<?php echo app_get_setting('dbprefix'); ?>" /></td></tr>
				</table>
			</div>
			<input type="submit" name="settings_submit" value="Save" />
			<input type="hidden" name="app_settings" value="template,adv_checking,tab_override,examples_mode,dbname,dbuser,dbpass,dbhost,dbprefix" />
		</form>
	</div>
</div>
<br />
<!--END Settings-->