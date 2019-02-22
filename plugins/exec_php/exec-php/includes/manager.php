<?php

require_once(dirname(__FILE__).'/admin.php');
require_once(dirname(__FILE__).'/ajax.php');
require_once(dirname(__FILE__).'/cache.php');
require_once(dirname(__FILE__).'/const.php');
require_once(dirname(__FILE__).'/runtime.php');

// -----------------------------------------------------------------------------
// the ExecPhp_Manager class handles the plugin initialization phase,
// assuring the infrastructure is set up properly
// -----------------------------------------------------------------------------

if (!class_exists('ExecPhp_Manager')) :
class ExecPhp_Manager
{
	var $m_ajax = NULL;
	var $m_runtime = NULL;
	var $m_admin = NULL;

	// ---------------------------------------------------------------------------
	// init
	// ---------------------------------------------------------------------------

	function ExecPhp_Manager()
	{
		add_action('init', array(&$this, 'action_init'));
	}

	// ---------------------------------------------------------------------------
	// hooks
	// ---------------------------------------------------------------------------

	function action_init()
	{
		$cache =& new ExecPhp_Cache();
		$this->m_ajax =& new ExecPhp_Ajax($cache);
		$this->m_runtime =& new ExecPhp_Runtime($cache);
		$this->m_admin =& new ExecPHP_Admin($cache);
	}
}
endif;

?>