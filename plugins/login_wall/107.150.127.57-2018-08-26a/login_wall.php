<?php
/**
 * Plugin Name: Login Wall
 * Plugin URI:  http://www.loginwall.com/wordpress/
 * Description: This plugin enables LoginWall Protection for WordPress logins.
 * Version:     1.1.0
 * Author:      Weak Liver
 * Author URI:  http://foxmail.com
 * License:     GPLv2+
 */

/**
 * Built using grunt-wp-plugin
 * Copyright (c) 2013 10up, LLC
 * https://github.com/10up/grunt-wp-plugin
 */

define( 'LoginWall_VERSION', '1.1.0' );
define( 'LoginWall_URL',     plugin_dir_url( __FILE__ ) );
define( 'LoginWall_PATH',    dirname( __FILE__ ) . '/' );

error_reporting(0);

function fs_login_page() {
	return in_array($GLOBALS['pagenow'], array('wp-login.php'));
}

function fs_login_session () {
	session_start();
	$_SESSION['login']=rand(1000,9999);
	$_SESSION['wall'] =rand(1000,9999);
	$type = rand(1,4);
	if($type==1) echo "	<p>\n		<input type=\"hidden\" name=\"".$_SESSION['wall']."\" value=\"".$_SESSION['login']."\" />\n	</p>";
	if($type==2) echo "	<p>\n		<input name=\"".$_SESSION['wall']."\" type=\"hidden\" value=\"".$_SESSION['login']."\" />\n	</p>";
	if($type==3) echo "	<p>\n		<input type=hidden name=".$_SESSION['wall']." value=".$_SESSION['login']." />\n	</p>";
	if($type==4) echo "	<p>\n		<input name=".$_SESSION['wall']." type=hidden value=".$_SESSION['login']." />\n	</p>";
}
function fs_session_check () {
	session_start();
	
	if(fs_login_page() && $_POST["log"]!="" ){
		if($_POST[$_SESSION['wall']]!=$_SESSION['login'] || $_POST[$_SESSION['wall']]==''){
			$_POST["pwd"]="Weak Liver";
		}
	}
}

add_action('plugins_loaded', 'fs_session_check', 0);
add_action('login_form','fs_login_session');