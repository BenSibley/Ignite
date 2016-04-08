<?php if ( is_active_sidebar( 'primary' ) ) : ?>
	<aside id="sidebar-primary-container" class="sidebar-primary-container">
		<h1 class="screen-reader-text">Primary Sidebar</h1>
		<div class="sidebar sidebar-primary" id="sidebar-primary" role="complementary">
			<?php dynamic_sidebar( 'primary' ); ?>
		</div>
	</aside>
<?php endif; ?>