<?php
/*
Plugin Name: Exec-PHP
Plugin URI: http://bluesome.net/post/2005/08/18/50/
Description: Executes &lt;?php ?&gt; code in your posts, pages and text widgets.
Author: S&ouml;ren Weber
Author URI: http://bluesome.net
Version: 4.9
*/

require_once(dirname(__FILE__).'/includes/manager.php');

// ----------------------------------------------------------------------------
// main
// ----------------------------------------------------------------------------

global $g_execphp_manager;
if (!isset($g_execphp_manager))
	// strange assignment because of explaination how references work;
	// this will generate warnings with error_reporting(E_STRICT) using PHP5;
	// http://www.php.net/manual/en/language.references.whatdo.php
	$GLOBALS['g_execphp_manager'] =& new ExecPhp_Manager();

?>