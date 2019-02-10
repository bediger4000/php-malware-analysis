<?php

require_once(dirname(__FILE__).'/cache.php');
require_once(dirname(__FILE__).'/const.php');

// -----------------------------------------------------------------------------
// the ExecPhp_Runtime class handles the execution of PHP code during
// access to the articles content or widget including checks against
// the exec_php / edit_others_php capability or plugin options respectivly
// -----------------------------------------------------------------------------

if (!class_exists('ExecPhp_Runtime')) :
class ExecPhp_Runtime
{
	var $m_cache = NULL;

	// ---------------------------------------------------------------------------
	// init
	// ---------------------------------------------------------------------------

	function ExecPhp_Runtime(&$cache)
	{
		$this->m_cache =& $cache;

		add_filter('the_content', array(&$this, 'filter_user_content'), 1);
		add_filter('the_content_rss', array(&$this, 'filter_user_content'), 1);
		add_filter('the_excerpt', array(&$this, 'filter_user_content'), 1);
		add_filter('the_excerpt_rss', array(&$this, 'filter_user_content'), 1);
		add_filter('widget_text', array(&$this, 'filter_widget_content'), 1);
		add_filter('user_has_cap', array(&$this, 'filter_user_has_cap'), 10, 3);
	}

	// ---------------------------------------------------------------------------
	// tools
	// ---------------------------------------------------------------------------

	function eval_php($content)
	{
		// to be compatible with older PHP4 installations
		// don't use fancy ob_XXX shortcut functions
		ob_start();
		eval("?>$content<?php ");
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	// ---------------------------------------------------------------------------
	// hooks
	// ---------------------------------------------------------------------------

	function filter_user_content($content)
	{
		global $post;

		// check whether the article author is allowed to execute PHP code
		if (!isset($post) || !isset($post->post_author))
			return $content;
		$poster = new WP_User($post->post_author);
		if (!$poster->has_cap(ExecPhp_CAPABILITY_EXECUTE_ARTICLES))
			return $content;
		return $this->eval_php($content);
	}

	function filter_widget_content($content)
	{
		// check whether the admin has configured widget support
		$option =& $this->m_cache->get_option();
		if (!$option->get_widget_support())
			return $content;

		return $this->eval_php($content);
	}

	function filter_user_has_cap($allcaps, $caps, $args)
	{
		// $allcaps = Capabilities the user currently has
		// $caps = Primitive capabilities being tested / requested
		// $args = array with:
		// $args[0] = original meta capability requested
		// $args[1] = user being tested
		// See code for assumptions

		// This handler is only set up to deal with the edit_others_pages
		// or edit_others_posts capability. Ignore all other calls into here.
		$pages_request = in_array('edit_others_pages', $caps);
		$posts_request = in_array('edit_others_posts', $caps);
		if ((!$pages_request && !$posts_request)
			|| ($pages_request && $posts_request)
			|| !$args[0] || !$args[1] || $args[1] == 0)
			return $allcaps;

		global $post;
		if (!isset($post))
			return $allcaps;
		$poster = new WP_User($post->post_author);
		if (!$poster->has_cap(ExecPhp_CAPABILITY_EXECUTE_ARTICLES))
			return $allcaps;

		$editor_has_edit_others_php = (in_array(ExecPhp_CAPABILITY_EDIT_OTHERS_PHP, $allcaps)
			&& $allcaps[ExecPhp_CAPABILITY_EDIT_OTHERS_PHP]);
		if ($editor_has_edit_others_php)
			return $allcaps;

		// article may contain PHP code due to the original posters capabilities
		// but the editor is not allowed to edit others PHP code, so filter out
		// requested edit_others_xxx settings from the allowed caps
		if ($pages_request)
			unset($allcaps['edit_others_pages']);
		if ($posts_request)
			unset($allcaps['edit_others_posts']);
		return $allcaps;
	}
}
endif;

?>