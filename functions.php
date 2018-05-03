<?php

require_once __DIR__ . '/includes/content-syndicate.php';

add_filter( 'spine_child_theme_version', 'pharmacy_theme_version' );
add_action( 'init', 'pharmacy_register_footer_menu' );
add_filter( 'wsuwp_people_default_rewrite_slug', 'pharmacy_people_rewrite_arguments' );
add_filter( 'nav_menu_css_class', 'pharmacy_menu_classes', 11, 3 );
add_filter( 'the_title', 'pharmacy_people_degrees', 10, 2 );

/**
 * Provides a theme version for use in cache busting.
 *
 * @since 0.0.2
 *
 * @return string
 */
function pharmacy_theme_version() {
	return '0.2.10';
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
 * Filter menu item classes for Community Events pages and people profiles.
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

	// Run applicable URLs through `trailingslashit` just to be safe.
	$item_url = trailingslashit( $item->url );
	$posts_page_url = trailingslashit( get_permalink( get_option( 'page_for_posts' ) ) );

	// Modify classes for Community Events page views.
	if ( tribe_is_community_edit_event_page() || tribe_is_community_my_events_page() ) {

		// Remove classes from the Posts page.
		if ( $item_url === $posts_page_url ) {
			$classes = array();
		}

		$add_event_page_url = trailingslashit( tribe_community_events_add_event_link() );

		// Add the `active` class to the add event page for "My Events" page views.
		if ( tribe_is_community_my_events_page() && $item_url === $add_event_page_url ) {
			$classes[] = 'active';
		}

		return $classes;
	}

	// Modify classes for individual people profile views.
	if ( is_singular( 'wsuwp_people_profile' ) ) {

		// Remove classes from the Posts page.
		if ( $item_url === $posts_page_url ) {
			$classes = array();
		}

		$object_id = get_post_meta( $item->ID, '_menu_item_object_id', true );
		$object_template = get_page_template_slug( $object_id );

		// Add the `active` class to the page using the directory template.
		if ( 'templates/people.php' === $object_template ) {
			$classes[] = 'active';
		}

		return $classes;
	}

	return $classes;
}

/**
 * Filters the title of People posts to include degrees.
 *
 * @since 0.2.10
 *
 * @param string $title The post title.
 * @param int    $id    The post ID.
 *
 * @return string
 */
function pharmacy_people_degrees( $title, $id = null ) {
	if ( ! in_the_loop() ) {
		return $title;
	}

	if ( ! is_singular( 'wsuwp_people_profile' ) || ! $id ) {
		return $title;
	}

	$nid = get_post_meta( $id, '_wsuwp_profile_ad_nid', true );
	$person = WSUWP_People_Post_Type::get_rest_data( $nid );
	$display = WSUWP_Person_Display::get_data( $person, array() );

	if ( $display['degrees'] && is_array( $display['degrees'] ) ) {
		foreach ( $display['degrees'] as $degree ) {
			$title .= '<span class="degree">, ' . esc_html( $degree ) . '</span>';
		}
	}

	return $title;
}
