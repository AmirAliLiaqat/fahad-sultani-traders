<?php
/**
 * Fahad Sultani Traders Enqueue scripts and styles.
 *
 * @package Fahad_Sultani_Traders
 */

function fahad_sultani_traders_scripts() {
	/************ enqueue styling files and links ***************/
	wp_enqueue_style( 'fahad-sultani-traders-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'fahad-sultani-traders-bootstrap', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css", _S_VERSION );
    wp_enqueue_style( 'fahad-sultani-traders-font-awesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css", _S_VERSION );
	wp_style_add_data( 'fahad-sultani-traders-style', 'rtl', 'replace' );

	/************ enqueue scripting files and links ***************/
	wp_enqueue_script( 'fahad-sultani-traders-script', get_template_directory_uri() . '/js/script.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'fahad-sultani-traders-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
    wp_enqueue_script( 'fahad-sultani-traders-jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js',  _S_VERSION, true );
    wp_enqueue_script( 'fahad-sultani-traders-jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js',  _S_VERSION, true );
	wp_enqueue_script( 'fahad-sultani-traders-bootstrap', "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js", _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'fahad_sultani_traders_scripts' );