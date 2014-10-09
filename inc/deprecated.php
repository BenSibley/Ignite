<?php

/* Avoid use of these functions in favor of newer functions */

/*
 * @deprecated 1.36
 * Now template part /content/further-reading.php
 */
function ct_ignite_further_reading() {

	global $post;

	// gets the next & previous posts if they exist
	$previous_blog_post = get_adjacent_post(false,'',true);
	$next_blog_post = get_adjacent_post(false,'',false);

	if(get_the_title($previous_blog_post)) {
		$previous_title = get_the_title($previous_blog_post);
	} else {
		$previous_title = __("The Previous Post", 'ignite');
	}
	if(get_the_title($next_blog_post)) {
		$next_title = get_the_title($next_blog_post);
	} else {
		$next_title = __("The Next Post", 'ignite');
	}

	echo "<nav class='further-reading'>";
	if($previous_blog_post) {
		echo "<p class='prev'>
        		<span>" . __('Previous Post', 'ignite') . "</span>
        		<a href='".get_permalink($previous_blog_post)."'>".$previous_title."</a>
	        </p>";
	} else {
		echo "<p class='prev'>
                <span>" . __('Return to Blog', 'ignite') . "</span>
        		<a href='".esc_url(home_url())."'>" . __('This is the oldest post', 'ignite') . "</a>
        	</p>";
	}
	if($next_blog_post) {

		echo "<p class='next'>
        		<span>" . __('Next Post', 'ignite') . "</span>
        		<a href='".get_permalink($next_blog_post)."'>".$next_title."</a>
	        </p>";
	} else {
		echo "<p class='next'>
                <span>" . __('Return to Blog', 'ignite') . "</span>
        		<a href='".esc_url(home_url())."'>" . __('This is the newest post', 'ignite') . "</a>
        	 </p>";
	}
	echo "</nav>";
}

/*
 * @deprecated 1.36
 * Now template part /content/category-links.php
 */
function ct_ignite_category_display() {

	$categories = get_the_category();
	$separator = ' ';
	$output = '';
	if($categories){
		echo "<p><i class='fa fa-folder-open'></i>";
		foreach($categories as $category) {
			$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", 'ignite' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
		}
		echo trim($output, $separator);
		echo "</p>";
	}
}

/*
 * @deprecated 1.36
 * Now template part /content/tag-links.php
 */
function ct_ignite_tags_display() {

	$tags = get_the_tags();
	$separator = ' ';
	$output = '';
	if($tags){
		echo "<p><i class='fa fa-tag'></i>";
		foreach($tags as $tag) {
			$output .= '<a href="'.get_tag_link( $tag->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts tagged %s", 'ignite' ), $tag->name ) ) . '">'.$tag->name.'</a>'.$separator;
		}
		echo trim($output, $separator);
		echo "</p>";
	}
}

/*
 * @deprecated 1.36
 * Now template part /content/comment-count.php
 */
function ct_ignite_post_meta_comments(){

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
}

/*
 * @deprecated 1.38
 * Now template part /content/social-icons.php
 */
function ct_ignite_social_media_icons() {

	$social_sites = ct_ignite_customizer_social_media_array();

	// any inputs that aren't empty are stored in $active_sites array
	foreach($social_sites as $social_site) {
		if( strlen( get_theme_mod( $social_site ) ) > 0 ) {
			$active_sites[] = $social_site;
		}
	}

	// for each active social site, add it as a list item
	if(!empty($active_sites)) {
		echo "<ul class='social-media-icons'>";
		foreach ($active_sites as $active_site) {?>
			<li>
			<?php if( $active_site == 'email' ) : ?>
			<a target="_blank" href="mailto:<?php echo is_email( get_theme_mod( $active_site ) ); ?>">
			<?php else : ?>
			<a target="_blank" href="<?php echo esc_url(get_theme_mod( $active_site )); ?>">
		<?php endif; ?>

			<?php if( $active_site ==  "flickr" || $active_site ==  "dribbble" || $active_site ==  "instagram" || $active_site ==  "soundcloud" || $active_site ==  "spotify" || $active_site ==  "vine" || $active_site ==  "yahoo" || $active_site ==  "codepen" || $active_site ==  "delicious" || $active_site ==  "stumbleupon" || $active_site ==  "deviantart" || $active_site ==  "digg" || $active_site ==  "hacker-news" || $active_site == "vk" || $active_site == 'weibo' || $active_site == 'tencent-weibo') { ?>
				<i class="fa fa-<?php echo $active_site; ?>"></i>
			<?php } elseif( $active_site == 'email' ) { ?>
				<i class="fa fa-envelope"></i>
			<?php } elseif( $active_site == 'academia' ) { ?>
				<i class="fa fa-graduation-cap"></i>
			<?php } else { ?>
				<i class="fa fa-<?php echo $active_site; ?>-square"></i>
			<?php } ?>
			</a>
			</li><?php
		}
		echo "</ul>";
	}
}