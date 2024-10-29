<!--Wordpress Code-->
<?php
global $app_output_mode;

if($app_output_mode == 'code')
	$lang = 'text';
else
	$lang = 'html';
?>
<div id="wordpress_code" class="hide">
<div class="section-title">Wordpress Code:</div>
<div class="section-body">
<textarea class="input" rows="20" readonly="yes">
Code:
[sourcecode language="php"]
<?php echo app_get_code(false) . "\n"; ?>
[/sourcecode]
<?php if(app_has_output()) : ?>
Output:
[sourcecode language="<?php echo $lang; ?>"]
<?php echo app_get_output(false) . "\n"; ?>
[/sourcecode]
<?php endif; ?>
</textarea>
</div>
</div>
<br />
<!--END Wordpress Code-->