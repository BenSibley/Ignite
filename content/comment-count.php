<?php

if ( get_theme_mod( 'ct_ignite_post_meta_comments_settings' ) != 'show' ) {
	return;
}

if ( ! comments_open() && get_comments_number() < 1 ) {
	?>
	<p>
		<i class="fa fa-comment"></i>
		<?php comments_number( __( 'Comments closed', 'ignite' ), __( 'One Comment', 'ignite' ), __( '% Comments', 'ignite' ) ); ?>
	</p>
	<?php
} else {
	?>
	<p>
		<i class="fa fa-comment"></i>
		<a href="<?php comments_link(); ?>">
			<?php comments_number( __( 'Leave a Comment', 'ignite' ), __( 'One Comment', 'ignite' ), __( '% Comments', 'ignite' ) ); ?>
		</a>
	</p>
	<?php
}