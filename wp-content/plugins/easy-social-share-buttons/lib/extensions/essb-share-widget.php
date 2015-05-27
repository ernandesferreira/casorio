<?php
add_action( 'widgets_init', 'essb_social_share_widget' );
function essb_social_share_widget() {
	register_widget( 'EasySocialShareButtons_Widget' );
}
class EasySocialShareButtons_Widget extends WP_Widget {

	function EasySocialShareButtons_Widget() {
		//$widget_ops = array( 'classname' => 'easy-social-share-buttons-widget', 'description' => ''  );
		//$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'easy-social-share-buttons-widget' );
		$this->WP_Widget( 'easy-social-share-buttons-widget', 'Easy Social Share Buttons');
	}

	function widget( $args, $instance ) {
		global $essb_fans;
		extract( $args );

		$essb_w_counter = $instance['essb_w_counter'] ;
		$essb_w_totalcounter =  $instance['essb_w_totalcounter'] ;
		$essb_w_fixed =  $instance['essb_w_fixed'] ;
		$essb_w_style =  $instance['essb_w_style'] ;
		$essb_w_width =  $instance['essb_w_width'] ;
		$essb_w_template = isset($instance['essb_w_template']) ? $instance['essb_w_template'] : '';		
		
		$essb_w_align = isset($instance['essb_w_align']) ? $instance['essb_w_align'] : 'left';
		
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		$buttons = "";
		
		if (is_array($options)) {
			if (! is_array ( $options ['networks'] )) {
				$default_networks = EasySocialShareButtons::default_options ();
				$options ['networks'] = $default_networks ['networks'];
			}
		
			foreach ( $options ['networks'] as $k => $v ) {
				$display_name = isset ( $v [1] ) ? $v [1] : $k;
		
				if (trim($display_name) == "") {
					$display_name = $k;
				}
		
				$is_active = ( isset($instance['essb_w_'.$k]) ) ? esc_attr($instance['essb_w_'.$k]): '';
				
				if ($is_active == "1") {
					if ($buttons != '') { $buttons .= ","; }
					$buttons .= $k;
				}
			}
		}

		$shortcode = '[easy-share buttons="'.$buttons.'" native="no"';
		
		if ($essb_w_counter == "no") {
			$shortcode .= " counters=0";
		}
		else {
			$shortcode .= " counters=1";
		}
		
		$shortcode .= ' forceurl="yes"';
		$shortcode .= ' counters_pos="'.$essb_w_counter.'"';
		$shortcode .= ' total_counter_pos="'.$essb_w_totalcounter.'"';
		
		if ($essb_w_totalcounter == "no") {
			$shortcode .= ' hide_total="yes"';
		}			
		
		if ($essb_w_style == "icons") {
			$shortcode .= ' hide_names="force"';
		}
		if ($essb_w_style == "iconspop") {
			$shortcode .= ' hide_names="yes"';
		}
		if ($essb_w_style == "buttons") {
			$shortcode .= ' hide_names="no"';
		}
		
		if ($essb_w_fixed) {
			$shortcode .= ' fixedwidth="yes"';
		}
		
		if ($essb_w_width != '') {
			$shortcode .= ' fixedwidth_px="'.$essb_w_width.'"';
		}
		if ($essb_w_template != '') {
			$shortcode .= ' template="'.$essb_w_template.'"';
		}
		
		$shortcode .= ']';
		
		echo $before_widget;

		if ($essb_w_align != 'left') {
			echo '<div style="text-align: '.$essb_w_align.'; width: 100%;">';
		}
		
		//if( empty($box_only) )	echo $before_widget . $before_title . $title . $after_title;
		//$shortcode = '[essb-fans style="'.$style.'" cols="'.$cols.'" width="'.$width.'" '.$tiny.']';
		//echo do_shortcode($shortcode);
		$generated = do_shortcode($shortcode);
		//$generated = $essb_fans->generate_essb_fans_count($style, $cols, $width);
		echo $generated;
		
		if ($essb_w_align != 'left') {
			echo '</div>';
		}
		
		echo $after_widget;

		//if( empty($box_only) )	echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['essb_w_counter'] = $new_instance['essb_w_counter'] ;
		$instance['essb_w_totalcounter'] =  $new_instance['essb_w_totalcounter'] ;
		$instance['essb_w_fixed'] =  $new_instance['essb_w_fixed'] ;
		$instance['essb_w_style'] =  $new_instance['essb_w_style'] ;
		$instance['essb_w_width'] =  $new_instance['essb_w_width'] ;
		$instance['essb_w_align'] =  $new_instance['essb_w_align'];
		$instance['essb_w_template'] = $new_instance['essb_w_template'];

		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );
		
		if (is_array($options)) {
			if (! is_array ( $options ['networks'] )) {
				$default_networks = EasySocialShareButtons::default_options ();
				$options ['networks'] = $default_networks ['networks'];
			}
				
			foreach ( $options ['networks'] as $k => $v ) {
				$display_name = isset ( $v [1] ) ? $v [1] : $k;
		
				if (trim($display_name) == "") {
					$display_name = $k;
				}
				
				$instance['essb_w_'.$k] =  $new_instance['essb_w_'.$k] ;		
			}
		}
		
		return $instance;
	}

	function form( $instance ) {
		$network_list = array();
		$options = get_option ( EasySocialShareButtons::$plugin_settings_name );	
		
		$counter = ( isset($instance['essb_w_counter']) ) ? esc_attr($instance['essb_w_counter']): 'no';
		$total_counter = ( isset($instance['essb_w_totalcounter']) ) ? esc_attr($instance['essb_w_totalcounter']): 'no';
		$fixed = ( isset($instance['essb_w_fixed']) ) ? esc_attr($instance['essb_w_fixed']): '';
		$style = ( isset($instance['essb_w_style']) ) ? esc_attr($instance['essb_w_style']): '';
		$align = (isset($instance['essb_w_align'])) ? esc_attr($instance['essb_w_align']) : 'left';
		$template = (isset($instance['essb_w_template'])) ? esc_attr($instance['essb_w_template']) : 'left';
		if (is_array($options)) {
			if (! is_array ( $options ['networks'] )) {
				$default_networks = EasySocialShareButtons::default_options ();
				$options ['networks'] = $default_networks ['networks'];
			}
			
			foreach ( $options ['networks'] as $k => $v ) {
				$display_name = isset ( $v [1] ) ? $v [1] : $k;
				
				if (trim($display_name) == "") { $display_name = $k; } 
				
				$is_active = ( isset($instance['essb_w_'.$k]) ) ? esc_attr($instance['essb_w_'.$k]): '';
				
				?>
							<p>
			<input id="<?php echo $this->get_field_id('essb_w_'.$k); ?>" name="<?php echo $this->get_field_name('essb_w_'.$k); ?>" type="checkbox" value="1" <?php checked( '1', $is_active ); ?> />
			<label for="<?php echo $this->get_field_id('essb_w_'.$k); ?>"><?php echo $display_name; ?></label>
			</p>
			
				
				<?php 
			}
		}
		?>
		
			<p>
		<label for="<?php echo $this->get_field_id( 'essb_w_style' ); ?>"><?php _e( 'Button display style:' , ESSB_TEXT_DOMAIN ) ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'essb_w_style' ); ?>" name="<?php echo $this->get_field_name( 'essb_w_style' ); ?>" >
					<option value="icons" <?php if( $style == 'icons' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Social Share Icons Only' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="iconspop" <?php if( $style == 'iconspop' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Social Share Icons With Network Name Pop On Hover' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="buttons" <?php if( $style == 'buttons' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Social Share Buttons' , ESSB_TEXT_DOMAIN ) ?></option>
					</select>
		</p>
			<p>
		<label for="<?php echo $this->get_field_id( 'essb_w_align' ); ?>"><?php _e( 'Buttons align:' , ESSB_TEXT_DOMAIN ) ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'essb_w_align' ); ?>" name="<?php echo $this->get_field_name( 'essb_w_align' ); ?>" >
					<option value="left" <?php if( $align == 'left' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Left' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="right" <?php if( $align == 'right' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Right' , ESSB_TEXT_DOMAIN ) ?></option>
					<option value="center" <?php if( $align == 'center' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'Center' , ESSB_TEXT_DOMAIN ) ?></option>
					</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'essb_w_counter' ); ?>"><?php _e( 'Display Counter:' , ESSB_TEXT_DOMAIN ) ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'essb_w_counter' ); ?>" name="<?php echo $this->get_field_name( 'essb_w_counter' ); ?>" >
					<option value="no" <?php if( $counter == 'no' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'No' , ESSB_TEXT_DOMAIN ) ?></option>
							<option value="left"
								<?php echo (($counter == "left") ? " selected=\"selected\"": ""); ?>>Left</option>
							<option value="right"
								<?php echo (($counter == "right") ? " selected=\"selected\"": ""); ?>>Right</option>
							<option value="inside"
								<?php echo (($counter == "inside") ? " selected=\"selected\"": ""); ?>>Inside</option>
							<option value="insidename"
								<?php echo (($counter == "insidename") ? " selected=\"selected\"": ""); ?>>Inside with Network Names</option>
							<option value="hidden"
								<?php echo (($counter == "hidden") ? " selected=\"selected\"": ""); ?>>Hidden</option>
							<option value="leftm"
								<?php echo (($counter == "leftm") ? " selected=\"selected\"": ""); ?>>Left Modern</option>
							<option value="rightm"
								<?php echo (($counter == "rightm") ? " selected=\"selected\"": ""); ?>>Right Modern</option>
							<option value="top"
								<?php echo (($counter == "top") ? " selected=\"selected\"": ""); ?>>Top Modern</option>
							<option value="topm"
								<?php echo (($counter == "topm") ? " selected=\"selected\"": ""); ?>>Top Mini</option>
					</select>
		</p>
		
			<p>
		<label for="<?php echo $this->get_field_id( 'essb_w_totalcounter' ); ?>"><?php _e( 'Display Total Counter:' , ESSB_TEXT_DOMAIN ) ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'essb_w_totalcounter' ); ?>" name="<?php echo $this->get_field_name( 'essb_w_totalcounter' ); ?>" >
					<option value="no" <?php if( $total_counter == 'no' ) echo "selected=\"selected\""; else echo ""; ?>><?php _e( 'No' , ESSB_TEXT_DOMAIN ) ?></option>
							<option value="left"
								<?php echo (($total_counter == "left") ? " selected=\"selected\"": ""); ?>>Left</option>
							<option value="right"
								<?php echo (($total_counter == "right") ? " selected=\"selected\"": ""); ?>>Right</option>
							<option value="leftbig"
								<?php echo (($total_counter == "leftbig") ? " selected=\"selected\"": ""); ?>>Left
								Big</option>
							<option value="rightbig"
								<?php echo (($total_counter == "rightbig") ? " selected=\"selected\"": ""); ?>>Right
								Big</option>
							<option value="hidden"
								<?php echo (($total_counter == "hidden") ? " selected=\"selected\"": ""); ?>>Hidden</option>
					</select>
		</p>
		
			<p>
			<label for="<?php echo $this->get_field_id( 'essb_w_fixed' ); ?>"><?php _e( 'Fixed width buttons:' , ESSB_TEXT_DOMAIN ) ?></label>
			<input id="<?php echo $this->get_field_id( 'essb_w_fixed' ); ?>" name="<?php echo $this->get_field_name( 'essb_w_fixed' ); ?>" value="true" <?php if( $fixed ) echo 'checked="checked"'; ?> type="checkbox" />
			<br /><small><?php _e( 'This option will generate buttons with equal width' , ESSB_TEXT_DOMAIN ) ?></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'essb_w_width' ); ?>"><?php _e( 'Fixed width buttons value :' , ESSB_TEXT_DOMAIN ) ?></label>
			<input id="<?php echo $this->get_field_id( 'essb_w_width' ); ?>" name="<?php echo $this->get_field_name( 'essb_w_width' ); ?>" value="<?php if(isset( $instance['essb_w_width'] )) echo $instance['essb_w_width']; ?>" style="width:40px;" type="text" /> <?php _e( 'px' , ESSB_TEXT_DOMAIN ) ?>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'essb_w_template' ); ?>"><?php _e( 'Template:' , ESSB_TEXT_DOMAIN ) ?></label>
<select class="widefat" id="<?php echo $this->get_field_id( 'essb_w_template' ); ?>" name="<?php echo $this->get_field_name( 'essb_w_template' ); ?>">
							<option value="" <?php echo (($template == "") ? " selected=\"selected\"": ""); ?>>Use dafault template from settings</option>
							<option value="default" <?php echo (($template == "default") ? " selected=\"selected\"": ""); ?>>Default</option>
							<option value="metro" <?php echo (($template == "metro") ? " selected=\"selected\"": ""); ?>>Metro</option>
							<option value="round" <?php echo (($template == "round") ? " selected=\"selected\"": ""); ?>>Round</option>
							<option value="modern" <?php echo (($template == "modern") ? " selected=\"selected\"": ""); ?>>Modern</option>
							<option value="big" <?php echo (($template == "big") ? " selected=\"selected\"": ""); ?>>Big</option>
														<option value="metro-retina" <?php echo (($template == "metro-retina") ? " selected=\"selected\"": ""); ?>>Metro (Retina)</option>
														<option value="big-retina" <?php echo (($template == "big-retina") ? " selected=\"selected\"": ""); ?>>Big (Retina)</option>
														<option value="flat-retina" <?php echo (($template == "flat-retina") ? " selected=\"selected\"": ""); ?>>Flat (Retina)</option>
														<option value="light-retina" <?php echo (($template == "light-retina") ? " selected=\"selected\"": ""); ?>>Light (Retina)</option>
														<option value="tiny-retina" <?php echo (($template == "tiny-retina") ? " selected=\"selected\"": ""); ?>>Tiny (Retina)</option>
														<option value="round-retina" <?php echo (($template == "round-retina") ? " selected=\"selected\"": ""); ?>>Round (Retina)</option>
														<option value="modern-retina" <?php echo (($template == "modern-retina") ? " selected=\"selected\"": ""); ?>>Modern (Retina)</option>
														</select>		
		</p>		
		<?php 
	}
}

?>