<?php if ( is_active_sidebar( 'primary' ) ) : ?>
	<div id="sidebar-primary-container" class="sidebar-primary-container">
		<div class="sidebar sidebar-primary" id="sidebar-primary" role="complementary">
			<?php dynamic_sidebar( 'primary' ); ?>
		</div>
	</div>
<?php endif; ?>