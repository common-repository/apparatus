<?php
class AppSettings implements ArrayAccess {
	
	function __construct()
	{

	}
	
	function offsetExists($offset)
	{
		if(get_option('app_' . $offset))
			return true;
			
		return false;
	}
	
	function offsetGet($offset)
	{
		return get_option('app_' . $offset);
	}
	
	function offsetSet($offset, $value)
	{
		update_option('app_' . $offset, $value);
	}
	
	function offsetUnset($offset)
	{
		delete_option('app_' . $offset);
	}
}
?>