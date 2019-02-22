<?php

if (!class_exists('ExecPhp_L10n')) :

if (!function_exists('translate')) :
// downward compatibility for older WP installations
function translate($text, $domain)
{
	global $l10n;

	if (isset($l10n[$domain])) {
		return $l10n[$domain]->translate($text);
	} else {
		return $text;
	}
}
endif;

if (!function_exists('translate_sprintf')) :
function translate_sprintf($text, $domain = 'default')
{
	if (func_num_args() <= 2)
		return translate($text, $domain);
	$args = func_get_args();
	array_shift($args);
	array_shift($args);
	array_unshift($args, translate($text, $domain));
	return call_user_func_array('sprintf', $args);
}
endif;

if (!function_exists('__s')) :
function __s($text, $domain = 'default')
{
	$args = func_get_args();
	return call_user_func_array('translate_sprintf', $args);
}
endif;

if (!function_exists('_es')) :
function _es($text, $domain = 'default')
{
	$args = func_get_args();
	echo call_user_func_array('translate_sprintf', $args);
}
endif;

if (!function_exists('escape_dquote')) :
function escape_dquote($text)
{
	return str_replace('"', '\"', $text);
}
endif;

class ExecPhp_L10n
{
}
endif;

?>