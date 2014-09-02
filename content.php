<?php 

if( is_single() ) { ?>
    <div <?php post_class(); ?>>
        <?php ct_ignite_featured_image(); ?>
        <div class="entry-meta-top">
            <?php
            echo __('Published', 'ignite') . " " . date_i18n( get_option( 'date_format' ), strtotime( get_the_date('n/j/Y') ) ) . " " . _x('by', 'Published by whom?', 'ignite') . " ";
            the_author_posts_link();
            ?>
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
            <?php
            if(get_theme_mod('ct_ignite_author_meta_settings') != 'hide'){ ?>
                <div class="author-meta">
                    <?php ct_ignite_profile_image_output(); ?>
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
            <?php } ?>
            <?php
            if(get_theme_mod('ct_ignite_post_meta_categories_settings') != 'hide'){ ?>
                <div class="entry-categories"><?php ct_ignite_category_display(); ?></div><?php
            }
            if(get_theme_mod('ct_ignite_post_meta_tags_settings') != 'hide'){ ?>
                <div class="entry-tags"><?php ct_ignite_tags_display(); ?></div><?php
            }
            ?>
		</div>
    </div>
<?php 
} else { ?>
    <div <?php post_class(); ?>>
        <?php ct_ignite_featured_image(); ?>
        <div class="excerpt-meta-top">
            <?php
            echo __('Published', 'ignite') . " " . date_i18n( get_option( 'date_format' ), strtotime( get_the_date('n/j/Y') ) ) . " " . _x('by', 'Published by whom?', 'ignite') . " ";
            the_author_posts_link();
            ?>
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
        <?php
        if(get_theme_mod('ct_ignite_post_meta_categories_settings') != 'hide'){ ?>
            <div class="entry-categories"><?php ct_ignite_category_display(); ?></div><?php
        }
        if(get_theme_mod('ct_ignite_post_meta_tags_settings') != 'hide'){ ?>
            <div class="entry-tags"><?php ct_ignite_tags_display(); ?></div><?php
        }
        if(get_theme_mod('ct_ignite_post_meta_comments_settings') == 'show'){ ?>
            <div class="excerpt-comments"><?php ct_ignite_post_meta_comments(); ?></div><?php
        }
        ?>
    </div>
<?php 
}

