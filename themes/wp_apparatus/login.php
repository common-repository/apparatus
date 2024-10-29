<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
		<link rel="shortcut icon" href="themes/<?php echo $app_settings['template']; ?>/favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="themes/<?php echo $app_settings['template']; ?>/login.css" />
		<title>Apparatus - Secure Login</title>
	</head>
	<body>
	<h1>Apparatus<span class="version">v<?php echo $app_version; ?></span></h1>
	<div align="center">
		<div class="loginBox">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				Key:<br />
				<input class="key" type="password" name="app_pass" /><br />
				<?php if($warnings['invalid'] == 1) : ?>
				<span class="invalid">Invalid Username/Password</span>
				<?php endif; ?>
				<input class="button" type="submit" value="login" />
				<br class="clear" />
			</form>
		</div>
		Powered By <a href="http://tinsology.net/scripts/apparatus/">Tinsology.net</a>
	</div>
	</body>
</html>