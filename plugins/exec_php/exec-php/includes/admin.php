<?php

require_once(dirname(__FILE__).'/cache.php');
require_once(dirname(__FILE__).'/const.php');
require_once(dirname(__FILE__).'/config_ui.php');
require_once(dirname(__FILE__).'/l10n.php');
require_once(dirname(__FILE__).'/script.php');
require_once(dirname(__FILE__).'/style.php');
require_once(dirname(__FILE__).'/user_ui.php');
require_once(dirname(__FILE__).'/write_ui.php');

// -----------------------------------------------------------------------------
// the ExecPhp_Admin class provides functionality common to all displayed
// admin menus
// -----------------------------------------------------------------------------

// use this guard to avoid error messages in WP admin panel if plugin
// is disabled because of a version conflict but you still try to reload
// the plugins config interface
if (!class_exists('ExecPhp_Admin')) :
class ExecPhp_Admin
{
	var $m_cache = NULL;
	var $m_common_script = NULL;
	var $m_common_l10n = NULL;
	var $m_style = NULL;
	var $m_write_ui = NULL;
	var $m_user_ui = NULL;
	var $m_admin_script = NULL;
	var $m_admin_l10n = NULL;
	var $m_config_ui = NULL;

	// ---------------------------------------------------------------------------
	// init
	// ---------------------------------------------------------------------------

	function ExecPhp_Admin(&$cache)
	{

		$this->m_cache =& $cache;

		if (!is_admin())
			return;

		global $wp_version;
		if (version_compare($wp_version, '2.6.dev') >= 0)
			load_plugin_textdomain(ExecPhp_PLUGIN_ID, false, ExecPhp_HOMEDIR. '/languages');
		else
			load_plugin_textdomain(ExecPhp_PLUGIN_ID, ExecPhp_PLUGINDIR. '/'. ExecPhp_HOMEDIR. '/languages');

		$this->m_common_l10n = array(
			'messageContainer' => ExecPhp_ID_MESSAGE);
		$this->m_common_script =& new ExecPhp_Script(ExecPhp_ID_SCRIPT_COMMON,
			ExecPhp_ID_L10N_COMMON, $this->m_common_l10n, '/js/common.js', array());

		$this->m_write_ui =& new ExecPhp_WriteUi($this->m_cache, $this->m_common_script);
		$this->m_user_ui =& new ExecPhp_UserUi($this->m_cache);
		add_action('admin_notices', array(&$this, 'action_admin_notices'), 5);

		if (version_compare($wp_version, '2.1.dev') < 0)
			return;

		$this->m_style =& new ExecPhp_Style();
		$this->m_config_ui =& new ExecPhp_ConfigUi($this->m_cache, $this->m_common_script);

		if (current_user_can(ExecPhp_CAPABILITY_EDIT_PLUGINS)
			|| current_user_can(ExecPhp_CAPABILITY_EDIT_USERS))
		{
			$this->m_admin_l10n = array(
				'noUserFound' => escape_dquote(__s('No user matching the query.', ExecPhp_PLUGIN_ID)),
				'securityAlertHeading' => escape_dquote(__s('Exec-PHP Security Alert.', ExecPhp_PLUGIN_ID)),
				'securityAlertText' => escape_dquote(__s('The Exec-PHP plugin found a security hole with the configured user rights of this blog. For further information consult the plugin configuration menu or contact your blog administrator.', ExecPhp_PLUGIN_ID)),
				'requestFile' => get_option('siteurl'). '/wp-admin/admin-ajax.php',
				'ajaxError' => escape_dquote(__s("Exec-PHP AJAX HTTP error when receiving data: ", ExecPhp_PLUGIN_ID)),
				'action' => ExecPhp_ACTION_REQUEST_USERS,
				'executeArticlesContainer' => ExecPhp_ID_INFO_EXECUTE_ARTICLES,
				'widgetsContainer' => ExecPhp_ID_INFO_WIDGETS,
				'securityHoleContainer' => ExecPhp_ID_INFO_SECURITY_HOLE);
			$this->m_admin_script =& new ExecPhp_Script(ExecPhp_ID_SCRIPT_ADMIN,
				ExecPhp_ID_L10N_ADMIN, $this->m_admin_l10n, '/js/admin.js', array('sack'));

			add_action('admin_footer', array(&$this, 'action_admin_footer'));
		}
	}

	// ---------------------------------------------------------------------------
	// hooks
	// ---------------------------------------------------------------------------

	function action_admin_notices()
	{
?>
<div id="<?php echo ExecPhp_ID_MESSAGE; ?>"></div>
<?php
	}

	function action_admin_footer()
	{
?>
	<script type="text/javascript">
		//<![CDATA[
		ExecPhp_subscribeForFeature("<?php echo ExecPhp_REQUEST_FEATURE_SECURITY_HOLE; ?>");
		ExecPhp_requestUser();
		//]]>
	</script>
<?php
	}
}
endif;

?>