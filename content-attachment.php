<div class='entry'>
    <div class='entry-meta-top'><?php echo get_the_date('F jS, Y', 'Published: '); ?></div>
	<div class='entry-header'>
        <h1 class='entry-title'><?php the_title(); ?></h1>
	</div>
    <div class="entry-content">
        <article>
            <?php 
                $image = get_the_image(array('size' => 'large', 'echo' => false));
                if (empty($image)) {
                        the_attachment_link();          
                } else {
                        echo $image;    
                }
            ?>
        </article>
    </div>
</div>