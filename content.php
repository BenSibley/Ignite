<?php
if ( is_single() ) { ?>
	<div <?php post_class(); ?>>
		<?php ct_ignite_featured_image(); ?>
		<?php get_template_part( 'content/entry-meta-top' ); ?>
		<div class='entry-header'>
			<h1 class='entry-title'><?php the_title(); ?></h1>
		</div>
		<div class="entry-content">
			<article>
				<?php ct_ignite_output_last_updated_date(); ?>
				<?php the_content(); ?>
				<?php wp_link_pages( array(
					'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'ignite' ),
					'after'  => '</p>',
				) ); ?>
			</article>
		</div>
		<div class='entry-meta-bottom'>
			<?php get_template_part( 'content/further-reading' ); ?>
			<?php get_template_part( 'content/author-meta' ); ?>
			<?php get_template_part( 'content/category-links' ); ?>
			<?php get_template_part( 'content/tag-links' ); ?>
		</div>
	</div>
	<?php
} else { ?>
	<div <?php post_class(); ?>>
		<?php ct_ignite_featured_image(); ?>
		<?php get_template_part( 'content/entry-meta-top' ); ?>
		<div class='excerpt-header'>
			<h2 class='excerpt-title'>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
		</div>
		<div class='excerpt-content'>
			<article>
				<?php ct_ignite_excerpt(); ?>
			</article>
		</div>
		<?php get_template_part( 'content/category-links' ); ?>
		<?php get_template_part( 'content/tag-links' ); ?>
		<?php get_template_part( 'content/comment-count' ); ?>
	</div>
	<?php
}

