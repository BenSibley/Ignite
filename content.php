<?php 

if( is_home() ) { ?>
    <div <?php post_class(); ?>>
    	<?php ct_ignite_featured_image(); ?>
        <div class="excerpt-meta-top">
            Published <?php echo get_the_date('F jS, Y'); ?> by <?php the_author_posts_link(); ?>
        </div>
		<div class='excerpt-header'>
			<h1 class='excerpt-title'>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h1>
		</div>
		<div class='excerpt-content'>
			<article>
				<?php ct_ignite_excerpt(); ?>
			</article>
		</div>
        <div class="excerpt-categories"><?php ct_ignite_category_display(); ?></div>
        <div class="excerpt-tags"><?php ct_ignite_tags_display(); ?></div>
	</div>
<?php     
} elseif( is_single() ) { ?>
    <div <?php post_class(); ?>>
        <?php ct_ignite_featured_image(); ?>
        <div class="entry-meta-top">
            Published <?php echo get_the_date('F jS, Y'); ?> by <?php the_author_posts_link(); ?>
        </div>
		<div class='entry-header'>
			<h1 class='entry-title'><?php the_title(); ?></h1>
		</div>
		<div class="entry-content">
			<article>
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','ignite'), 'after' => '</p>', ) ); ?>
			</article>
		</div>
		<div class='entry-meta-bottom'>
			<?php ct_ignite_further_reading(); ?>
            <div class="author-meta">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 72 );?>
                <div class="name-container">
                    <h4>
                        <?php
                        if(get_the_author_meta('user_url')){
                            echo "<a href='" . get_the_author_meta('user_url') . "'>" . get_the_author() . "</a>";
                        } else {
                            the_author();
                        }
                        ?>
                    </h4>
                </div>
                <p><?php echo get_the_author_meta('description'); ?></p>
            </div>
			<div class="entry-categories"><?php ct_ignite_category_display(); ?></div>
			<div class="entry-tags"><?php ct_ignite_tags_display(); ?></div>
		</div>
    </div>
<?php 
} else { ?>
    <div <?php post_class(); ?>>
        <?php ct_ignite_featured_image(); ?>
        <div class="excerpt-meta-top">
            Published <?php echo get_the_date('F jS, Y'); ?> by <?php the_author_posts_link(); ?>
        </div>
        <div class='excerpt-header'>
            <h1 class='excerpt-title'>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h1>
        </div>
        <div class='excerpt-content'>
            <article>
                <?php ct_ignite_excerpt(); ?>
            </article>
        </div>
        <div class="excerpt-categories"><?php ct_ignite_category_display(); ?></div>
        <div class="excerpt-tags"><?php ct_ignite_tags_display(); ?></div>
    </div>
<?php 
}

