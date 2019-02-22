<?php

// -----------------------------------------------------------------------------
// the ExecPhp_Const function defines plugin wide constants
// -----------------------------------------------------------------------------

if (!class_exists('ExecPhp_Const')) :

define('ExecPhp_VERSION', '4.9');
define('ExecPhp_PLUGIN_ID', 'exec-php');

// relative path of stored plugins to ABSPATH; only required for WP < 2.6
global $wp_version;
if (version_compare($wp_version, '2.6.dev') >= 0)
{
	// relative path of plugin to PLUGINDIR
	$execphp_path = str_replace('\\', '/', dirname(dirname(__FILE__)));
	$execphp_plugin_path = str_replace('\\', '/', WP_PLUGIN_DIR);
	define('ExecPhp_HOMEDIR', trim(str_replace($execphp_plugin_path, '', $execphp_path), '/'));
}
else
{
	// ExecPhp_PLUGINDIR only available for WP < 2.6
	if (defined('PLUGINDIR'))
		define('ExecPhp_PLUGINDIR', PLUGINDIR);
	else
		define('ExecPhp_PLUGINDIR', 'wp-content/plugins');

	// relative path of plugin to PLUGINDIR
	$execphp_path = str_replace('\\', '/', dirname(dirname(__FILE__)));
	$execphp_offset = 0;
	while (($execphp_n = strpos($execphp_path, ExecPhp_PLUGINDIR, $execphp_offset)) !== false)
		$execphp_offset = $execphp_n + 1;
	define('ExecPhp_HOMEDIR', substr($execphp_path, $execphp_offset + strlen(ExecPhp_PLUGINDIR)));
}

if (defined('WP_PLUGIN_DIR'))
	define('ExecPhp_HOME_DIR', WP_PLUGIN_DIR. '/'. ExecPhp_HOMEDIR);
else
	define('ExecPhp_HOME_DIR', ABSPATH. ExecPhp_PLUGINDIR. '/'. ExecPhp_HOMEDIR);

if (defined('WP_PLUGIN_URL'))
	define('ExecPhp_HOME_URL', WP_PLUGIN_URL. '/'. ExecPhp_HOMEDIR);
else
	define('ExecPhp_HOME_URL', get_option('siteurl'). '/'. ExecPhp_PLUGINDIR. '/'. ExecPhp_HOMEDIR);

define('ExecPhp_CAPABILITY_EXECUTE_WIDGETS', 'switch_themes');
define('ExecPhp_CAPABILITY_EXECUTE_ARTICLES', 'exec_php');
define('ExecPhp_CAPABILITY_WRITE_PHP', 'unfiltered_html');
define('ExecPhp_CAPABILITY_EDIT_PLUGINS', 'edit_plugins');
define('ExecPhp_CAPABILITY_EDIT_USERS', 'edit_users');
define('ExecPhp_CAPABILITY_EDIT_OTHERS_POSTS', 'edit_others_posts');
define('ExecPhp_CAPABILITY_EDIT_OTHERS_PAGES', 'edit_others_pages');
define('ExecPhp_CAPABILITY_EDIT_OTHERS_PHP', 'edit_others_php');

define('ExecPhp_STATUS_OKAY', 0);
define('ExecPhp_STATUS_UNINITIALIZED', 1);
define('ExecPhp_STATUS_PLUGIN_VERSION_MISMATCH', 2);

define('ExecPhp_ACTION_REQUEST_USERS', 'execphp_request_users');
define('ExecPhp_REQUEST_FEATURE_SECURITY_HOLE', 'security_hole');
define('ExecPhp_REQUEST_FEATURE_WIDGETS', 'widgets');
define('ExecPhp_REQUEST_FEATURE_EXECUTE_ARTICLES', 'execute_articles');

define('ExecPhp_POST_WIDGET_SUPPORT', 'execphp_widget_support');
define('ExecPhp_POST_WYSIWYG_WARNING', 'execphp_wysiwyg_warning');

define('ExecPhp_ID_CONFIG_FORM', 'execphp-configuration');
define('ExecPhp_ID_INFO_FORM', 'execphp-information');

define('ExecPhp_ID_INFO_SECURITY_HOLE', 'execphp-security-hole');
define('ExecPhp_ID_INFO_WIDGETS', 'execphp-widgets');
define('ExecPhp_ID_INFO_EXECUTE_ARTICLES', 'execphp-execute-articles');
define('ExecPhp_ID_MESSAGE', 'execphp-message');

define('ExecPhp_ID_SCRIPT_COMMON', 'execphp_common');
define('ExecPhp_ID_SCRIPT_ADMIN', 'execphp_admin');
define('ExecPhp_ID_L10N_COMMON', 'execphpCommonL10n');
define('ExecPhp_ID_L10N_ADMIN', 'execphpAdminL10n');
define('ExecPhp_ID_STYLE_ADMIN', 'execphp_admin');

class ExecPhp_Const
{
}
endif;

?>