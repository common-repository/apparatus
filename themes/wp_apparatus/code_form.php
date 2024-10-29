<?php
//code form
echo "\n";
?>
<!--Code Form -->
<div class="section-body">

<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
<span class="php">&nbsp;&lt;?php</span>
<textarea cols="" class="input" id="app_code" name="app_code" rows="30" ><?php echo app_get_code(false); ?></textarea><br />
<span class="php">&nbsp;?&gt;</span>
<p>
Show source with syntax highlighting: <input type="checkbox" value="1" name="app_highlight" <?php echo $app_highlight_checked; ?> />
</p>
<p>
Output Mode: <br />
&nbsp;&nbsp;&nbsp;Code: <input type="radio" name="app_mode" value="code" <?php echo $app_code_checked; ?> /> 
				  HTML: <input type="radio" name="app_mode" value="html" <?php echo $app_html_checked; ?> />
<input name="app_code_submit" type="submit" value="Run..." />
</p>
</form>
</div>
<br />
<!--END Code Form-->