<!--Code-->
<?php
$code = app_get_code();
?>
<div class="section-title">Code:</div>
<div class="section-body">
	<table class="source">
<?php foreach($code as $line) : ?>
<?php
//prepare error icon
$err = '';
if($line->error != '')
{
	if($line->error == 'Notice')
		$src = 'notice.png';
	elseif($line->error == 'Warning' || $line->error == 'Unknown')
		$src = 'warning.png';
	else
		$src = 'error.png';
	
	if($app_settings['error_search'] == 'errordatabase')
		$href = "http://errordatabase.info/wiki/Special:Search?search={$line->error}:{$line->error_msg}&fulltext=Search";
	else 
		$href = 'http://google.com/search?q=' . urlencode('php ' . $line->error . ': ' . $line->error_msg);
	
	$err = '<a 
				href="'.$href.'"
				target="_blank">
					<img src="'. plugins_url('/apparatus/themes/wp_apparatus/images/' . $src) . '"
						 width="16" height="16" border="0" 
						 title="'.$line->error.'" 
						 alt="'.$line->error.'" />
			</a>';
}
?>
		<tr>
			<td><?php echo $err; ?></td>
			<td><?php echo $line->num; ?></td>
			<td><?php echo $line->code; ?></td>
		</tr>	
<?php endforeach; ?>
	</table>
</div>
<br />
<!--END Code-->