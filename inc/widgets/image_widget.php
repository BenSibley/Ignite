<?php
/**
 * Adds Recent Comments widget.
 */
class ct_ignite_Image extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname'   => 'widget_ct_ignite_image', // Classes
			'description' => __( 'This Ignite widget displays an image.', 'ignite' ) // Args
		);

		/* Create the widget. */
		$this->WP_Widget(
			'ct_ignite_image', // Base ID
			__('Image','ignite'), // Name
			$widget_options
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		/* the sidebar's $before_widget wrapper. */
		$html =  $args['before_widget'];

		/* If there is a link, link image */
		if ($instance['link']) {
			$html .= "<a href='" . $instance['link'] . "'>";
		}

		/* If an image was uploaded by the user, display it. */
		if ($instance['image']) {
			$html .= "<img title='" . $instance['title'] . "' alt='" . $instance['alt-text'] . "' src='". $instance['image'] ."' />";
		}

		/* If there is a link, close it */
		if ($instance['link']) {
			$html .= "</a>";
		}

		/* close the widget </section> */
		$html .=  $args['after_widget'];

		echo $html;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		/* Image */
		if ( isset( $instance[ 'image' ] ) ) {
			$image = $instance[ 'image' ];
		} else {
			$image = __( 'http://', 'ignite' );
		}

		/* Title */
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( '', 'ignite' );
		}

		/* Alt text */
		if ( isset( $instance[ 'alt-text' ] ) ) {
			$alt_text = $instance[ 'alt-text' ];
		} else {
			$alt_text = __( '', 'ignite' );
		}

		/* Link */
		if ( isset( $instance[ 'link' ] ) ) {
			$link = $instance[ 'link' ];
		} else {
			$link = __( '', 'ignite' );
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image URL:','ignite' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo esc_url( $image ); ?>">
			<input type='button' class="image-upload button-primary" value="<?php _e( 'Upload Image', 'ignite' ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','ignite' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'alt-text' ); ?>"><?php _e( 'Alternate text','ignite' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'alt-text' ); ?>" name="<?php echo $this->get_field_name( 'alt-text' ); ?>" type="text" value="<?php echo esc_attr( $alt_text ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link (optional)','ignite' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_url( $link ); ?>">
		</p>

	<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		/* image */
		$instance['image'] = ( ! empty( $new_instance['image'] ) ) ? esc_url_raw( $new_instance['image'] ) : '';

		/* title */
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		/* alt text */
		$instance['alt-text'] = ( ! empty( $new_instance['alt-text'] ) ) ? strip_tags( $new_instance['alt-text'] ) : '';

		/* link */
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? esc_url_raw( $new_instance['link'] ) : '';

		return $instance;
	}

}

// register widget
function register_ct_ignite_image_widget() {
	register_widget( 'ct_ignite_image' );
}
add_action( 'widgets_init', 'register_ct_ignite_image_widget' );