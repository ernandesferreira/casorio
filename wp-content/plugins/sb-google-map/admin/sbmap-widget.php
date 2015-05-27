<?php
/*	 SB MAP Widget Settings
 *   Version: 1.0
 *   Author: SB Themes
 *   Profile: http://codecanyon.net/user/sbthemes?ref=sbthemes
 */
// Creating the widget 
class sbmap_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'sbmap_widget',																					// Base ID of widget
			__('SB Google Map', 'sb_google_map'),															// Widget name will appear in UI
			array( 'description' => __( 'Display google maps on your web page.', 'sb_google_map' ), ) 		// Widget description
		);
	}
	
	// Creating widget front-end
	public function widget( $args, $instance ) {
		$title 			= apply_filters( 'widget_title', $instance['title'] );
		$google_map_id 	= apply_filters( 'widget_title', $instance['google_map_id'] );
		
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		
		// This is where you run the code and display the output
		if(trim($google_map_id) == '') {
			echo 'Please select map id from widget setting panel.';
		} else {
			echo do_shortcode('[SBMAP id="'.$google_map_id.'"]');
		}
		echo $args['after_widget'];
	}
			
	// Widget Backend 
	public function form( $instance ) {
		if (isset($instance['title'])) {
			$title = $instance[ 'title' ];
		} else {
			$title = __('', 'sb_google_map');
		}
		if (isset($instance['google_map_id'])) {
			$google_map_id = $instance['google_map_id'];
		} else {
			$google_map_id = '';
		}
		
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Select Google Map:' ); ?></label> 
			<?php
				global $wpdb;
				$shortcodes = $wpdb->get_results("select * from ".$wpdb->prefix."sb_google_map order by map_id desc");
			?>
			<select class="widefat" id="<?php echo $this->get_field_id( 'google_map_id' ); ?>" name="<?php echo $this->get_field_name( 'google_map_id' ); ?>">
				<option <?php selected($google_map_id, ''); ?> value="">Select Map ID</option>
				<?php  if($shortcodes) { ?>
					<?php foreach($shortcodes as $shortcode) { ?>
						<option <?php selected($google_map_id, $shortcode->map_id); ?> value="<?php echo $shortcode->map_id; ?>"><?php echo $shortcode->map_title; ?></option>
					<?php } ?>
				<?php } ?>
			</select>
		</p>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			$instance['google_map_id'] = ( ! empty( $new_instance['google_map_id'] ) ) ? strip_tags( $new_instance['google_map_id'] ) : '';
			return $instance;
	}
} // Class sbmap_widget ends here

// Register and load the widget
function sbmap_load_widget() {
	register_widget( 'sbmap_widget' );
}
add_action( 'widgets_init', 'sbmap_load_widget' );