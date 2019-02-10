<?php

require_once(dirname(__FILE__).'/cache.php');
require_once(dirname(__FILE__).'/const.php');
require_once(dirname(__FILE__).'/l10n.php');
require_once(dirname(__FILE__).'/script.php');

// -----------------------------------------------------------------------------
// the ExecPhp_WriteUi class displays the user warnings in case of false
// configuration
// -----------------------------------------------------------------------------

// use this guard to avoid error messages in WP admin panel if plugin
// is disabled because of a version conflict but you still try to reload
// the plugins config interface
if (!class_exists('ExecPhp_WriteUi')) :
class ExecPhp_WriteUi
{
	var $m_cache = NULL;
	var $m_script = NULL;

	// ---------------------------------------------------------------------------
	// init
	// ---------------------------------------------------------------------------

	function ExecPhp_WriteUi(&$cache, &$script)
	{
		$this->m_cache =& $cache;
		$this->m_script =& $script;

		add_action('edit_form_advanced', array(&$this, 'action_edit_form'));
		add_action('edit_page_form', array(&$this, 'action_edit_form'));
		add_action('sidebar_admin_page', array(&$this, 'action_sidebar_admin_page'));
	}

	// ---------------------------------------------------------------------------
	// hooks
	// ---------------------------------------------------------------------------

	function action_edit_form()
	{
		if ($this->rtfm_article())
		{
			$heading = __s('Exec-PHP WYSIWYG Conversion Warning.', ExecPhp_PLUGIN_ID);
			$text = __s('Saving this article will render all contained PHP code permanently unuseful. Even if you are saving this article through the Code editor. You can turn off this warning in your user profile. Ignore this warning in case this article does not contain PHP code. <a href="%s">Read the Exec-PHP documentation if you are unsure what to do next</a>.', ExecPhp_PLUGIN_ID
				, ExecPhp_HOME_URL. '/docs/'. __s('readme.html', ExecPhp_PLUGIN_ID). '#execute_php');
			$this->m_script->print_message($heading, $text);
		}
	}

	function action_sidebar_admin_page()
	{
		if ($this->rtfm_widget())
		{
			$heading = __s('Exec-PHP Widget Conversion Warning.', ExecPhp_PLUGIN_ID);
			$text = __s('Saving the widgets will render all contained PHP code permanently unuseful. Ignore this warning in case the text widgets do not contain PHP code. <a href="%s">Read the Exec-PHP documentation if you are unsure what to do next</a>.', ExecPhp_PLUGIN_ID
				, ExecPhp_HOME_URL. '/docs/'. __s('readme.html', ExecPhp_PLUGIN_ID). '#execute_php');
			$this->m_script->print_message($heading, $text);
		}
	}

	// ---------------------------------------------------------------------------
	// tools
	// ---------------------------------------------------------------------------

	// checks whether the author / editor has read the documentation
	function rtfm_article()
	{
		global $post;

		$current_user = wp_get_current_user();

		// the user turned off the wysiwyg warning in its preferences
		$usermeta =& $this->m_cache->get_usermeta($current_user->ID);
		if ($usermeta->hide_wysiwyg_warning())
			return false;

		if (!isset($post->author) || $post->post_author == $current_user->ID)
		{
			// the editor is equal to the writer of the article
			if (!current_user_can(ExecPhp_CAPABILITY_EXECUTE_ARTICLES))
				return false;
			if (!current_user_can(ExecPhp_CAPABILITY_WRITE_PHP))
				return true;
		}
		else
		{
			// the editor is different to the writer of the article
			$poster = new WP_User($post->post_author);
			if (!$poster->has_cap(ExecPhp_CAPABILITY_EXECUTE_ARTICLES))
				return false;
			// no check for posters write cap because the editor may want to
			// insert code after the poster created the article
		}
		if (!current_user_can(ExecPhp_CAPABILITY_WRITE_PHP))
			return true;
		if (user_can_richedit())
			return true;
		if (get_option('use_balanceTags'))
			return true;
		return false;
	}

	// checks whether the admin has read the documentation
	function rtfm_widget()
	{
		$option =& $this->m_cache->get_option();
		if (!$option->get_widget_support())
			return false;
		if (!current_user_can(ExecPhp_CAPABILITY_WRITE_PHP))
			return true;
		return false;
	}
}
endif;

?>