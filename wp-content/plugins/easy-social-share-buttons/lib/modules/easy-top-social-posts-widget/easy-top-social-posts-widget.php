<?php

class EasyTopSocialPostsWidget extends WP_Widget {
	public function form($instance) {
		// define all variables of the widget, and also set default values
		if (isset ( $instance ['category'] )) {
			$category = $instance ['category'];
		} else {
			$category = __ ( 'Featured', ESSB_TEXT_DOMAIN);
		}
		if (isset ( $instance ['title'] )) {
			$title = $instance ['title'];
		} else {
			$title = __ ( 'Top Social Posts', ESSB_TEXT_DOMAIN);
		}
		
		if (isset ( $instance ['show_post_limit'] )) {
			$show_post_limit = $instance ['show_post_limit'];
		} else {
			$show_post_limit = __ ( '5', ESSB_TEXT_DOMAIN);
		}
		if (isset ( $instance ['order_post'] )) {
			$order_post = $instance ['order_post'];
		} else {
			$order_post = __ ( 'DESC', ESSB_TEXT_DOMAIN);
		}
		
		if (isset ( $instance ['link_title_to_cat'] )) {
			$link_title_to_archive = $instance ['link_title_to_cat'];
		} else {
			$link_title_to_archive = __ ( 'checked', ESSB_TEXT_DOMAIN);
		}
		if (isset ( $instance ['show_post_title'] )) {
			$show_post_title = $instance ['show_post_title'];
		} else {
			$show_post_title = __ ( 'checked', ESSB_TEXT_DOMAIN);
		}
		
		if (isset ( $instance ['show_featured_image'] )) {
			$show_featured_image = $instance ['show_featured_image'];
		} else {
			$show_featured_image = __ ( 'checked', ESSB_TEXT_DOMAIN);
		}
		if (isset ( $instance ['image_align'] )) {
			$image_align = $instance ['image_align'];
		} else {
			$image_align = __ ( 'center', ESSB_TEXT_DOMAIN);
		}
		if (isset ( $instance ['image_size'] )) {
			$image_size = $instance ['image_size'];
		} else {
			$image_size = __ ( 'medium', ESSB_TEXT_DOMAIN);
		}
		if (isset ( $instance ['image_width'] )) {
			$image_width = $instance ['image_width'];
		} else {
			$image_width = __ ( '', ESSB_TEXT_DOMAIN);
		}
		if (isset ( $instance ['image_height'] )) {
			$image_height = $instance ['image_height'];
		} else {
			$image_height = __ ( '', ESSB_TEXT_DOMAIN);
		}
		
		if (isset ( $instance ['post_type'] )) {
			$post_type = $instance ['post_type'];
		} else {
			$post_type = __ ( 'post', ESSB_TEXT_DOMAIN);
		}
		if (isset ( $instance ['custom_post_type'] )) {
			$custom_post_type = $instance ['custom_post_type'];
		} else {
			$custom_post_type = __ ( '', ESSB_TEXT_DOMAIN);
		}
		
		if (isset ( $instance ['show_excerpt'] )) {
			$show_excerpt = $instance ['show_excerpt'];
		} else {
			$show_excerpt = __ ( 'checked', ESSB_TEXT_DOMAIN);
		}
		
		$post_type_id = $this->get_field_id ( 'post_type' );
		?>
<p>
 <label class="small"><strong>This widget requires Easy Social Metrics Lite to be active to work.</strong></label>
</p>
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title :' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'title' ); ?>"
		name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
		value="<?php echo esc_attr( $title ); ?>" class="widefat" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"> <?php _e( 'Post Type: (Any Post Types is equal to post types that Easy Social Metrics Lite monitor' ); ?> </label>
	<select id="<?php echo $this->get_field_id( 'post_type' ); ?>"
		name="<?php echo $this->get_field_name( 'post_type' ); ?>"
		onchange="showOps(this)" class="post_type_option widefat"
>
		<option value="post"
			<?php if ( $post_type == "post" ) { echo 'selected="selected"'; } ?>>Post</option>
		<option value="any"
			<?php if ( $post_type == "any" ) { echo 'selected="selected"'; } ?>>Any Post Type</option>
			<option value="custom"
			<?php if ( $post_type == "custom" ) { echo 'selected="selected"'; } ?>>Custom
			Post Type</option>
	</select>
</p>
<p id="<?php echo $this->get_field_id( 'post_type' ); ?>_id"
	<?php if ( $post_type != "custom") { echo 'style="display:none;"'; } ?>
	class="hidden_options">
	<label for="<?php echo $this->get_field_id( 'custom_post_type' ); ?>"><?php _e( 'Custom Post Type:' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'custom_post_type' ); ?>"
		name="<?php echo $this->get_field_name( 'custom_post_type' ); ?>"
		type="text" value="<?php echo esc_attr( $custom_post_type); ?>"
		class="widefat" />
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'show_post_limit' ); ?>"><?php _e( 'Show How Many Posts:' ); ?></label>
	<input
		id="<?php echo $this->get_field_id( 'show_post_limit' ); ?>"
		name="<?php echo $this->get_field_name( 'show_post_limit' ); ?>"
		type="text" value="<?php echo esc_attr( $show_post_limit ); ?>"
		maxlength="2" style="text-align: center; " size="5" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('order_post'); ?>"><?php _e('Order Posts:'); ?></label>
	<select id="<?php echo $this->get_field_id( 'order_post' ); ?>"
		name="<?php echo $this->get_field_name( 'order_post' ); ?>"
		class="widefat">
		
		<option value="DESC"
			<?php if ( $order_post == "DESC" ) { echo 'selected="selected"'; } ?>>Top posts</option>
		<option value="rand"
			<?php if ( $order_post == "rand" ) { echo 'selected="selected"'; } ?>>Random posts</option>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Category :' ); ?></label>
	<select id="<?php echo $this->get_field_id( 'category' ); ?>"
		name="<?php echo $this->get_field_name( 'category' ); ?>"
		class="widefat">
		<option value="none">All</option>
					<?php
		$categories_list = get_categories ();
		foreach ( $categories_list as $list_category ) {
			
			if ($list_category->cat_ID == $category) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			
			echo '<option value="' . $list_category->cat_ID . '" ' . selected ( $list_category->cat_ID, $instance ['category'] ) . '>' . $list_category->cat_name . '</option>';
		}
		?>
				</select>
</p>

<p >
	<label for="<?php echo $this->get_field_id( 'show_post_title' ); ?>"><?php _e( 'Show The Post\'s Title :' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'show_post_title' ); ?>"
		name="<?php echo $this->get_field_name( 'show_post_title' ); ?>"
		type="checkbox" value="<?php echo esc_attr( $show_post_title ); ?>"
		<?php checked( (bool) $show_post_title, true ); ?>
		onchange="showLinktitle(this)" />
</p>

<p id="<?php echo $this->get_field_id( 'show_post_title' ); ?>_id"  style="<?php if ( $show_post_title == false ) { echo 'display:none;'; } ?>" class="hidden_options">
	<label for="<?php echo $this->get_field_id( 'link_title_to_cat' ); ?>"><?php _e( 'Make Widget Title A Link? :' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'link_title_to_cat' ); ?>"
		name="<?php echo $this->get_field_name( 'link_title_to_cat' ); ?>"
		type="checkbox"
		value="<?php echo esc_attr( $link_title_to_archive ); ?>"
		<?php checked( (bool) $link_title_to_archive, true ); ?> />
</p>

<p>
	<label
		for="<?php echo $this->get_field_id( 'show_featured_image' ); ?>"><?php _e( 'Show Featured Image :' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'show_featured_image' ); ?>"
		name="<?php echo $this->get_field_name( 'show_featured_image' ); ?>"
		type="checkbox"
		value="<?php echo esc_attr( $show_featured_image ); ?>"
		<?php checked( (bool) $show_featured_image, true ); ?>
		onchange="showFeaturedImageOps(this)" />
</p>

<p
	<?php if ( $show_featured_image == false ) { echo 'style="display:none;"'; } ?>
	class="hidden_options">
	<label for="<?php echo $this->get_field_id( 'image_align' ); ?>"><?php _e( 'Align Image :' ); ?></label>
	<select id="<?php echo $this->get_field_id( 'image_align' ); ?>"
		name="<?php echo $this->get_field_name( 'image_align' ); ?>"
		style="width: 150px;">
		<option value="left"
			<?php if ( $image_align == "left" ) { echo 'selected="selected"'; } ?>
			style="text-align: left;">Left</option>
		<option value="center"
			<?php if ( $image_align == "center" ) { echo 'selected="selected"'; }  ?>
			style="text-align: center;">Center</option>
		<option value="right"
			<?php if ( $image_align == "right" ) { echo 'selected="selected"'; }  ?>
			style="text-align: right;">Right</option>
	</select>
</p>

<p
	<?php if ( $show_featured_image == false ) { echo 'style="display:none;"'; } ?>
	class="hidden_options">
	<label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size :' ); ?></label>
	<select id="<?php echo $this->get_field_id( 'image_size' ); ?>"
		name="<?php echo $this->get_field_name( 'image_size' ); ?>"
		class="image_size_options" onchange="showOps(this)"
		style="width: 154px;">
		<option value="custom"
			<?php if ( $image_size == "custom" ) { echo 'selected="selected"'; } ?>>Custom</option>
		<option value="thumbnail"
			<?php if ( $image_size == "thumbnail" ) { echo 'selected="selected"'; } ?>>Thumbnail</option>
		<option value="medium"
			<?php if ( $image_size == "medium" ) { echo 'selected="selected"'; } ?>>Medium</option>
		<option value="large"
			<?php if ( $image_size == "large" ) { echo 'selected="selected"'; } ?>>Large</option>
		<option value="full"
			<?php if ( $image_size == "full" ) { echo 'selected="selected"'; } ?>>Full</option>
	</select>
</p>

<p id="<?php echo $this->get_field_id( 'image_size' ); ?>_id"
	<?php if ( $image_size != "custom") { echo 'style="display:none;"'; } ?>
	class="hidden_options">
	<label for="<?php echo $this->get_field_id( 'image_width' ); ?>"><?php _e( 'Width :' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'image_width' ); ?>"
		name="<?php echo $this->get_field_name( 'image_width' ); ?>"
		type="text" value="<?php echo esc_attr( $image_width ); ?>"
		<?php checked( (bool) $image_width, true ); ?>
		style="width: 65px; text-align: center;" /> <label
		for="<?php echo $this->get_field_id( 'image_height' ); ?>"><?php _e( 'Height :' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'image_height' ); ?>"
		name="<?php echo $this->get_field_name( 'image_height' ); ?>"
		type="text" value="<?php echo esc_attr( $image_height ); ?>"
		<?php checked( (bool) $image_height, true ); ?>
		style="width: 69px; text-align: center;" />
</p>


<p>
	<label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>"><?php _e( 'Show Text Excerpt? :' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>"
		name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>"
		type="checkbox" value="<?php echo esc_attr( $show_excerpt ); ?>"
		<?php checked( (bool) $show_excerpt, true ); ?> />
</p>

<?php
	
	}
	
	public function widget($args, $instance) {
		// let's save the post data for after the widget.
		global $post;
		$post_old = $post;
		
		extract ( $args ); // grabbing all the args for the widget
		                  
		// setting all the args for the widget
		$title = apply_filters ( 'widget_title', $instance ['title'] );
		
		// I hate using other people's websites when you can't select to display
		// posts from ALL categories... so this is my way of making the world a
		// better place :)
		if ($instance ['category'] != "none") {
			$category_id = $instance ['category']; // added in 1.8, thanks for the
			                                      // positive feed back!
			$category = 'cat=' . $instance ['category'] . '&';
		} else {
			$category = '';
			$category_id = $instance ['custom_post_type'];
		}
		
		$post_type = $instance ['post_type'];
		$custom_post_type = $instance ['custom_post_type'];
		$order_post = $instance ['order_post'];
		$link_title_to_archive = $instance ['link_title_to_cat'];
		$show_post_title = $instance ['show_post_title'];
		$show_post_limit = $instance ['show_post_limit'];
		$show_featured_image = $instance ['show_featured_image'];

		$image_size = $instance ['image_size'];
		$image_align = $instance ['image_align'];
		$show_excerpt = $instance ['show_excerpt'];
		
		// build the size variable to use with wp_query
		if ($image_size == "custom") {
			$image_width = $instance ['image_width'];
			$image_height = $instance ['image_height'];
			$size = array ($image_width, $image_height );
		} else {
			$size = $image_size;
		}
		
		// start the widget
		echo $before_widget;
		
		// for 1.8 we need to update the widget title, to allow for more
		// flexible linking
		
		// Let's build the title of the widget
		if ($link_title_to_archive) {
			if ($post_type == 'custom') {
				$custom_post_link = get_post_type_archive_link ( $custom_post_type );
				echo $before_title . '<a href="' . $custom_post_link . '">' . $title . '</a>' . $after_title;
				$category = '';
			} else {
				echo $before_title . '<a href="' . get_category_link ( $category_id ) . '">' . $title . '</a>' . $after_title;
			}
		} else {
			echo $before_title . $title . $after_title;
		}
		
		// some post type checks real quick
		if ($post_type == "custom") {
			$post_type = 'post_type=='.$custom_post_type;
		}
		else if ($post_type == "any") {
			$list = $this->get_post_types();
			$post_type = "";
			foreach ($list as $pt) {
				if ($post_type != '') { $post_type .= '&'; }
				$post_type .= 'post_type[]='.$pt;
			}
		}
		else {
			$post_type = 'post_type='.$post_type;
		}
		
		echo "<ul>"; // thanks Dan ;)
		             
		// we need to figure out what argument to use, order or orderby
		             // depending on what they select.
		if ($order_post == 'rand') {
			// in order to sort randomly, we need to use orderby instead of
			// order, added july 9th 2013 at 2:04am ;)
			$order_orderby = 'orderby=' . $order_post;
		} else {
			$order_orderby = 'orderby=meta_value&order=' . $order_post.'&meta_key=esml_socialcount_total';
			//$order_orderby = 'order=' . $order_post;
		}
		// i'm going to forget to delete this before the release, watch...
		// echo
		// $category.'showposts='.$show_post_limit.'&'.$order_orderby.'&post_type='.$post_type;
		
		// this is where all the magic happens.
		$featured_posts_query = new WP_Query ( $category . 'showposts=' . $show_post_limit . '&' . $order_orderby . '&' . $post_type ); // get
		                                                                                                                               // new
		                                                                                                                               // post
		                                                                                                                               // data
		
		while ( $featured_posts_query->have_posts () ) :
			$featured_posts_query->the_post ();
			$image_title = get_the_title (); // The alt text for the featured image
			?>
<li><a href="<?php the_permalink(); ?>">
							<?php
			if ($show_post_title) 			// To show the title of the post, or not...
			{
				the_title ();
			}
			if ($show_featured_image) {
				if (has_post_thumbnail ()) {
					echo '<br />';
					the_post_thumbnail ( $size, array ('class' => 'essb_top_post_image align' . $image_align . ' ', 'title' => $image_title ) );
				}
			}
			?>
							<br style="clear: both;" />
</a>
						<?php if ( $show_excerpt ) { the_excerpt(); } ?>
					</li>
<?php
		endwhile
		;
		echo "</ul>";

				echo $after_widget; // end widget
		$post = $post_old; // finally, restoring the original post data, as if we
		                   // never even touched it ;)
	}
	
	// PARTYS OVER GUYS no more fun stuff
	public function __construct() {
		parent::__construct ( 'essb_top_social_posts', 		// Base ID
		'Easy Social Share Buttons: Top Social Posts', 		// Name
		array ('description' => __ ( 'Display the most popular posts in social networks', ESSB_TEXT_DOMAIN ) ) )		// Args
		;
	}
	
	public function get_post_types() {
	
		$types_to_track = array();
	
		$pts = get_post_types ( array ('public' => true, 'show_ui' => true, '_builtin' => true ) );
		$cpts = get_post_types ( array ('public' => true, 'show_ui' => true, '_builtin' => false ) );
		$essb_options = EasySocialShareButtons_Options::get_instance();
		$options = $essb_options->options;
	
		if (is_array($options)) {
			if (!isset($options['esml_monitor_types'])) {
				$options['esml_monitor_types'] = array();
			}
		}
	
		if (is_array ( $options ) && isset ( $options ['esml_monitor_types'] ) && is_array ( $options ['esml_monitor_types'] )) {
	
			global $wp_post_types;
			// classical post type listing
			foreach ( $pts as $pt ) {
	
				$selected = in_array ( $pt, $options ['esml_monitor_types'] ) ? '1' : '0';
	
				if ($selected == '1') {
					$types_to_track[] = $pt;
				}
	
			}
	
			// custom post types listing
			if (is_array ( $cpts ) && ! empty ( $cpts )) {
				foreach ( $cpts as $cpt ) {
	
					$selected = in_array ( $cpt, $options ['esml_monitor_types'] ) ? '1' : '0';
						
					if ($selected == '1') {
						$types_to_track[] = $cpt;
					}
	
					$selected = in_array ( $cpt, $options ['esml_monitor_types'] ) ? 'checked="checked"' : '';
				}
			}
		}
	
		return $types_to_track;
	
	}
	
	public function featured_post() {
		$widget_ops = array ('classname' => 'essb-top-social-posts', 		// class that will be added to li element
		                                 // in widgeted area ul
		'description' => 'Display post from category' )		// description displayed in
		                                            // admin
		;
		$control_ops = array ('width' => 200, 'height' => 250, 		// width of input widget in admin
		'id_base' => 'essb-top-post' )		// base of id of li element ex.
		                              // id="example-widget-1"
		;
		$this->WP_Widget ( 'essb_top_social_posts', 'Easy Social Share Buttons: Top Social Posts', $widget_ops, $control_ops ); // "Example
		                                                                                        // Widget"
		                                                                                        // will
		                                                                                        // be
		                                                                                        // name
		                                                                                        // in
		                                                                                        // control
		                                                                                        // panel
	}
	
	public function update($new_instance, $old_instance) {
		// save the widget info
		$instance = array ();
		$instance ['show_post_limit'] = strip_tags ( $new_instance ['show_post_limit'] );
		$instance ['order_post'] = strip_tags ( $new_instance ['order_post'] );
		$instance ['post_type'] = strip_tags ( $new_instance ['post_type'] );
		$instance ['custom_post_type'] = strip_tags ( $new_instance ['custom_post_type'] );
		$instance ['category'] = strip_tags ( $new_instance ['category'] );
		$instance ['title'] = strip_tags ( $new_instance ['title'] );
		$instance ['image_align'] = strip_tags ( $new_instance ['image_align'] );
		$instance ['image_size'] = strip_tags ( $new_instance ['image_size'] );
		$instance ['image_width'] = strip_tags ( $new_instance ['image_width'] );
		$instance ['image_height'] = strip_tags ( $new_instance ['image_height'] );
		$instance ['link_title_to_cat'] = (isset ( $new_instance ['link_title_to_cat'] ) ? 1 : 0);
		$instance ['show_post_title'] = (isset ( $new_instance ['show_post_title'] ) ? 1 : 0);
		$instance ['show_featured_image'] = (isset ( $new_instance ['show_featured_image'] ) ? 1 : 0);
		$instance ['show_excerpt'] = (isset ( $new_instance ['show_excerpt'] ) ? 1 : 0);
		return $instance;
	}
}

function essb_top_social_posts_widget_scripts($hook) {
	if ($hook == 'widgets.php')
		wp_enqueue_script ( 'essb-morris', ESSB_PLUGIN_URL . '/lib/modules/easy-top-social-posts-widget/easy-top-social-posts-widget.js', array ('jquery' ));
	
}
add_action ( 'admin_enqueue_scripts', 'essb_top_social_posts_widget_scripts' );
add_action ( 'widgets_init', create_function ( '', 'register_widget( "EasyTopSocialPostsWidget" );' ) );

?>
