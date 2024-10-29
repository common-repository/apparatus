<?php
/*
Plugin Name: Apparatus
Plugin URI: http://tinsology.net/scripts/apparatus/
Description: Apparatus allows you to execute PHP code directly from your Wordpress admin area.
Version: 0.4
Author: Mathew Tinsley
Author URI: http://tinsology.net
License: GPL2
*/
/*  Copyright 2010  Mathew Tinsley  (email : tinsley@tinsology.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
register_activation_hook(__FILE__, 'apparatus_install');
add_action('admin_menu', 'apparatus_menu');
add_action('admin_head', 'apparatus_styles');

$app_settings;
$app_code;
$app_output;
$app_error;
$app_highlight;

function apparatus_menu()
{
	add_submenu_page('tools.php', 'Apparatus', 'Apparatus', 'administrator', 'apparatus.php', 'apparatus_draw');
}

function apparatus_styles()
{
	require_once('core/settings.php');

	require_once('core/version.php');
	require_once('core/functions.php');
	global $app_settings;
	$app_settings = new AppSettings();
	if($_GET['page'] == 'apparatus.php')
	{
		?>
		<link rel="stylesheet" href="<?php echo plugins_url('/apparatus/themes/wp_apparatus/style.css'); ?>" type="text/css" />
		<?php if(app_get_setting('tab_override')) : ?>
		<script src="<?php echo plugins_url('/apparatus/js/tab-override.js?v2'); ?>" type="text/javascript"></script>
		<?php endif; ?>
		<script src="<?php echo plugins_url('/apparatus/js/resizetxt.js') ;?>" type="text/javascript"></script>
		<script src="<?php echo plugins_url('/apparatus/js/functions.js'); ?>" type="text/javascript"></script>
		<?php
	}
}

function apparatus_install()
{
	require_once('core/settings.php');
	$app_settings = new AppSettings();
	$app_settings['template'] = 'wp_apparatus';
	$app_settings['tab_override'] = 1;
	$app_settings['error_search'] = 'errordatabase';
}

function apparatus_draw()
{
	$app_warnings = array();
	require_once('core/settings.php');

	require_once('core/version.php');
	require_once('core/functions.php');

	//prepare settings
	global $app_settings;
	$app_settings = new AppSettings();

	$app_code_checked = 'checked="checked"';
	$app_highlight_checked = 'checked="checked"';

	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if(isset($_POST['app_code_submit']))
		{
			//highlight checkbox
			global $app_highlight;
			$app_highlight = $_POST['app_highlight'];
			if($app_highlight)
				$app_highlight_checked = 'checked="checked"';
			else
				$app_highlight_checked = '';
			
			//output mode radio
			global $app_output_mode;
			$app_output_mode = $_POST['app_mode']; 
			$app_code_checked = ($app_output_mode == 'code') ? 'checked="checked"' : '';
			$app_html_checked = ($app_output_mode == 'html') ? 'checked="checked"' : '';
			
			global $app_code;
			global $app_output;
			global $app_error;
				
			$app_error = array();
				
			//BUG strip slashes caused escaped strings to fail
			//$app_code = $_POST['app_code'];
			$app_code = stripslashes($_POST['app_code']);
				
			ob_start();		
			$app_output = app_eval($app_code);
			$app_parse_err = strip_tags(ob_get_clean());

					/* It is not possible to catch parse errors in
					eval()'d code using an error handler. One solution
					is to parse out the error message from the output */
			if($app_parse_err != '')
			{
				if( preg_match( '/Parse error:\s*syntax error,(.+?)\s+in\s+.+?\s*line\s+(\d+)/', $app_parse_err, $match ) )
				{
					$app_error[] = array(
						'type'		=>	'Parse Error',
						'line'		=>	$match[2],
						'message'	=>	$match[1]
						);
				}
			}
		}
		elseif(isset($_POST['app_settings']))
		{
			$settingsArr = explode(',', $_POST['app_settings']);
			foreach($settingsArr as $s)
			{
				app_update_setting($s, $_POST[$s]);
			}
		}
	}

	//prepare theme
	if(file_exists('themes/wp_apparatus/reg.php'))
		require_once('themes/wp_apparatus/reg.php');
	else
	{
		$warnings['Template Not Found'] = array('Reverting to default template');
		$app_settings['template'] = 'apparatus';
		require_once('themes/wp_apparatus/reg.php');
	}

	$_SESSION['app_status'] = 'valid';
	//include(app_get_setting('template') . '/header.php');
	$app_elements = app_get_elements();
		
	foreach($app_elements as $e)
	{
		$callback = $e['callback'];
		$args = $e['args'];
		$file = $e['file'];
		if($callback == '' || call_user_func_array($callback, $args))
		{
			include('themes/wp_apparatus/' . $file);
			echo "\n";
		}
	}
	//include(app_get_setting('template') . '/footer.php');
}
?>