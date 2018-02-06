<?php

require_once __DIR__ . '/includes/content-syndicate.php';

add_filter( 'spine_child_theme_version', 'pharmacy_theme_version' );
add_action( 'init', 'pharmacy_register_footer_menu' );
add_filter( 'wsuwp_people_default_rewrite_slug', 'pharmacy_people_rewrite_arguments' );

/**
 * Provides a theme version for use in cache busting.
 *
 * @since 0.0.2
 *
 * @return string
 */
function pharmacy_theme_version() {
	return '0.2.0';
}

/**
 * Registers the menu locations for the site footer.
 *
 * @since 0.0.1
 */
function pharmacy_register_footer_menu() {
	register_nav_menu( 'footer', 'Footer' );
}

/**
 * Filter the rewrite arguments passed to register_post_type by the people directory.
 *
 * @param array|bool $rewrite False by default. Array if previously filtered.
 *
 * @return array
 */
function pharmacy_people_rewrite_arguments( $rewrite ) {
	return array(
		'slug' => 'directory',
		'with_front' => false,
	);
}
