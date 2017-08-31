<footer class="main-footer">
 <header><?php echo get_bloginfo( 'name' ); ?></header>
 <nav class="footer-nav">
	 <?php
	 	$spine_site_args = array(
	 		'theme_location'  => 'site',
	 		'menu'            => 'footer',
	 		'container'       => false,
	 		'container_class' => false,
	 		'container_id'    => false,
	 		'menu_class'      => null,
	 		'menu_id'         => null,
	 		'items_wrap'      => '<ul>%3$s</ul>',
	 		'depth'           => 6,
	 	);
	 	wp_nav_menu( $spine_site_args ); ?>
	</nav>
  <div class="social">
  </div>
</footer>
