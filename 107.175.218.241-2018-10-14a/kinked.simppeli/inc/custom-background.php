<?php
/**
 * Custom background feature
 *
 * @package Simppeli
 */

/**
 * Adds support for the WordPress 'custom-background' theme feature.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function simppeli_custom_background_setup() {

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'simppeli_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
}
add_action( 'after_setup_theme', 'simppeli_custom_background_setup', 15 );
