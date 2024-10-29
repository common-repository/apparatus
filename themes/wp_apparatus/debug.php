<!--Begin Debug-->
<div class="section-title">Debug:</div>
<div class="section-body">
	<strong>Session:</strong>
	<pre>
<?php echo nl2br(print_r($_SESSION, true)); ?>
	</pre>
	<strong>Settings:</strong>
	<pre>
<?php echo nl2br(print_r($app_settings, true)); ?>
	</pre>
</div>
<br />
<!--END Debug-->