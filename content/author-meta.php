<?php
if ( get_theme_mod( 'ct_ignite_author_meta_settings' ) == 'hide' ) {
	return;
}
?>
<div class="author-meta">
	<?php echo get_avatar( get_the_author_meta( 'ID' ), 72, '', get_the_author() ); ?>
	<div class="name-container">
		<h4>
			<?php
			if ( get_the_author_meta( 'user_url' ) ) {
				echo "<a href='" . esc_url( get_the_author_meta( 'user_url' ) ) . "'>" . esc_html( get_the_author() ) . "</a>";
			} else {
				the_author();
			}
			?>
		</h4>
	</div>
	<p>
		<?php echo esc_html( get_the_author_meta( 'description' ) ); ?>
	</p>
</div>