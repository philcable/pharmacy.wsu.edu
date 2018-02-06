<?php

namespace WSU\Pharmacy\Content_Syndicate;

add_filter( 'wsuwp_content_syndicate_json_output', 'WSU\Pharmacy\Content_Syndicate\wsuwp_json_output', 10, 3 );
add_filter( 'wsuwp_people_item_html', 'WSU\Pharmacy\Content_Syndicate\people_html', 10, 2 );

/**
 * Provide fallback URLs if thumbnail sizes have not been generated
 * for a post pulled in with content syndicate.
 *
 * @since 0.0.2
 *
 * @param \stdClass $content
 *
 * @return string
 */
function get_image_url( $content ) {
	// If no embedded featured media exists, use the full thumbnail.
	if ( ! isset( $content->featured_media )
		|| ! isset( $content->featured_media->media_details )
		|| ! isset( $content->featured_media->media_details->sizes ) ) {
		return $content->thumbnail;
	}

	$sizes = $content->featured_media->media_details->sizes;

	if ( isset( $sizes->{'spine-medium_size'} ) ) {
		return $sizes->{'spine-medium_size'}->source_url;
	}

	if ( isset( $sizes->{'spine-small_size'} ) ) {
		return $sizes->{'spine-small_size'}->source_url;
	}

	if ( isset( $sizes->{'full'} ) ) {
		return $sizes->{'full'}->source_url;
	}

	return $content->thumbnail;
}

/**
 * Provide custom output for the wsuwp_json shortcode.
 *
 * @since 0.0.2
 *
 * @param string $content
 * @param array  $data
 * @param array  $atts
 *
 * @return string
 */
function wsuwp_json_output( $content, $data, $atts ) {
	// Provide a default output for cases where no `output` attribute is included.
	if ( 'json' === $atts['output'] ) {
		ob_start();
		?>
		<div class="deck">
		<?php
		$offset_x = 0;
		foreach ( $data as $content ) {
			if ( $offset_x < absint( $atts['offset'] ) ) {
				$offset_x++;
				continue;
			}

			?>
			<article class="card">

				<?php if ( ! empty( $content->thumbnail ) ) { ?>
				<?php $image_url = get_image_url( $content ); ?>
				<figure class="card-image"
						style="background-image: url(<?php echo esc_url( $image_url ); ?>">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $content->featured_media->alt_text ); ?>">
				</figure>
				<?php } ?>

				<div class="card-content">

					<header class="card-title">
						<a href="<?php echo esc_url( $content->link ); ?>"><?php echo esc_html( $content->title ); ?></a>
					</header>

					<div class="card-excerpt">
						<?php echo wp_kses_post( $content->excerpt ); ?>
					</div>

					<div class="card-cta">
						<a href="<?php echo esc_url( $content->link ); ?>">Read more</a>
					</div>

				</div>

			</article>
			<?php
		}
		?>
		</div>
		<?php
		$content = ob_get_clean();
	}

	return $content;
}

/**
 * Provide a custom HTML template for use with syndicated people.
 *
 * @param string   $html   The HTML to output for an individual person.
 * @param stdClass $person Object representing a person received from people.wsu.edu.
 *
 * @return string The HTML to output for a person.
 */
function people_html( $html, $person ) {
	// Cast the photo collection as an array to account for cases
	// where it can sometimes come through as an object.
	$photo_collection = (array) $person->photos;
	$photo = false;

	// Get the URL of the display photo.
	if ( ! empty( $photo_collection ) ) {
		if ( ! empty( $person->display_photo ) && isset( $photo_collection[ $person->display_photo ] ) ) {
			$photo = $photo_collection[ $person->display_photo ]->thumbnail;
		} elseif ( isset( $photo_collection[0] ) ) {
			$photo = $photo_collection[0]->thumbnail;
		}
	}

	// Get the display title(s).
	if ( ! empty( $person->working_titles ) ) {
		if ( ! empty( $person->display_title ) ) {
			$display_titles = explode( ',', $person->display_title );
			foreach ( $display_titles as $display_title ) {
				if ( isset( $person->working_titles[ $display_title ] ) ) {
					$titles[] = $person->working_titles[ $display_title ];
				}
			}
		} else {
			$titles = $person->working_titles;
		}
	} else {
		$titles = array( $person->position_title );
	}

	$office = ( ! empty( $person->office_alt ) ) ? $person->office_alt : $person->office;
	$address = ( ! empty( $person->address_alt ) ) ? $person->address_alt : $person->address;
	$email = ( ! empty( $person->email_alt ) ) ? $person->email_alt : $person->email;
	$phone = ( ! empty( $person->phone_alt ) ) ? $person->phone_alt : $person->phone;

	$link = ( '' !== $person->content->rendered ) ? $person->link : false;

	ob_start();
	?>
	<article class="person-card">

		<header class="person-card-name">
			<?php if ( $link ) { ?>
			<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $person->title->rendered ); ?></a>
			<?php } else { ?>
				<?php echo esc_html( $person->title->rendered ); ?>
			<?php } ?>
		</header>

		<?php if ( $photo ) { ?>
		<figure class="person-card-photo">
			<?php if ( $link ) { ?>
			<a href="<?php echo esc_url( $link ); ?>"><img src="<?php echo esc_url( $photo ); ?>" alt="" /></a>
			<?php } else { ?>
				<img src="<?php echo esc_url( $photo ); ?>" alt="" />
			<?php } ?>
		</figure>
		<?php } ?>

		<div class="person-card-contact">

			<?php foreach ( $titles as $title ) { ?>
			<div class="person-card-title"><?php echo esc_html( $title ); ?></div>
			<?php } ?>

			<?php if ( $email ) { ?>
			<div class="person-card-email">
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			</div>
			<?php } ?>

			<?php if ( $phone ) { ?>
			<div class="person-card-phone"><?php echo esc_html( $phone ); ?></div>
			<?php } ?>

			<?php if ( $office ) { ?>
			<div class="person-card-office"><?php echo esc_html( $office ); ?></div>
			<?php } ?>

			<?php if ( $address ) { ?>
			<div class="person-card-address"><?php echo esc_html( $address ); ?></div>
			<?php } ?>

			<?php if ( $person->website ) { ?>
			<div class="person-card-website">
				<a href="<?php echo esc_url( $person->website ); ?>"><?php echo esc_url( $person->website ); ?></a>
			</div>
			<?php } ?>

		</div>

	</article>
	<?php
	$html = ob_get_clean();

	return $html;
}
