<?php

add_action( 'init', 'pharmacy_register_footer_menu' );

/**
 * Registers the menu locations for the site footer.
 *
 * @since 0.0.1
 */
function pharmacy_register_footer_menu() {
	register_nav_menu( 'footer', 'Footer' );
}
