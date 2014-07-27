<div class='search-form-container'>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url( '/' )); ?>">
        <label class="screen-reader-text"><?php _e('Search for:', 'ignite'); ?></label>
        <input type="search" class="search-field" placeholder="<?php _e('Search...', 'ignite'); ?>" value="" name="s" title="<?php _e('Search for:', 'ignite'); ?>" />
        <input type="submit" class="search-submit" value='<?php _e('Go', 'ignite'); ?>' />
    </form>
</div>