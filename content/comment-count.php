<?php

/* comment count link in excerpts */

// if comments aren't open and there aren't any comments
if(!comments_open() && get_comments_number() < 1) {
	?>
	<p>
		<i class="fa fa-comment"></i>
		<?php comments_number( __( 'Comments closed', 'ignite' ), __( 'One Comment', 'ignite'), __( '% Comments', 'ignite' ) ); ?>
	</p>
	<?php
	// otherwise link to the comments and display the count
} else {
	?>
	<p>
		<i class="fa fa-comment"></i>
		<a href="<?php comments_link(); ?>">
			<?php comments_number( __( 'Leave a Comment', 'ignite' ), __( 'One Comment', 'ignite'), __( '% Comments', 'ignite' ) ); ?>
		</a>
	</p>
<?php
}