=== Exec-PHP ===
Contributors: McShelby
Tags: code, exec, execute, eval, php, run
Requires at least: 2.0.11
Tested up to: 2.8
Stable tag: 4.9

The Exec-PHP plugin executes PHP code in posts, pages and text widgets.

== Description ==

The Exec-PHP plugin executes PHP code in posts, pages and text widgets.

Features:

* Executes PHP code in the excerpt and the content portion of your posts and pages
* Configurable execution of PHP code in text widgets (for WordPress 2.2 or higher)
* Write PHP code in familiar syntax, eg. `<?php ... ?>`
* Works in your newsfeeds
* Information about which users are allowed to execute PHP with the current security settings (for WordPress 2.1 or higher)
* Configurable user warnings for inappropriate blog and user settings (for WordPress 2.1 or higher)
* Restrict execution of PHP code in posts and pages to certain users by using roles and capabilities
* Update notifications through the 'Plugins' menu in WordPress if a new version of the Exec-PHP plugin is available (for WordPress 2.3 or higher)
* Internationalization support (english and german included, many more available)
* Comes with documentation

For support and further information about the Exec-PHP plugin see the plugins homepage at [http://bluesome.net/post/2005/08/18/50/](http://bluesome.net/post/2005/08/18/50/ "Link to Exec-PHPs homepage").

== Installation ==

If you have ever installed a WordPress plugin, then installation will be pretty easy:

1. Download the Exec-PHP plugin archive and extract the files
1. Copy the resulting exec-php directory into /wp-content/plugins/
1. Activate the plugin through the 'Plugins' menu of WordPress
1. Configure blog and user settings if needed

For support and further information about the Exec-PHP plugin see the plugins homepage at [http://bluesome.net/post/2005/08/18/50/](http://bluesome.net/post/2005/08/18/50/ "Link to Exec-PHPs homepage").

== Frequently Asked Questions ==

= Where do I get support and further information =

For support and further information about the Exec-PHP plugin see the plugins homepage at [http://bluesome.net/post/2005/08/18/50/](http://bluesome.net/post/2005/08/18/50/ "Link to Exec-PHPs homepage").

== Screenshots ==

1. The Exec-PHP configuration menu
2. An Exec-PHP warning in the 'Write' menu
3. Exec-PHP warning configuration in the 'Users &gt; Your Profile' menu

== Changelog ==

= 4.9 (2009-01-07) =
* Requirements: WordPress 2.0.11 or higher
* Feature: Improved performance during loading admin interface
* Feature: New 'Settings' link in WordPress 'Plugin' menu
* Feature: WYSIWYG Conversion Warning now also displays for WordPress 2.0.11

= 4.8 (2008-07-05) =
* Requirements: WordPress 2.0 or higher
* Feature: Support for WordPress 2.6 (relocation of wp-content)

= 4.7 (2008-05-05) =
* Requirements: WordPress 2.0 or higher
* Bugfix: For PHP4 the cache instance wasn't a reference, which was a bug but did not cause any known issues
* Bugfix: Now Javascript works with single quotes for translated text
* Feature: Increased performance for AJAX call
* Feature: Better localization support inside of the plugin and the readme

= 4.6 (2008-04-06) =
* Requirements: WordPress 2.0 or higher
* Feature: In case of AJAX error retry call at most three more times
* Bugfix: Making Exec-PHP configuration menu valid XHTML

= 4.5 (2008-03-24) =
* Requirements: WordPress 2.0 or higher
* Bugfix: Fixing WordPress 2.1.x compatibility
* Bugfix: WYSIWYG Conversion Warning now displays correctly for pages, too
* Change: Performance optimization during plugin initialization
* Change: Nonintrusive AJAX error display
* Feature: Plugin interface support for WordPress 2.5

= 4.4 (2008-01-29) =
* Requirements: WordPress 2.0 or higher
* Bugfix: Incompatibilites with WP-Shopping-Cart because of Javascript global variable clash
* Change: New directory structure

= 4.3 (2007-12-11) =
* Requirements: WordPress 2.0 or higher
* Bugfix: Requirements lowered to WordPress 2.0 or higher
* Bugfix: Delay loading of text translations to support language switching plugins
* Feature: The WYSIWYG Conversion Warning can now be turned off through the Profile menu of the user

= 4.2 (2007-11-03) =
* Requirements: WordPress 2.2 or higher
* Change: Remodeling the Information section of the plugin configuration menu
* Feature: Showing security alarms in the Information section of the plugin configuration menu
* Feature: A warning will be printed on the 'Write' and the 'Widgets' menu in case blog or user settings will screw up written PHP code during saving the article or widgets

= 4.1 (2007-10-27) =
* Requirements: WordPress 2.2 or higher
* Bugfix: Display of the Exec-PHP configuration menu was restricted by an inappropriate capability
* Bugfix: Making Exec-PHP configuration menu valid XHTML
* Feature: The Exec-PHP configuration menu now displays which user is allowed to write and execute PHP. Display of this list is executed with AJAX. Therefore even for large WordPress installations with many users, the time to load the Exec-PHP configuration menu will still be satisfiying

= 4.0 (2007-10-25) =
* Requirements: WordPress 2.0 or higher
* Bugfix: When the blog administrator removes the 'exec_php' capability from all roles, the plugin will not reassign the capability to the Administrator and Editor roles
* Change: For new plugin installations only the Administrator role will be eligable to execute PHP code
* Feature: Configurable execution of PHP code in text widgets through the Exec-PHP configuration menu. This will only work with native widgets support introduced in WordPress 2.2 or higher

= 3.4 (2007-10-08) =
* Requirements: WordPress 2.0 or higher
* Feature: Now supports execution of code in text widgets
* Feature: Now supports plugin upgrade notification through the 'Plugins' menu of WordPress by listing it in the <a href="http://wordpress.org/extend/plugins/exec-php/">official WordPress plugin repository</a>

= 3.3 (2007-08-11) =
* Bugfix: Removing spaces around PHP code
* Bugfix: Removing obsolete plugin hooks for WordPress 1.x

= 3.2 (2007-02-10) =
* Bugfix: Removing obsolete config interface hooks

= 3.1 (2007-02-09) =
* Bugfix: Removing tag style converter because a) it caused a serious slow down in the WordPress admin interface and b) PCRE proved to be very buggy and unreliable. Note for myself: Never use PCRE again!
* Feature: Adding internationalization (just to be complete)
* Feature: Now works in RSS feeds

= 3.0 (2006-08-06) =
* Feature: Removing all alternative PHP tag styles like <code>&#91;?php ?&#93;</code> and <code>&lt; ?php ?&gt;</code>, because regex was buggy and to tough to support
* Feature: Removing support for WordPress 1.x, because regex was buggy and to tough to support
* Feature: Moving plugin files to plugins subdirectory
* Feature: Adding tag style converter
* Feature: Adding support for excerpt field
* Bugfix: Because of changes to PHP tag handling, the bug reported in comment 84 is fixed

= 2.0 (2005-12-22) =
* Feature: For WordPress 2.0 execution of PHP is now restricted to Administrators or Editors
* Feature: Supporting alternative PHP tags <code>&#91;?php ?&#93;</code>

= 1.2 (2005-12-04) =
* Bugfix: Reparing issue with reopening PHP tags (Test #16)

= 1.1 (2005-08-19) =
* Bugfix: Escaped string delimiters in PHP strings are now parsed correctly

= 1.0 (2005-08-18) =
* Feature: Allows `<?php ?>` tags inside your articles to execute the code inside of it
