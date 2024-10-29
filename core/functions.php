<?php
/*
* functions.php
* Core File: Do not edit!
*/

/*
* NOTES
* preg_replace('/\/*(.|\n)*\*\//', '', $text);
* preg_replace('/\/\/(.*)\n/', '', $text);
*/

/**
* Template Functions
*/
function app_has_code()
{
	global $app_highlight;
	global $app_code;
	
	return ($app_highlight == 1 && $app_code != '');
}

function app_get_code($highlight = true)
{
	global $app_code;
	if(!$highlight)
		return $app_code;
		
	return app_highlight($app_code);
}

function app_get_output($tags = true)
{
	global $app_output;
	global $app_output_mode;
	
	if($app_output_mode == 'code')
	{
		if($tags)
			return "<code>\n" . str_replace(array("\n", "    "), array('<br />', '&nbsp;&nbsp;&nbsp;&nbsp;'), htmlspecialchars($app_output)) . "\n</code>";
		else
			return str_replace(array("\n", "    "), array('<br />', '&nbsp;&nbsp;&nbsp;&nbsp;'), htmlspecialchars($app_output));
	}
	
	return $app_output;
}

function app_has_output()
{
	global $app_output;
	
	return strlen(trim($app_output)) > 0;
}

function app_has_error()
{
	global $app_error;
	
	if(!is_array($app_error))
		return false;
	
	if(key($app_error) !== null)
		return true;
	else
	{
		@reset($app_error);
		return false;
	}
}

function app_get_error()
{
	global $app_error;
	
	$e = (object)current($app_error);
	next($app_error);
	
	return $e;
}

function app_check_update()
{
	if($_GET['update'] == 1)
		return true;
		
	return false;
}

function app_check_settings()
{
	if($_GET['settings'] == 1 || isset($_POST['app_settings']))
		return true;
		
	return false;
}

/**
* Mod Functions
*/
function app_get_mods()
{
	global $app_mods;
	
	$mods = array();
	$dir = './mods';
	foreach(new DirectoryIterator($dir) as $info)
	{
		if($info->isDir() && !$info->isDot())
		{
			$fname = str_replace('.php', '', $info->getFilename());
			if(file_exists($dir . '/' . $fname . '/' . $fname . '.php'))
			{
				$mods[] = $fname;
				if(isset($app_mods[$fname]))
					$app_mods[$fname]['include'] = $dir . '/' . $fname . '/' . $fname . '.php';
				else
				{
					$app_mods[$fname] = array();
					$app_mods[$fname]['status'] = 'ready';
					$app_mods[$fname]['include'] = $dir . '/' . $fname . '/' . $fname . '.php';
				}
			}
		}
		elseif($info->isFile())
		{
			$mods[] = $fname;
			if(isset($app_mods[$fname]))
				$app_mods[$fname]['include'] = $dir . '/' . $fname . '.php';
			else
			{
				$app_mods[$fname] = array();
				$app_mods[$fname]['status'] = 'ready';
				$app_mods[$fname]['include'] = $dir . '/' . $fname . '.php';
			}
		}
	}
	
	return $mods;
}

function mod_activate($mod)
{
	
}
/*
function app_register_mod($name, $file)
{
	global $app_mods;
	if($app_mods[$name])
		return;
		
	$mod = array('name' => $name,
				 'file' => $file,
				 'status' => 'ready');
}
*/
function app_mod_install($name)
{
	global $app_warnings;
	global $app_mods;
	
	if(!$app_mods[$name])
	{
		$app_warnings[] = 'Cannot install mod: ' . $name . ' is not available';
		return;
	}
	
	require_once('mods/' . $app_mods[$name]['file']);
}
/**
* Core Functions
*/

/**
* Syntax Highlighting Functions
*/
//returns an associative array containing each line of code, line number, and error info
function app_highlight($source_code)
{
	if (is_array($source_code))
		return false;

	$source_code = explode("\n", str_replace(array("\r\n", "\r"), "\n", $source_code));
	$line_count = 1;
	$source = array();

	foreach ($source_code as $code_line)
	{
		$err = ''; //place holder
		$errMsg = '';
		
		global $app_error;
		$c = count($app_error);
		
		$errExists = false;
		
		for($i = 0; ($i < $c) && !$errExists; $i++)
		{
			
			if($app_error[$i]['line'] == $line_count)
			{
				$err = $app_error[$i]['type'];
				$errMsg = $app_error[$i]['message'];
				$errExists = true;
			}
		}
		
		if (preg_match('/<\?(php)?[^[:graph:]]/', $code_line) || $code_line == '<?php')
			$formatted_code = str_replace(array('<code>', '</code>'), '', app_highlight_string($code_line));
		else
			$formatted_code = preg_replace('/(&lt;\?php&nbsp;)+/', '', str_replace(array('<code>', '</code>'), '', app_highlight_string($code_line, '<?php ')));
			
		$source[] = (object) array('num' 		=> $line_count, 
								   'code' 		=> $formatted_code, 
								   'error' 		=> $err, 
								   'error_msg' 	=> $errMsg);
		$line_count++;
	}
	
	return $source;
}

//helper function
//addresses comment bug in above function
function app_highlight_string($code, $append = '')
{
	global $comment_toggle;
	
	$out;
	if($comment_toggle)
		$out = str_replace('/*[temp]', '', highlight_string($append . '/*[temp]' . $code, true));
	else
		$out = highlight_string($append . $code, true);
	
	if(strstr($code, '/*'))
		$comment_toggle = true;
		
	if(strstr($code, '*/'))
		$comment_toggle = false;
		
	return $out;
}

/**
* Page Element Functions
*/

$GLOBALS['app_elements'] = array();

function app_register_element($file)
{
	$args = array();
	$callback = '';
	if(func_num_args() > 1)
	{
		$args = func_get_args();
		array_shift($args); //remove $file
		//array_shift($args); //remove $position
		$callback = array_shift($args);
	}
	
	$GLOBALS['app_elements'][] = array('file' => $file,
										'callback' => $callback, 
										'args' => $args);
}

function app_get_elements()
{
	$app_elements = $GLOBALS['app_elements'];
	return  $app_elements;
}

/**
* Setting functions
*/
function app_register_setting($name, $label, $type, $default = null, $options = null, $group = null)
{
	global $app_settings;
	$form_group = ($group) ? $group : 'main';
	$app_settings['template_settings'][$group][$name] = array('label' => $label, 'type' => $type, 'default' => $default, 'options' => $options);
}

function app_get_form_settings($group = null)
{
	return ($group) ? $app_settings['template_settings'][$group] : $app_settings['template_settings'];
}

function app_settings_draw($excludes = null)
{
	global $app_settings;
	$groups = array();
	if($excludes && is_array($excludes))
	{
		foreach($app_settings['template_settings'] as $group => $elements)
		{
			if(!in_array($group, $excludes))
			{
				$groups[$group] = $elements;
			}
		}
	}
	else
	{
		$groups = $app_settings['template_settings'];
	}
	$names = array();
	ob_start();
	foreach($groups as $group => $elements) : ?>
		<div id="<?php echo $group; ?>" class="<?php echo $group; ?>">
		
			<?php foreach($elements as $name => $e) : $names[] = $name; ?>
			
			<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
			<?php //determine the input type and format accordingly ?>
				<?php switch($e['type']) : /* workaround for switch syntax */
				
					  case 'textarea' : ?>
						<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>"><?php echo ($app_settings[$name] == '') ? $e['default']: $app_settings[$name]; ?></textarea>
				<?php break; ?>
				
				<?php case 'checkbox' : ?>
						<?php 
						if(isset($app_settings[$name]))
							if($app_settings[$name])
								$checked = ' checked="checked"';
						elseif($e['default'])
							$checked = ' checked="checked"';
						?>
						<input type="checkbox" id="<?php echo $name; ?>" name="<?php echo $name; ?>"<?php echo $checked; ?> value="1" />
				<?php break; ?>
				
				<?php case 'radio' : ?>
						<?php $radios = $e['options']; ?>
						<span id="<?php echo $name; ?>">
						<?php foreach($radios as $rName => $rValue) : ?>
							<label for="<?php echo $rName; ?>"><?php echo $rName; ?></label>
							<input type="radio" id="<?php echo $rName; ?>" name="<?php echo $name; ?>" value="<?php echo $rValue; ?>"<?php ($rValue == $app_settings[$name]) ? ' checked="checked"' : ''; ?> />
						<?php endforeach; ?>
						</span>
				<?php break; ?>
				<?php case 'select' : ?>
						<?php $opts = $e['options']; ?>
						<select id="<?php echo $name; ?>" name="<?php echo $name; ?>">
						<?php foreach($opts as $oName => $oValue) : ?>
							<option value="<?php echo $oValue; ?>"<?php if($t == app_get_setting($name)) echo ' selected="selected"'; ?>><?php echo $oName; ?></option>
						<?php endforeach; ?>
						</select>
				<?php break; ?>
				<?php default : ?>
						<input type="<?php echo $e['type']; ?>" id="<?php echo $name; ?>" name="<?php echo $name; ?>" value="<?php echo (!isset($app_settings[$name])) ? $e['default']: $app_settings[$name]; ?>" />
				<?php endswitch; ?>
				
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
	<input type="hidden" value="<?php echo implode(',', $names); ?>" name="app_settings" />
	<input type="submit" name="settings_submit" value="Save" />
	<?php	
	return ob_get_clean();
}

function app_get_setting($key)
{
	global $app_settings;
	return $app_settings[$key];
}

function app_update_setting($key, $value, $add = true)
{
	global $app_settings;
	if(!app_setting_exists($key))
	{
		if($add)
			return app_add_setting($key, $value, false);
		
		return false;
	}

	$app_settings[$key] = $value;
	
	return true;
}

function app_add_setting($key, $value, $update = true)
{
	global $app_settings;
	if(app_setting_exists($key))
	{
		if($update)
			return app_update_setting($key, $value, false);
		
		return false;
	}

	$app_settings[$key] = $value;
	
	return true;
}

function app_setting_exists($key)
{
	global $app_settings;
	if(array_key_exists($key, $app_settings))
		return true;
	
	return false;
}

function app_get_templates()
{
	$templates = array();
	$dir = './themes';
	foreach(new DirectoryIterator($dir) as $info)
	{
		if($info->isDir() && !$info->isDot())
		{
			if(file_exists($dir . '/' . $info->getFilename() . '/reg.php'))
				$templates[] = $info->getFilename();
		}
	}
	
	return $templates;
}

/**
* Eval Functions
*/

function app_eval($app_code)
{
	set_error_handler('app_error_handler');
		$app_pre = 'ob_start();';
		$app_post = ' $apparatusOutput = ob_get_clean();';
		eval($app_pre . $app_code . $app_post);	
	restore_error_handler();
	$app_output = $apparatusOutput;
	
	return $app_output;
}

/**
* Error Functions
*/

function app_functions_exist($code, &$undefined)
{
	$constructs = array('array', 'for', 'foreach', 'if', 'elseif', 'while', 'function',
						'switch', 'case', 'and', 'or', 'as', 'catch', 'clone',
						'declare', 'new', 'use', 'xor', 'die', 'echo', 'empty',
						'exit', 'eval', 'include', 'include_once', 'isset',
						'list', 'require', 'require_once', 'return', 'print',
						'unset');
	$undefined = array();
	
	preg_match_all('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*[ ]*\(/', $code, $matches, PREG_OFFSET_CAPTURE);
	$matches = $matches[0];
	//print_r($matches);

	foreach($matches as $m)
	{
		$func = str_replace(array(' ', '('), '', $m[0]);
		//if the function does not exist
		if(!function_exists($func))
		{
			//and is not currently being defined
			if(!preg_match('/function[ ]*' . $func .'/', $code) && 
			   !preg_match('/class[ ]*' . $func .'/', $code) &&
			   !preg_match('/new[ ]*' . $func .'/', $code))
			{
				//and is not a comment
				if(!preg_match('/\/\*(.|\n)*'. $func .'(.|\n)*\*\//', $code) && !preg_match('/\/\/(.*)'. $func .'(.*)\n/', $code))
				{
					//and is not a object method
					if(!preg_match('/->' . $func . '/', $code))
						//and is not a construct
						if(!in_array($func, $constructs))
						{
							//$undefined[] = $m;
							$offset = $m[1];
							$line = 1;
							for($i = 0; $i < $offset; $i++)
							{
								if($code[$i] == "\n")
									$line++;
							}
							$undefined[] = array(
								'type'		 => 'Fatal error',
								'line'		 => $line,
								'message'	 => 'Call to undefined function ' . $func . '()'
								);
						}
				}
			}
		}
	}
	
	if(count($undefined) == 0)
		return true;
	
	return false;
}

function app_error_handler($errno, $errstr, $errfile, $errline)
{
	global $app_error;
	
	switch($errno)
	{
		case E_PARSE:
		case E_ERROR:
		case E_COMPILE_ERROR:
		case E_USER_ERROR:
			$app_error[] = array('type' => 'Error', 'message' => $errstr, 'line' => $errline);
		break;
		case E_WARNING:
		case E_COMPILE_WARNING:
		case E_USER_WARNING:
			$app_error[] = array('type' => 'Warning', 'message' => $errstr, 'line' => $errline);
		break;
		case E_NOTICE:
		case E_USER_NOTICE:
			$app_error[] = array('type' => 'Notice', 'message' => $errstr, 'line' => $errline);
		break;
		default:
			$app_error[] = array('type' => 'Unknown', 'message' => $errstr, 'line' => $errline);
	}
	return true;
}

/**
* Version Functions
*/

//checks if a newer version is available
//returns false if up to date, returns the
//latest version if not
function app_update(&$error)
{
	global $app_version;
	global $app_version_check;
	
	$error = false;
	$params = array('http' => array(
					'method' => 'GET'
               ));
	$ctx = stream_context_create($params);
	$req = @file_get_contents($app_version_check, null, $ctx);
	if(!$req)
	{
		$error = true;
		return false;
	}
	
	if(app_version_compare($app_version, $req) == -1)
		return $req;
		
	return false;
}

//Compares two version strings, returns 1 if test is >, 0 if =, -1 if <
//Only works with numeric version strings (ie #.#... ex 3.1.2)
function app_version_compare($test, $current)
{

	$currArr = explode('.', $current);
	$testArr = explode('.', $test);
	$testCount = count($testArr);
	$currCount = count($currArr);
	
	//select the string with the fewest elements
	$c = ($testCount < $currCount) ? $testCount : $currCount;

	//scan the string from major to minor
	for($i = 0; $i < $c; $i++)
	{
		//if the ith element of test is greater than curr
		if((int)$testArr[$i] > (int)$currArr[$i])
			return 1;
		elseif((int)$testArr[$i] < (int)$currArr[$i])
			return -1;
	}

	//so far they are equal

	//if test has more elements than curr
	if($testCount > $currCount)
		return 1;
	elseif($testCount < $currCount)
		return -1;
	
	//if we make it this far they are equal
	return 0;
}