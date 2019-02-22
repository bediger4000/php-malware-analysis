<?php

require_once(dirname(__FILE__).'/const.php');
require_once(dirname(__FILE__).'/l10n.php');

// -----------------------------------------------------------------------------
// the ExecPhp_Ajax class handles the AJAX communication incoming from the
// AdminUi for requesting which users are allowed to execute PHP in widgets
// and articles
// -----------------------------------------------------------------------------

if (!class_exists('ExecPhp_Ajax')) :
class ExecPhp_Ajax
{
	var $m_cache = NULL;

	// ---------------------------------------------------------------------------
	// init
	// ---------------------------------------------------------------------------

	function ExecPhp_Ajax(&$cache)
	{
		$this->m_cache =& $cache;

		global $wp_version;
		if (version_compare($wp_version, '2.5.dev') >= 0 && !defined('DOING_AJAX'))
			return;

		add_action('wp_ajax_'. ExecPhp_ACTION_REQUEST_USERS,
			array(&$this, 'action_ajax_request_user'));
	}

	// ---------------------------------------------------------------------------
	// hooks
	// ---------------------------------------------------------------------------

	function action_ajax_request_user()
	{
		global $wpdb;

		if (!current_user_can(ExecPhp_CAPABILITY_EDIT_PLUGINS)
			&& !current_user_can(ExecPhp_CAPABILITY_EDIT_USERS))
			die('-1');

		$feature = explode(',', $_POST['feature']);
		$wants_edit_others_php = in_array(ExecPhp_REQUEST_FEATURE_SECURITY_HOLE, $feature);
		$wants_switch_themes = in_array(ExecPhp_REQUEST_FEATURE_WIDGETS, $feature);
		$wants_exec_php = in_array(ExecPhp_REQUEST_FEATURE_EXECUTE_ARTICLES, $feature);

		$query = "SELECT ID AS user_id FROM {$wpdb->users} ORDER BY display_name";
		$wpdb->query($query);
		$s = $wpdb->get_results($query);
		if (!is_array($s))
			$s = array();

		$option =& $this->m_cache->get_option();
		$widget_support = $option->get_widget_support();

		$output_edit_others_php = '';
		$output_switch_themes = '';
		$output_exec_php = '';
		foreach ($s as $i)
		{
			$user =& new WP_User($i->user_id);
			$has_switch_themes = $user->has_cap(ExecPhp_CAPABILITY_EXECUTE_WIDGETS);
			$has_exec_php = $user->has_cap(ExecPhp_CAPABILITY_EXECUTE_ARTICLES);
			$has_edit_others_posts = $user->has_cap(ExecPhp_CAPABILITY_EDIT_OTHERS_POSTS);
			$has_edit_others_pages = $user->has_cap(ExecPhp_CAPABILITY_EDIT_OTHERS_PAGES);
			$has_edit_others_php = $user->has_cap(ExecPhp_CAPABILITY_EDIT_OTHERS_PHP);

			if (($has_edit_others_posts || $has_edit_others_pages)
				&& $has_edit_others_php && !$has_exec_php && $wants_edit_others_php)
				$output_edit_others_php .= "<li>{$user->data->display_name}</li>";
			if ($has_switch_themes && $widget_support && $wants_switch_themes)
				$output_switch_themes .= "<li>{$user->data->display_name}</li>";
			if ($has_exec_php && $wants_exec_php)
				$output_exec_php .= "<li>{$user->data->display_name}</li>";
		}
		$output_edit_others_php = $this->adjust_reply('edit_others_php', $output_edit_others_php);
		$output_switch_themes = $this->adjust_reply('switch_themes', $output_switch_themes);
		$output_exec_php = $this->adjust_reply('exec_php', $output_exec_php);
		die($output_edit_others_php. $output_switch_themes. $output_exec_php);
	}

	// ---------------------------------------------------------------------------
	// tools
	// ---------------------------------------------------------------------------

	function adjust_reply($js_var, $output)
	{
		if (!empty($output))
			$output = "$js_var = \"<ul>". escape_dquote($output). "</ul>\"; ";
		return $output;
	}
}
endif;

?>