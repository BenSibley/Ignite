<?php

class ct_ignite_Image extends WP_Widget {

	function __construct() {

		$widget_options = array(
			'classname'   => 'widget_ct_ignite_image',
			'description' => sprintf( __( 'This %s widget displays an image.', 'ignite' ), wp_get_theme( get_template() ) )
		);
		parent::__construct(
			'ct_ignite_image',
			esc_html__( 'Image', 'ignite' ),
			$widget_options
		);
	}

	public function widget( $args, $instance ) {

		$html = $args['before_widget'];
		if ( $instance['link'] ) {
			$html .= "<a href='" . $instance['link'] . "'>";
		}
		if ( $instance['image'] ) {
			$html .= "<img title='" . $instance['title'] . "' alt='" . $instance['alt-text'] . "' src='" . $instance['image'] . "' />";
		}
		if ( $instance['link'] ) {
			$html .= "</a>";
		}
		$html .= $args['after_widget'];

		echo $html;
	}

	public function form( $instance ) {

		$image    = isset( $instance['image'] ) ? $instance['image'] : 'http://';
		$title    = isset( $instance['title'] ) ? $instance['title'] : '';
		$alt_text = isset( $instance['alt-text'] ) ? $instance['alt-text'] : '';
		$link     = isset( $instance['link'] ) ? $instance['link'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image URL:', 'ignite' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>"
			       name="<?php echo $this->get_field_name( 'image' ); ?>" type="text"
			       value="<?php echo esc_url( $image ); ?>">
			<input type='button' class="image-upload button-primary" value="<?php _e( 'Upload Image', 'ignite' ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'ignite' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'alt-text' ); ?>"><?php _e( 'Alternate text', 'ignite' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'alt-text' ); ?>"
			       name="<?php echo $this->get_field_name( 'alt-text' ); ?>" type="text"
			       value="<?php echo esc_attr( $alt_text ); ?>">
		</p>
		<p>
			<label
				for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link (optional)', 'ignite' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>"
			       name="<?php echo $this->get_field_name( 'link' ); ?>" type="text"
			       value="<?php echo esc_url( $link ); ?>">
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {

		$instance             = array();
		$instance['image']    = ( ! empty( $new_instance['image'] ) ) ? esc_url_raw( $new_instance['image'] ) : '';
		$instance['title']    = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['alt-text'] = ( ! empty( $new_instance['alt-text'] ) ) ? strip_tags( $new_instance['alt-text'] ) : '';
		$instance['link']     = ( ! empty( $new_instance['link'] ) ) ? esc_url_raw( $new_instance['link'] ) : '';

		return $instance;
	}
}

function register_ct_ignite_image_widget() {
	register_widget( 'ct_ignite_image' );
}
add_action( 'widgets_init', 'register_ct_ignite_image_widget' );