<?php

require_once(dirname(__FILE__).'/option.php');
require_once(dirname(__FILE__).'/usermeta.php');

// -----------------------------------------------------------------------------
// the ExecPhp_Cache serves as a cache for the option and usermeta
// -----------------------------------------------------------------------------

if (!class_exists('ExecPhp_Cache')) :
class ExecPhp_Cache
{
	var $m_option = NULL;
	var $m_usermetas = array();

	// ---------------------------------------------------------------------------
	// init
	// ---------------------------------------------------------------------------

	function ExecPhp_Cache()
	{
		$this->m_option =& new ExecPhp_Option();
	}

	// ---------------------------------------------------------------------------
	// access
	// ---------------------------------------------------------------------------

	function &get_option()
	{
		return $this->m_option;
	}

	function &get_usermeta($user_id)
	{
		if (!isset($this->m_usermetas[$user_id]))
			// this will generate warnings with error_reporting(E_STRICT) using PHP5
			// see http://www.php.net/manual/en/language.references.whatdo.php
			$this->m_usermetas[$user_id] =& new ExecPhp_UserMeta($user_id);
		return $this->m_usermetas[$user_id];
	}
}
endif;

?>