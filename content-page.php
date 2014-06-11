<div class='entry'>
    <?php ct_ignite_featured_image(); ?>
	<div class='entry-header'>
            <h1 class='entry-title'><?php the_title(); ?></h1>
    </div>
    <div class='entry-content'>
        <article>
            <?php the_content(); ?>
            <?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Pages:','ignite'), 'after' => '</p>', ) ); ?>
        </article>
    </div>
</div>