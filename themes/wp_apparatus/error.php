<!-- Errors -->
<div class="section-title">Errors/Warnings/Notices:</div>
<div class="section-body">
<table class="error-table">
	<tr>
		<td>Type:</td>
		<td>Line:</td>
		<td>Message:</td>
	</tr>
	<?php while(app_has_error()) : ?>
		<?php $e = app_get_error(); ?>
		<tr>
			<td><?php echo $e->type; ?></td>
			<td><?php echo $e->line; ?></td>
			<td><?php echo $e->message; ?></td>
		</tr>
	<?php endwhile; ?>
</table>
</div>
<br />
<!-- END Errors -->