<div class='entry'>
    <div class='entry-meta-top'><?php the_date('F jS, Y', 'Published: '); ?></div>
	<div class='entry-header'>
        <h1 class='entry-title'><?php the_title(); ?></h1>
	</div>
    <div class="entry-content">
        <article>
            <?php
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
            $image = $image[0];
            echo "<img src='$image' />";
            ?>
        </article>
    </div>
</div>