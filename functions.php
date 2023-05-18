<?php
/**
 * Fahad Sultani Traders functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Fahad_Sultani_Traders
 */

// solution of cannot modigy header information header already send by
// ob_clean();
// ob_start();

define("HOME_URL",trailingslashit(home_url()));
define("THEME_URI",trailingslashit(get_template_directory_uri()));
define("THEME_ABS",trailingslashit(get_template_directory()));

// include_once THEME_ABS.'inc/generic_functions.php';
include_once THEME_ABS.'inc/functions.php';

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

// using the hook on a custom query on the WordPress index front end page
// function my_custom_something($query) {
//     if ( !is_admin() && !$query->is_main_query() ) {
//         do_action( 'wpml_switch_language', "ur" );
//     }
// }
// add_action('pre_get_posts', 'my_custom_something');

if(is_admin()){
	/**
	 * Theme Installation
	 */
	// include_once THEME_ABS.'inc/theme_installation.php';
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
require get_template_directory() . '/inc/theme-supports.php';

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function fahad_sultani_traders_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'fahad_sultani_traders_content_width', 640 );
}
add_action( 'after_setup_theme', 'fahad_sultani_traders_content_width', 0 );

/**
 * Register widget area.
 */
require get_template_directory() . '/inc/widgets.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue-styles-and-scripts.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

