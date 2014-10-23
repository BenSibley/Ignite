<?php

// takes user input from the customizer and outputs linked social media icons

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
		<a target="_blank" href="mailto:<?php echo antispambot( is_email( get_theme_mod( $active_site ) ) ); ?>">
		<?php else : ?>
		<a target="_blank" href="<?php echo esc_url(get_theme_mod( $active_site )); ?>">
	<?php endif; ?>

		<?php if( $active_site ==  "flickr" || $active_site ==  "dribbble" || $active_site ==  "instagram" || $active_site ==  "soundcloud" || $active_site ==  "spotify" || $active_site ==  "vine" || $active_site ==  "yahoo" || $active_site ==  "codepen" || $active_site ==  "delicious" || $active_site ==  "stumbleupon" || $active_site ==  "deviantart" || $active_site ==  "digg" || $active_site ==  "hacker-news" || $active_site == "vk" || $active_site == 'weibo' || $active_site == 'tencent-weibo') { ?>
			<i class="fa fa-<?php echo esc_attr($active_site); ?>"></i>
		<?php } elseif( $active_site == 'email' ) { ?>
			<i class="fa fa-envelope"></i>
		<?php } elseif( $active_site == 'academia' ) { ?>
			<i class="fa fa-graduation-cap"></i>
		<?php } else { ?>
			<i class="fa fa-<?php echo esc_attr($active_site); ?>-square"></i>
		<?php } ?>
		</a>
		</li><?php
	}
	echo "</ul>";
}