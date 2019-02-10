<?php

require_once(dirname(__FILE__).'/cache.php');
require_once(dirname(__FILE__).'/const.php');
require_once(dirname(__FILE__).'/l10n.php');
require_once(dirname(__FILE__).'/script.php');

// -----------------------------------------------------------------------------
// the ExecPhp_ConfigUi class displays the config interface in the
// admin menu
// -----------------------------------------------------------------------------

// use this guard to avoid error messages in WP admin panel if plugin
// is disabled because of a version conflict but you still try to reload
// the plugins config interface
if (!class_exists('ExecPhp_ConfigUi')) :

define('ExecPhp_ACTION_UPDATE_OPTIONS', 'execphp_update_options');

class ExecPhp_ConfigUi
{
	var $m_cache = NULL;
	var $m_script = NULL;

	// ---------------------------------------------------------------------------
	// init
	// ---------------------------------------------------------------------------

	// Sets up the Exec-Php config menu
	function ExecPhp_ConfigUi(&$cache, &$script)
	{
		$this->m_cache =& $cache;
		$this->m_script =& $script;

		$option =& $this->m_cache->get_option();
		$this->toggle_action($option->get_status());
		add_action('admin_menu', array(&$this, 'action_admin_menu'));
	}

	// ---------------------------------------------------------------------------
	// hooks
	// ---------------------------------------------------------------------------

	function action_admin_menu()
	{
		if (current_user_can(ExecPhp_CAPABILITY_EDIT_PLUGINS))
		{
			add_submenu_page('options-general.php',
				__s('Exec-PHP Settings', ExecPhp_PLUGIN_ID),
				__s('Exec-PHP', ExecPhp_PLUGIN_ID),
				ExecPhp_CAPABILITY_EDIT_PLUGINS, __FILE__,
				array(&$this, 'submenu_page_option_general'));
			add_filter('plugin_action_links', array(&$this, 'filter_plugin_actions_links'), 10, 2);
		}
	}

	function filter_plugin_actions_links($links, $file)
	{
		if ($file == ExecPhp_HOMEDIR. '/exec-php.php')
		{
			$settings_link = $settings_link = '<a href="options-general.php?page='. ExecPhp_HOMEDIR. '/includes/config_ui.php">' . __('Settings') . '</a>';
			array_unshift($links, $settings_link);
		}
		return $links;
	}

	function action_admin_footer_plugin_version()
	{
		$option =& $this->m_cache->get_option();
		$heading = __s('Exec-PHP Error.', ExecPhp_PLUGIN_ID);
		$text = __s('No necessary upgrade of the the Exec-PHP plugin could be performed. PHP code in your articles or widgets may be viewable to your blog readers. This is plugin version %1$s, previously there was version %2$s installed. Downgrading from a newer version to an older version of the plugin is not supported.', ExecPhp_PLUGIN_ID
			, ExecPhp_VERSION, $option->get_version());
		$this->m_script->print_message($heading, $text);
	}

	function action_admin_footer_unknown()
	{
		$option =& $this->m_cache->get_option();
		$heading = __s('Exec-PHP Error.', ExecPhp_PLUGIN_ID);
		$text = __s('An unknown error (%s) occured during execution of the Exec-PHP plugin. PHP code in your articles or widgets may be viewable to your blog readers. This error should never happen if you use the plugin with a compatible WordPress version and installed it as described in the documentation.', ExecPhp_PLUGIN_ID
			, $option->get_status());
		$this->m_script->print_message($heading, $text);
	}

	function toggle_action($status)
	{
		if ($status == ExecPhp_STATUS_PLUGIN_VERSION_MISMATCH)
			add_action('admin_footer', array(&$this, 'action_admin_footer_plugin_version'));
		else
			remove_action('admin_footer', array(&$this, 'action_admin_footer_plugin_version'));

		if ($status != ExecPhp_STATUS_OKAY
			&& $status != ExecPhp_STATUS_PLUGIN_VERSION_MISMATCH)
			add_action('admin_footer', array(&$this, 'action_admin_footer_unknown'));
		else
			remove_action('admin_footer', array(&$this, 'action_admin_footer_unknown'));
	}

	// ---------------------------------------------------------------------------
	// interface
	// ---------------------------------------------------------------------------

	function print_request_users($display_id, $feature, $title, $introduction)
	{
		global $wp_version;
		if (version_compare($wp_version, '2.6.dev') >= 0)
			// since WP 2.6 it comes with its own progress animation
			$image_url = get_option('siteurl'). '/wp-admin/images/loading.gif';
		else
			$image_url = ExecPhp_HOME_URL. '/images/progress.gif';
?>
			<fieldset class="options">
				<table class="editform optiontable form-table">
					<tr valign="top" id="<?php echo $display_id; ?>-container" >
						<th scope="row"><?php echo $title; ?></th>
						<td>
							<label for="<?php echo ExecPhp_POST_WIDGET_SUPPORT; ?>">
								<?php echo $introduction; ?>

								<div class="execphp-user-list" id="<?php echo $display_id; ?>">
									<?php _es('The list can not be displayed because you may have disabled Javascript or your browser does not support Javascript.', ExecPhp_PLUGIN_ID); ?>

								</div>
								<script type="text/javascript">
									//<![CDATA[
									document.getElementById("<?php echo $display_id; ?>").innerHTML =
										"<p><img src=\"<?php echo $image_url; ?>\" alt=\"<?php escape_dquote(_es('An animated icon signaling that this information is still be loaded.', ExecPhp_PLUGIN_ID)); ?>\" /> <?php escape_dquote(_es('Loading user information...', ExecPhp_PLUGIN_ID)); ?></p>";
									ExecPhp_subscribeForFeature("<?php echo $feature; ?>");
									//]]>
								</script>
							</label>
						</td>
					</tr>
				</table>
			</fieldset>
<?php
	}

	function submenu_page_option_general()
	{
		global $wpdb;
		global $wp_version;

		// executing form actions
		$option =& $this->m_cache->get_option();
		if (isset($_POST[ExecPhp_ACTION_UPDATE_OPTIONS]))
		{
			check_admin_referer(ExecPhp_ACTION_UPDATE_OPTIONS);
			$option->set_from_POST();
			$option->save();
			echo '<div id="message" class="updated fade"><p><strong>'.
				__s('Settings saved.', ExecPhp_PLUGIN_ID) . "</strong></p></div>\n";
		}
		$this->toggle_action($option->get_status());
?>
	<div class="wrap">
<?php if (version_compare($wp_version, '2.7.dev') >= 0) : ?>
		<div id="icon-options-general" class="icon32"><br /></div>
<?php endif; ?>
		<h2><?php _es('Exec-PHP Plugin', ExecPhp_PLUGIN_ID); ?></h2>
		<p><?php echo __s('Exec-PHP executes <code>&lt;?php ?&gt;</code> code in your posts, pages and text widgets. See the <a href="%s">local documentation</a> for further information. The latest version of the plugin, documentation and information can be found on the <a href="http://bluesome.net/post/2005/08/18/50/">official plugin homepage</a>.', ExecPhp_PLUGIN_ID, ExecPhp_HOME_URL. '/docs/'. __s('readme.html', ExecPhp_PLUGIN_ID)); ?></p>

<?php if (version_compare($wp_version, '2.2.dev') >= 0) : ?>
		<h3><?php _es('Settings', ExecPhp_PLUGIN_ID); ?></h3>

		<form action="" method="post" id="<?php echo ExecPhp_ID_CONFIG_FORM; ?>"<?php if (version_compare($wp_version, '2.5.dev') >= 0 && version_compare($wp_version, '2.6.dev') < 0) : ?> class="wp-2-5"<?php endif; ?>>
			<?php wp_nonce_field(ExecPhp_ACTION_UPDATE_OPTIONS); ?>

			<fieldset class="options">
				<table class="editform optiontable form-table">
					<tr valign="top">
						<th scope="row"><?php _es('Execute PHP code in text widgets', ExecPhp_PLUGIN_ID); ?></th>
						<td>
							<label for="<?php echo ExecPhp_POST_WIDGET_SUPPORT; ?>">
								<input type="checkbox" name="<?php echo ExecPhp_POST_WIDGET_SUPPORT; ?>" id="<?php echo ExecPhp_POST_WIDGET_SUPPORT; ?>" value="true" <?php if ($option->get_widget_support()) : ?>checked="checked" <?php endif; ?>/>
								<?php _es('Executing PHP code in text widgets is not restricted to any user. By default users who can modify text widgets will also be able to execute PHP code in text widgets. Unselect this option to generally turn off execution of PHP code in text widgets.', ExecPhp_PLUGIN_ID); ?>

							</label>
						</td>
					</tr>
				</table>
			</fieldset>

			<p class="submit">
				<input type="submit" name="<?php echo ExecPhp_ACTION_UPDATE_OPTIONS; ?>" class="button-primary" value="<?php _es('Save Changes', ExecPhp_PLUGIN_ID) ?>" />
			</p>
		</form>

<?php endif; ?>
		<h3><?php _es('Security Information', ExecPhp_PLUGIN_ID); ?></h3>
		<p><?php _es('The following lists show which users are allowed to write or execute PHP code in different cases. Allowing to write or execute PHP code can be adjusted by assigning the necessary capabilities to individual users or roles by using a role manager plugin.', ExecPhp_PLUGIN_ID); ?></p>

		<form action="" id="<?php echo ExecPhp_ID_INFO_FORM; ?>"<?php if (version_compare($wp_version, '2.5.dev') >= 0 && version_compare($wp_version, '2.6.dev') < 0) : ?> class="wp-2-5"<?php endif; ?>>
<?php $this->print_request_users(ExecPhp_ID_INFO_SECURITY_HOLE,
	ExecPhp_REQUEST_FEATURE_SECURITY_HOLE,
	__s('Security Hole', ExecPhp_PLUGIN_ID),
	__s('The following list shows which users have either or both of the &quot;%1$s&quot; or &quot;%2$s&quot; capability and are allowed to change others PHP code by having the &quot;%3$s&quot; capability but do not have the &quot;%4$s&quot; capability for themself. This is a security hole, because the listed users can write and execute PHP code in articles of other users although they are not supposed to execute PHP code at all.', ExecPhp_PLUGIN_ID, ExecPhp_CAPABILITY_EDIT_OTHERS_POSTS, ExecPhp_CAPABILITY_EDIT_OTHERS_PAGES, ExecPhp_CAPABILITY_EDIT_OTHERS_PHP, ExecPhp_CAPABILITY_EXECUTE_ARTICLES)); ?>

<?php if (version_compare($wp_version, '2.2.dev') >= 0) : ?>
<?php $this->print_request_users(ExecPhp_ID_INFO_WIDGETS,
	ExecPhp_REQUEST_FEATURE_WIDGETS,
	__s('Executing PHP Code in Text Widgets', ExecPhp_PLUGIN_ID),
	__s('The following list shows which users have the &quot;%s&quot; capability and therefore are allowed to write and execute PHP code in text widgets. In case you have deselected the option &quot;Execute PHP code in text widgets&quot; from above, this list will appear empty.', ExecPhp_PLUGIN_ID, ExecPhp_CAPABILITY_EXECUTE_WIDGETS)); ?>

<?php endif; ?>
<?php $this->print_request_users(ExecPhp_ID_INFO_EXECUTE_ARTICLES,
	ExecPhp_REQUEST_FEATURE_EXECUTE_ARTICLES,
	__s('Executing PHP Code in Articles', ExecPhp_PLUGIN_ID),
	__s('The following list shows which users have the &quot;%s&quot; capability and therefore are allowed to execute PHP code in articles.', ExecPhp_PLUGIN_ID, ExecPhp_CAPABILITY_EXECUTE_ARTICLES)); ?>
		</form>
	</div>
<?php
	}
}
endif;

?>