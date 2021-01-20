<?php

$date_display = get_theme_mod( 'ct_ignite_post_meta_date_settings' );
$author_display = get_theme_mod( 'ct_ignite_post_meta_author_settings' );

if ( ( $date_display != 'hide' ) || ( $author_display != 'hide' ) ) : ?>
	<div class="entry-meta-top">
		<?php
		if ( $date_display != 'hide' ) {
			echo esc_html_x( 'Published', 'Published DATE', 'ignite' ) . " " . get_the_date();
		}
		if ( $author_display != 'hide' ) {

			// Capitalize "By"
			if ( $date_display == 'hide' ) {
				echo " " . ucfirst( esc_html_x( 'by', 'Published by whom?', 'ignite' ) ) . " ";
				the_author_posts_link();
			} else {
				echo " " . esc_html_x( 'by', 'Published by whom?', 'ignite' ) . " ";
				the_author_posts_link();
			}
		}
		?>
	</div>
<?php endif;