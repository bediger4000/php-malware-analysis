<?php
/**
 * Simppeli functions and definitions
 *
 * @package Simppeli
 */
 
/**
 * The current version of the theme.
 */
define( 'SIMPPELI_VERSION', '1.1.0' );

/**
 * The suffix to use for scripts.
 */
if ( ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) ) {
	define( 'SIMPPELI_SUFFIX', '' );
} else {
	define( 'SIMPPELI_SUFFIX', '.min' );
}

if ( ! function_exists( 'simppeli_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function simppeli_setup() {
	
	/**
	* Set the content width based on the theme's design and stylesheet.
	*/
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = apply_filters( 'simppeli_content_width', 750 ); /* pixels */
	}
	
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Simppeli, use a find and replace
	 * to change 'simppeli' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'simppeli', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1120, 9999, false );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'simppeli' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	
	/* Add theme support for refresh widgets. */
	add_theme_support( 'customize-selective-refresh-widgets' );
	
	/* Add support for logo. */
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 80,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', simppeli_fonts_url() ) );

}
endif; // simppeli_setup
add_action( 'after_setup_theme', 'simppeli_setup' );

/**
 * Enqueue scripts and styles.
 */
function simppeli_scripts() {
	
	/* Enqueue fonts. */
	wp_enqueue_style( 'simppeli-fonts', simppeli_fonts_url(), array(), null );
	
	/* Enqueue parent theme styles if using child theme. */
	if ( is_child_theme() ) {
		wp_enqueue_style( 'simppeli-parent-style', trailingslashit( get_template_directory_uri() ) . 'style' . SIMPPELI_SUFFIX . '.css', array(), SIMPPELI_VERSION );
	}
	
	/* Enqueue active theme styles. */
	wp_enqueue_style( 'simppeli-style', get_stylesheet_uri() );
	
	/* Enqueue skip link focus fix. */
	wp_enqueue_script( 'simppeli-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix' . SIMPPELI_SUFFIX . '.js', array(), SIMPPELI_VERSION, true );
	
	/* Enqueue comment reply. */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'simppeli_scripts' );

/**
 * Change [...] to ... Read more.
 *
 * @since 1.0.0
 */
function simppeli_excerpt_more() {

	/* Translators: The %s is the post title shown to screen readers. */
	$text = sprintf( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'simppeli' ), '<span class="screen-reader-text">' . get_the_title() . '</span>' );
	$more = sprintf( '&hellip; <p class="simppeli-read-more"><a href="%s" class="more-link">%s</a></p>', esc_url( get_permalink() ), $text );

	return $more;

}
add_filter( 'excerpt_more', 'simppeli_excerpt_more' );

if ( ! function_exists( 'simppeli_fonts_url' ) ) :
/**
 * Register Google fonts for Simppeli.
 *
 * @since 1.0.0
 * @return string Google fonts URL for the theme.
 */
function simppeli_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'simppeli' ) ) {
		$fonts[] = 'Noto Sans:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Serif, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'simppeli' ) ) {
		$fonts[] = 'Noto Serif:400italic,700italic,400,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'simppeli' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}
endif;

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Implement the Custom Background feature.
 */
require get_template_directory() . '/inc/custom-background.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
