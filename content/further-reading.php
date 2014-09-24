<?php
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