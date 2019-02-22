<?php

require_once(dirname(__FILE__).'/const.php');
require_once(dirname(__FILE__).'/l10n.php');

// -----------------------------------------------------------------------------
// the ExecPhp_Script class displays the Exec-PHP javascript if necessary
// -----------------------------------------------------------------------------

if (!class_exists('ExecPhp_Script')) :

class ExecPhp_Script
{
	var $m_id = NULL;
	var $tab_name = NULL;
	var $m_l10n_tab = NULL;
	var $m_path = NULL;
	var $m_dependency = NULL;

	function ExecPhp_Script($id, $tab_name, $l10n_tab, $path, $dependency)
	{
		$this->m_id =& $id;
		$this->m_tab_name = $tab_name;
		$this->m_l10n_tab = $l10n_tab;
		$this->m_path = $path;
		$this->m_dependency = $dependency;

		if (function_exists('wp_enqueue_script'))
			wp_enqueue_script($this->m_id, ExecPhp_HOME_URL. $this->m_path, $this->m_dependency);
		else
			// WP < 2.1
			add_action('admin_head', array(&$this, 'action_admin_head_script'));

		if (!$this->m_l10n_tab)
			return;

		global $wp_version;

		if (version_compare($wp_version, '2.1.dev') >= 0)
			add_action('wp_print_scripts', array(&$this, 'action_wp_print_scripts'));
		else
			add_action('admin_head', array(&$this, 'action_admin_head_tab'));
	}

	// ---------------------------------------------------------------------------
	// hooks
	// ---------------------------------------------------------------------------

	function action_wp_print_scripts()
	{
		if (function_exists('wp_localize_script'))
		{
			$this->m_l10n_tab['l10n_print_after'] = 'try{convertEntities('. $this->m_tab_name. ');}catch(e){};';
			wp_localize_script($this->m_id, $this->m_tab_name, $this->m_l10n_tab);
		}
		else
			// WP < 2.2
			add_action('admin_head', array(&$this, 'action_admin_head_tab'));
	}

	function action_admin_head_script()
	{
?>
<script type='text/javascript' src='<?php echo ExecPhp_HOME_URL. $this->m_path; ?>'></script>
<?php
	}

	function action_admin_head_tab()
	{
?>
<script type='text/javascript'>
/* <![CDATA[ */
	<?php echo $this->m_tab_name; ?> = {
<?php
		foreach ($this->m_l10n_tab as $item => $value)
		{
			echo "\t\t$item: \"$value\",\n";
		}
		echo "\t\tlast: \"last\"\n\t}\n";
?>
	try{convertEntities(<?php echo $this->m_tab_name; ?>);}catch(e){};
/* ]]> */
</script>
<?php
	}

	// ---------------------------------------------------------------------------
	// tools
	// ---------------------------------------------------------------------------

	function print_message($heading, $text)
	{
		$heading = escape_dquote($heading);
		$text = escape_dquote($text);
?>
	<script type="text/javascript">
		//<![CDATA[
		ExecPhp_setMessage("<?php echo $heading; ?>", "<?php echo $text; ?>");
		//]]>
	</script>
<?php
	}
}
endif;

?>