<?php

require_once __DIR__ . '/includes/content-syndicate.php';

add_filter( 'spine_child_theme_version', 'pharmacy_theme_version' );
add_action( 'init', 'pharmacy_register_footer_menu' );
add_filter( 'wsuwp_people_default_rewrite_slug', 'pharmacy_people_rewrite_arguments' );
add_filter( 'nav_menu_css_class', 'pharmacy_menu_classes', 11, 3 );

/**
 * Provides a theme version for use in cache busting.
 *
 * @since 0.0.2
 *
 * @return string
 */
function pharmacy_theme_version() {
	return '0.2.6';
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

/**
 * Filter menu item classes for Community Events pages.
 *
 * @param array    $classes Current list of nav menu classes.
 * @param WP_Post  $item    Post object representing the menu item.
 * @param stdClass $args    Arguments used to create the menu.
 *
 * @return array
 */
function pharmacy_menu_classes( $classes, $item, $args ) {
	// Bail if this isn't the site menu.
	if ( 'site' !== $args->menu ) {
		return $classes;
	}

	// Bail if we're not on a Community Events page.
	if ( ! tribe_is_community_edit_event_page() && ! tribe_is_community_my_events_page() ) {
		return $classes;
	}

	// Run applicable URLs through `trailingslashit` just to be safe.
	$item_url = trailingslashit( $item->url );
	$posts_page_url = trailingslashit( get_permalink( get_option( 'page_for_posts' ) ) );
	$add_event_page_url = trailingslashit( tribe_community_events_add_event_link() );

	// Remove classes from the Posts page (falsely has `active` set).
	if ( $item_url === $posts_page_url ) {
		$classes = array();
	}

	// Add the `active` class to the add event page when we're on the "My Events" page.
	if ( tribe_is_community_my_events_page() && $item_url === $add_event_page_url ) {
		$classes[] = 'active';
	}

	return $classes;
}
