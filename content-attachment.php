<div class='entry'>
	<div class='entry-meta-top'>
		<?php _e( 'Published', 'ignite' ); ?>
		<?php the_date(); ?>
	</div>
	<div class='entry-header'>
		<h1 class='entry-title'><?php the_title(); ?></h1>
	</div>
	<div class="entry-content">
		<article>
			<?php
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
			echo "<img src='$image[0]' />";
			?>
		</article>
		<nav class='further-reading'>
			<p class='prev'>
				<span><?php previous_image_link( false, __( 'Previous Image', 'ignite' ) ); ?></span>
			</p>

			<p class='next'>
				<span><?php next_image_link( false, __( 'Next Image', 'ignite' ) ); ?></span>
			</p>
		</nav>
	</div>
</div>