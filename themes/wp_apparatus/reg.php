<?php
/**
* The following define page elements and their
* order. Elements are displayed in ascending order
* (lowest first). Duplicate position values will
* overwrite existing values.
*
* 0.2.1 added callback and args parameters
*/
app_register_element('header.php');
app_register_element('update.php', 'app_check_update');
app_register_element('settings.php');
app_register_element('wordpress_code.php', 'app_has_code');
app_register_element('error.php', 'app_has_error');
app_register_element('output.php', 'app_has_output');
app_register_element('code.php', 'app_has_code');
app_register_element('code_form.php');
//app_register_element('debug.php', 7);
app_register_element('footer.php');

//app_register_element('my_file.php', x,[ callback,[ args... ] ]); 
/**
* where x is the order the element will appear in,
* callback is the function to call to determine if the
* element is to be displayed, and args are the arguments
* to that function. Each argument should be passed as
* its own parameter, NOT an array of arguments
*/

/**
*	Register Settings
*		added version 0.4
*/
/*
app_register_setting('template', 'Template: ', 'select', 'apparatus', array_combine(app_get_templates(), app_get_templates()));
app_register_setting('tab_override', 'Tab Override: ', 'checkbox', true);
app_register_setting('examples_mode', 'Use Examples Mode: ', 'checkbox', false);
*/

?>