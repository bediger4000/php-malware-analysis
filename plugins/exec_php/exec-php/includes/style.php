<?php

require_once(dirname(__FILE__).'/const.php');

// -----------------------------------------------------------------------------
// the ExecPhp_Style class displays the Exec-PHP style sheet if necessary
// -----------------------------------------------------------------------------

if (!class_exists('ExecPhp_Style')) :
class ExecPhp_Style
{
	function ExecPhp_Style()
	{
		if (current_user_can(ExecPhp_CAPABILITY_EDIT_PLUGINS)
			|| current_user_can(ExecPhp_CAPABILITY_EDIT_USERS))
		{
			if (function_exists('wp_enqueue_style'))
				wp_enqueue_style(ExecPhp_ID_STYLE_ADMIN, ExecPhp_HOME_URL. '/css/admin.css');
			else
				// WP < 2.6
				add_action('admin_head', array(&$this, 'action_admin_head'), 5);
		}
	}

	// ---------------------------------------------------------------------------
	// hooks
	// ---------------------------------------------------------------------------

	function action_admin_head()
	{
?>
<link rel='stylesheet' href='<?php echo ExecPhp_HOME_URL; ?>/css/admin.css' type='text/css' />
<?php
	}
}
endif;

?>