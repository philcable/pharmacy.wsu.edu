<?php

include_once __DIR__ . '/includes/content-syndicate.php';

add_filter( 'spine_child_theme_version', 'pharmacy_theme_version' );
add_action( 'init', 'pharmacy_register_footer_menu' );

/**
 * Provides a theme version for use in cache busting.
 *
 * @since 0.0.2
 *
 * @return string
 */
function pharmacy_theme_version() {
	return '0.0.2';
}

/**
 * Registers the menu locations for the site footer.
 *
 * @since 0.0.1
 */
function pharmacy_register_footer_menu() {
	register_nav_menu( 'footer', 'Footer' );
}
