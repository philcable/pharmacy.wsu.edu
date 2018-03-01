<?php get_header(); ?>

	<main id="wsuwp-main" class="spine-single-template">

		<?php get_template_part( 'parts/headers' ); ?>

		<section class="row side-right gutter pad-top">

			<header>
				<h1>We recently updated our website!</h1>
			</header>

			<div class="column one">

				<p>The webpage you are looking for now lives somewhere else. Please use the search feature or the left-hand side navigation to find the page you are looking for. Sorry for any inconvenience this may have caused.</p>

				<?php get_search_form(); ?>

			</div><!--/column-->

			<div class="column two"><!-- Intentionally left blank --></div>

		</section>

		<?php get_template_part( 'parts/footers' ); ?>

	</main>

<?php get_footer();
