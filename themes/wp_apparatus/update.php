<?php
//display output
global $app_version;
global $app_home;
	
$update_error; //will be true if an error has occured
$latest = @app_update($update_error); //returns true if an update is available
echo "\n";
?>
<!--Update-->
<div class="section-title">Status:</div>
<div class="section-body">
	<?php if($update_error) : ?>
	Could not check for updates at this time. Visit the <a href="<?php echo $app_home; ?>">Apparatus Home Page</a>
	or try again later
	<?php elseif($latest) : ?>
	A newer version of Apparatus is available, get <a href="<?php echo $app_home; ?>">Version <?php echo $latest; ?></a>.
	<?php else : ?>
	You are using the most current version of Apparatus
	<?php endif; ?>
</div>
<br />
<!--END Update-->
