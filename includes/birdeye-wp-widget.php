<?php
class Birdeye_WP_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'Birdeye_WP_Widget',
			'description' => 'My Widget is awesome',
		);
		parent::__construct( 'Birdeye_WP_Widget', 'Birdeye Scrolling Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget

    if ( class_exists( 'Birdeye_Shortcodes' ) ){
			echo $args['before_widget'] . $args['before_title'] . $instance['title'] . $args['after_title'];

      Birdeye_Shortcodes::scrolling_widget( array() );

			echo $args['after_widget'];
    }
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin

    $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		$count = ! empty( $instance['count'] ) ? $instance['count'] : '10';
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_attr_e( 'Count:', 'text_domain' ); ?></label>
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>">
			<?php
			for($i = 0; $i < 10; $i++) {
				$value = $i + 1;
				$htmlAttr = ($value == $count) ? sprintf( 'value="%s" selected', $value ) : sprintf( 'value="%s"', $value ) ;

				printf( '<option %s>%s</option>', $htmlAttr, $value );
			}
			?>
		</select>
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved

		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}
