<?php get_header(); ?>
   
<?php

// The loop
if ( have_posts() ) :
    while (have_posts() ) : 
        the_post();
        get_template_part( 'content' );
        comments_template();
    endwhile;
endif;
?>

<?php get_footer(); ?>