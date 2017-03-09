<?php

$date_display = get_theme_mod( 'ct_ignite_post_meta_date_settings' );
$author_display = get_theme_mod( 'ct_ignite_post_meta_author_settings' );

if ( ( $date_display != 'hide' ) || ( $author_display != 'hide' ) ) : ?>
	<div class="entry-meta-top">
		<?php
		if ( $date_display != 'hide' ) {
			echo _x( 'Published', 'Published DATE', 'ignite' ) . " " . date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'r' ) ) );
		}
		if ( $author_display != 'hide' ) {

			// Capitalize "By"
			if ( $date_display == 'hide' ) {
				echo " " . ucfirst( _x( 'by', 'Published by whom?', 'ignite' ) ) . " ";
				the_author_posts_link();
			} else {
				echo " " . _x( 'by', 'Published by whom?', 'ignite' ) . " ";
				the_author_posts_link();
			}
		}
		?>
	</div>
<?php endif;