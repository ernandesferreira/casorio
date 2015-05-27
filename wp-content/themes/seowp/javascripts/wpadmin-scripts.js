/**
 * Theme back-end JavaScript
 *
 * -------------------------------------------------------------------
 *
 * DESCRIPTION:
 *
 * Custom JavaScript used to improve/extend some bundled plugins UI,
 * run actions for theme installation wizard
 *
 * @package    SEOWP WordPress Theme
 * @author     Vlad Mitkovsky <info@lumbermandesigns.com>
 * @copyright  2014 Lumberman Designs
 * @license    http://themeforest.net/licenses
 * @link       http://themeforest.net/user/lumbermandesigns
 *
 * -------------------------------------------------------------------
 *
 * Send your ideas on code improvement or new hook requests using
 * contact form on http://themeforest.net/user/lumbermandesigns
 */

(function ($) {
	"use strict";

	jQuery(document).ready(function ($) {


// $('tr[id^="meta-"]').css('background','red');
		
		// Retrieve focus keyword and trim
		// var focuskw = jQuery.trim(jQuery('#' + wpseoMetaboxL10n.field_prefix + 'focuskw').val());
		// focuskw = yst_escapeFocusKw(focuskw).toLowerCase();
		// var p = new RegExp("(^|[ \s\n\r\t\.,'\(\"\+;!?:\-])" + focuskw + "($|[ \s\n\r\t.,'\)\"\+!?:;\-])", 'gim');
		// Extract 'dslc_search_content' value
		// var page_content = $('input[value="dslc_search_content"]').parents('tr[id^="meta-"]').find('textarea').val();
		// console.info( ptest(page_content, p) );
		// if ( ! $('#content').length ) {
			// $('#content').html(page_content);
		// }


		// $(".post-type-page #content").html();
		// console.info( wpseoMetaboxL10n );

		// Helper function to get the value from URL Parameter
		var QueryString = function () {
		  // This function is anonymous, is executed immediately and
		  // the return value is assigned to QueryString!
		  var query_string = {};
		  var query = window.location.search.substring(1);
		  var vars = query.split("&");
		  for (var i=0;i<vars.length;i++) {
			 var pair = vars[i].split("=");
				// If first entry with this name
			 if (typeof query_string[pair[0]] === "undefined") {
				query_string[pair[0]] = pair[1];
				// If second entry with this name
			 } else if (typeof query_string[pair[0]] === "string") {
				var arr = [ query_string[pair[0]], pair[1] ];
				query_string[pair[0]] = arr;
				// If third or later entry with this name
			 } else {
				query_string[pair[0]].push(pair[1]);
			 }
		  }
			 return query_string;
		} ();

		/* Hide theme quick setup block on "Hide this message" button click */
		if (QueryString.hide_quicksetup) {
			$(".lumberman-message.quick-setup").hide();
		}


		/**
		* ----------------------------------------------------------------------
		* Mega Main Menu item alignment control
		* TODO: move this block into a separate file /inc/plugin-integrations/megamainmenu/megamainmenu-wpadmin.js
		*/

		var alignmentSelector = '';
		alignmentSelector += '<div class="clearboth"></div>';
		alignmentSelector += '<div class="bootstrap">';
		alignmentSelector += '	<div class="menu-item-align option row menu-item-124item_style select_type">';

		alignmentSelector += '		<div class="option_header col-md-3 col-sm-12">';
		alignmentSelector += '			<div class="descr">';
		alignmentSelector += '				Menu item alignment';
		alignmentSelector += '			</div><!-- class="descr" -->';
		alignmentSelector += '		</div>';

		alignmentSelector += '		<div class="option_field col-md-9 col-sm-12">';
		alignmentSelector += '			<select name="lbmn_menu-item-align" class="col-xs-12 form-control input-sm">';
		alignmentSelector += '				<option value="">Default</option>';
		alignmentSelector += '				<option value="menu-align-left">Left</option>';
		alignmentSelector += '				<option value="menu-align-right">Right</option>';
		alignmentSelector += '			</select>';
		// alignmentSelector += '<input type="text" class="widefat code edit-menu-item-classes" name="syntetic-menu-item-classes" value="" />';//style="display:none"
		alignmentSelector += '		</div>';

		alignmentSelector += '		<div class="col-xs-12">';
		alignmentSelector += '			<div class="h_separator">';
		alignmentSelector += '			</div><!-- class="h_separator" -->';
		alignmentSelector += '		</div>';

		alignmentSelector += '	</div>';
		alignmentSelector += '</div>';


		$(".nav-menus-php .menu-item-depth-0").each(function(index, el) {
			$(".menu-item-settings .bootstrap", el).eq('1').before(alignmentSelector);
		});

		// on page load update menu align dropdown value according to menu item class
		$("select[name=lbmn_menu-item-align]").each(function(index, val) {
			var align_selector    = $(this);
			var menu_class_field  = $(this).parents('.menu-item-settings').find('.edit-menu-item-classes');
			var current_class_val = menu_class_field.val();
			var align_class       = current_class_val.match(/menu-align-left|menu-align-right/g, '');

			if ( align_class == 'menu-align-left' ) {
				align_selector.find('option[value="menu-align-left"]').attr('selected', 'selected');
			} else if ( align_class == 'menu-align-right' ) {
				align_selector.find('option[value="menu-align-right"]').attr('selected', 'selected');
			}

		});

		// on menu align dropdown change update class field
		$("select[name=lbmn_menu-item-align]").live('change', function(event) {
			var selected_value    = $(this).find('option:selected').val();
			var menu_class_field  = $(this).parents('.menu-item-settings').find('.edit-menu-item-classes');
			var current_class_val = menu_class_field.val();
			var cleaned_classes   = current_class_val.replace(/menu-align-left|menu-align-right/g, '');

			if (cleaned_classes != null) {
				selected_value = cleaned_classes + ' ' + selected_value;
				selected_value = selected_value.replace(/\s+/g, ' ');
			}

			menu_class_field.val(selected_value);
		});


		/**
		* ----------------------------------------------------------------------
		* TGM plugin activation page improvements
		*/

		if ( $('body').hasClass('appearance_page_install-required-plugins') ) {
			// we are on the istall required plugins page

			$('#the-list tr').each(function(index, el) {
				var currentRow = $(this);

				// Mark row with .plugin-required class
				if ( $(currentRow).find('.type').text() == 'Required' ) {
					$(currentRow).addClass('required');
				}

				// Mark row with .plugin-notinstalled class
				if ( $(currentRow).find('.status').text() == 'Not Installed' ) {
					$(currentRow).addClass('notinstalled');
				}

				// Mark row with .plugin-notinstalled class
				if ( $(currentRow).find('.status').text() == 'Not Updated' ) {
					$(currentRow).addClass('update');
				}

				// Mark row with .plugin-notactive class
				if ( $(currentRow).find('.status').text() == 'Installed But Not Activated' ) {
					$(currentRow).addClass('inactive');
				}

				// Mark row with .plugin-active class
				if ( $(currentRow).find('.status').text() == 'Active' ) {
					$(currentRow).addClass('active');
				}

			});


			// Duplicate action link next to "Status" column
			$('.row-actions a').each(function(index, el) {
				var statusColumns = $(this).closest('tr').find('.status');

				// $(statusColumns).css('background', 'red');

				$(this).clone().addClass('button button-primary').appendTo( statusColumns );
			});

			// Remove source column, it only confuse users
			$('.column-source').hide();

			/**
			 * ----------------------------------------------------------------------
			 * Auto plugins installation
			 */

			if (QueryString.autoinstall) {
				$('.check-column input').attr('checked','checked').change();
				$('select[name=action]').find('option').removeAttr('selected');
				$('select[name=action] option[value=tgmpa-bulk-install]').attr('selected','selected');
				$('select[name=action]').change();
				$('input#doaction').trigger('click');

			}
		}


		/**
		 * ----------------------------------------------------------------------
		 * Theme installation wizard action:
		 * 1. Plugins installation
		 */

		var window_hash = window.location.hash.substr(1);
		$("#do_plugins-install").click(function() {
			// disable spinner
			$("#theme-setup-step-1").addClass('loading');
			// open TGMPA page in the hidden iframe
			// AJAX $("body").append('<iframe src="' + location.protocol + '//' + location.host + location.pathname + '?page=install-required-plugins&autoinstall=1" id="iframe-plugins-install" style="visibility:hidden;position:absolute;top:0;"></iframe> ');
		});

		// // This function will be caled on the end of plugin installation from
		// // hidden 'iframe-plugins-install' iframe
		// window.pluginsInstalledSuccessfully = function () {
		// 	// disable spinner
		// 	$("#theme-setup-step-1").removeClass('loading');
		// 	// mark installer step as completed
		// 	$(".lumberman-message.quick-setup .step-plugins").addClass("step-completed");
		// 	// hide standard TGMPA notice
		// 	$("#setting-error-tgmpa").hide();
		// }

		// window.pluginsInstallFailed = function () {
		// 	// disable spinner
		// 	$("#theme-setup-step-1").removeClass('loading');
		// 	// show error message
		// 	$(".lumberman-message.quick-setup .step-plugins .error").css("display","inline-block");
		// }

		// Check if all plugins were installed manually
		// If all installed: redirect to themes.php with url var set
		if ( window_hash === 'checkifallinstalled' ) {
			if ( $(".wp-list-table.plugins tr.inactive").length < 1 ) {
				window.location.replace(location.protocol + '//' + location.host + location.pathname+"?plugins=installed");
			}
		}


		/**
		 * ----------------------------------------------------------------------
		 * Theme installation wizard action:
		 * 2. Configure basic settings
		 */

		$("#do_basic-config").click(function(event) {
			event.preventDefault();

			// Do not run multiply times
			if ( ! $("#theme-setup-step-2").hasClass('step-completed') ) {
				// Do not run before step 1
				if ( $("#theme-setup-step-1").hasClass('step-completed') ) {

					$("#theme-setup-step-2").addClass('loading');
					$.ajax({
						// type: "POST",
						cache: false,
						url: location.protocol + '//' + location.host + location.pathname + "?importcontent=basic-templates",
						success: function(response){

							// $(".lumberman-message.quick-setup").after('<div class="import-log-window"></div>');
							// $(".import-log-window").append( $(response).find(".ajax-log") );

							// config process failed
							if ( $(response).find(".ajax-request-error").length > 0 ) {
								$(".lumberman-message.quick-setup .step-basic_config .error").css('display', 'inline-block');
								$(".lumberman-message.quick-setup").after('<div class="error-log-window" style="display:none"></div>');
								$(".error-log-window").append( $(response).find(".ajax-log") );

								$('.lumberman-message.quick-setup .step-basic_config .error-log-window').css('display','inline-block');

							// config process succeeded
							} else {
								// update option "LBMN_THEME_NAME . '_basic_config_done'"
								// with 'true' value
								$.ajax({
									cache: false,
									url: location.protocol + '//' + location.host + location.pathname + "?basic_setup=completed",
								});

								$(".lumberman-message.quick-setup .step-basic_config").addClass("step-completed");
							}

							// Import ESSB Easy Social Share Buttons plugin configuration
							// TODO: ask plugin author to provide a hook for this
							var essbOptionsRaw = '{"style":"2","networks":{"facebook":[1,"Facebook"],"twitter":[1,"Twitter"],"google":[1,"Google+"],"pinterest":[1,"Pinterest"],"linkedin":[1,"LinkedIn"],"digg":[0,"Digg"],"stumbleupon":[0,"StumbleUpon"],"vk":[0,"VKontakte"],"tumblr":[0,"Tumblr"],"print":[0,"Print"],"mail":[0,"E-mail"],"reddit":[0,"Reddit"],"flattr":[0,"Flattr"],"del":[0,"Delicious"],"buffer":[0,"Buffer"],"love":[1,"Love This"],"weibo":[0,"Weibo"],"pocket":[0,"Pocket"],"xing":[0,"Xing"],"ok":[0,"Odnoklassniki"],"mwp":[0,"ManageWP.org"]},"show_counter":1,"hide_social_name":1,"target_link":1,"twitter_user":"","display_in_types":["post"],"display_where":"nowhere","mail_subject":"Visit this site %%siteurl%%","mail_body":"Hi, this may be intersting you: \\\"%%title%%\\\"! This is the link: %%permalink%%","colors":{"bg_color":"","txt_color":"","facebook_like_button":"false"},"facebook_like_button":"false","facebook_like_button_api":"false","googleplus":"false","vklike":"false","vklikeappid":"","customshare":"false","customshare_url":"","customshare_text":"","customshare_imageurl":"","customshare_description":"","pinterest_sniff_disable":"false","mail_copyaddress":"","otherbuttons_sameline":"false;","twitterfollow":"false","twitterfollowuser":"","url_short_native":"false","url_short_google":"false","custom_url_like":"false","custom_url_like_address":"","twitteruser":"","twitterhashtags":"","twitter_nojspop":"false","custom_url_plusone_address":"","youtubesub":"false","youtubechannel":"","pinterestfollow_url":"","pinterestfollow_disp":"","pinterestfollow":"false","facebooksimple":"false","facebooktotal":"false","facebookhashtags":"","stats_active":"false","opengraph_tags":"false","facebookadvanced":"false","facebookadvancedappid":"","buttons_pos":"right","disable_adminbar_menu":"false","register_menu_under_settings":"false","twitter_shareshort":"false","using_yoast_ga":"false","url_short_bitly":"false","url_short_bitly_user":"","url_short_bitly_api":"","twitter_card":"false","twitter_card_user":"","twitter_card_type":"","advanced_share":{"facebook_u":"","facebook_t":"","facebook_i":"","facebook_d":"","twitter_u":"","twitter_t":"","google_u":"","pinterest_u":"","pinterest_t":"","pinterest_i":"","pinterest_d":"","linkedin_u":"","linkedin_t":"","digg_u":"","digg_t":"","stumbleupon_u":"","vk_u":"","tumblr_u":"","tumblr_t":"","reddit_u":"","reddit_t":"","flattr_u":"","del_u":"","del_t":"","buffer_u":"","buffer_t":"","weibo_u":"","pocket_u":"","xing_u":"","ok_u":"","mwp_u":""},"fullwidth_share_buttons":"true","fullwidth_share_buttons_correction":"","opengraph_tags_fbpage":"","opengraph_tags_fbadmins":"","opengraph_tags_fbapp":"","sso_default_image":"","translate_mail_title":"","translate_mail_email":"","translate_mail_recipient":"","translate_mail_subject":"","translate_mail_message":"","translate_mail_cancel":"","translate_mail_send":"","facebook_like_button_width":"            ","use_minified_css":"false","use_minified_js":"false","mail_captcha_answer":"","mail_captcha":"","flattr_username":"","flattr_tags":"","flattr_cat":"text","flattr_lang":"sq_AL","managedwp_button":"false","skin_native":"false","skinned_fb_color":"","skinned_fb_width":"","skinned_fb_text":"","skinned_fb_hovercolor":"","skinned_fb_textcolor":"","skinned_vk_color":"","skinned_vk_width":"","skinned_vk_text":"","skinned_vk_hovercolor":"","skinned_vk_textcolor":"","skinned_google_color":"","skinned_google_width":"","skinned_google_text":"","skinned_google_hovercolor":"","skinned_google_textcolor":"","skinned_twitter_color":"","skinned_twitter_width":"","skinned_twitter_text":"","skinned_twitter_hovercolor":"","skinned_twitter_textcolor":"","skinned_pinterest_color":"","skinned_pinterest_width":"","skinned_pinterest_text":"","skinned_pinterest_hovercolor":"","skinned_pinterest_textcolor":"","skinned_youtube_color":"","skinned_youtube_width":"","skinned_youtube_text":"","skinned_youtube_hovercolor":"","skinned_youtube_textcolor":"","force_hide_social_name":"false;","native_social_counters_fb":"true","native_social_counters_g":"true","native_social_counters_t":"true","native_social_counters_youtube":"false","woocommece_share":"false","message_share_buttons":"","message_like_buttons":"","popup_window_title":"","popup_window_close_after":"","popup_window_popafter":"","native_social_language":"","float_top":"","float_bg":"","sidebar_pos":"","counter_pos":"inside","force_hide_total_count":"false","display_excerpt":"false","buddypress_activity":"false","buddypress_group":"false","bbpress_topic":"false","bbpress_forum":"false","sidebar_sticky":"false","float_full":"false","total_counter_pos":"","force_counters_admin":"true","force_hide_buttons_on_mobile":"false","another_display_popup":"false","another_display_sidebar":"false","float_js":"false","translate_love_loved":"","translate_love_thanks":"","display_exclude_from":"","display_position_mobile":"","another_display_deactivate_mobile":"false","network_message":{"facebook":"","twitter":"","google":"","pinterest":"","linkedin":"","digg":"","stumbleupon":"","vk":"","tumblr":"","print":"","mail":"","reddit":"","flattr":"","del":"","buffer":"","love":"","weibo":"","pocket":"","xing":""},"customizer_bgcolor":"","customizer_textcolor":"","customizer_hovercolor":"","customizer_hovertextcolor":"","customizer_facebook_bgcolor":"","customizer_facebook_textcolor":"","customizer_facebook_hovercolor":"","customizer_facebook_hovertextcolor":"","customizer_facebook_icon":"","customizer_facebook_iconbgsize":"","customizer_facebook_hovericon":"","customizer_facebook_hovericonbgsize":"","customizer_twitter_bgcolor":"","customizer_twitter_textcolor":"","customizer_twitter_hovercolor":"","customizer_twitter_hovertextcolor":"","customizer_twitter_icon":"","customizer_twitter_iconbgsize":"","customizer_twitter_hovericon":"","customizer_twitter_hovericonbgsize":"","customizer_google_bgcolor":"","customizer_google_textcolor":"","customizer_google_hovercolor":"","customizer_google_hovertextcolor":"","customizer_google_icon":"","customizer_google_iconbgsize":"","customizer_google_hovericon":"","customizer_google_hovericonbgsize":"","customizer_pinterest_bgcolor":"","customizer_pinterest_textcolor":"","customizer_pinterest_hovercolor":"","customizer_pinterest_hovertextcolor":"","customizer_pinterest_icon":"","customizer_pinterest_iconbgsize":"","customizer_pinterest_hovericon":"","customizer_pinterest_hovericonbgsize":"","customizer_linkedin_bgcolor":"","customizer_linkedin_textcolor":"","customizer_linkedin_hovercolor":"","customizer_linkedin_hovertextcolor":"","customizer_linkedin_icon":"","customizer_linkedin_iconbgsize":"","customizer_linkedin_hovericon":"","customizer_linkedin_hovericonbgsize":"","customizer_digg_bgcolor":"","customizer_digg_textcolor":"","customizer_digg_hovercolor":"","customizer_digg_hovertextcolor":"","customizer_digg_icon":"","customizer_digg_iconbgsize":"","customizer_digg_hovericon":"","customizer_digg_hovericonbgsize":"","customizer_stumbleupon_bgcolor":"","customizer_stumbleupon_textcolor":"","customizer_stumbleupon_hovercolor":"","customizer_stumbleupon_hovertextcolor":"","customizer_stumbleupon_icon":"","customizer_stumbleupon_iconbgsize":"","customizer_stumbleupon_hovericon":"","customizer_stumbleupon_hovericonbgsize":"","customizer_vk_bgcolor":"","customizer_vk_textcolor":"","customizer_vk_hovercolor":"","customizer_vk_hovertextcolor":"","customizer_vk_icon":"","customizer_vk_iconbgsize":"","customizer_vk_hovericon":"","customizer_vk_hovericonbgsize":"","customizer_tumblr_bgcolor":"","customizer_tumblr_textcolor":"","customizer_tumblr_hovercolor":"","customizer_tumblr_hovertextcolor":"","customizer_tumblr_icon":"","customizer_tumblr_iconbgsize":"","customizer_tumblr_hovericon":"","customizer_tumblr_hovericonbgsize":"","customizer_print_bgcolor":"","customizer_print_textcolor":"","customizer_print_hovercolor":"","customizer_print_hovertextcolor":"","customizer_print_icon":"","customizer_print_iconbgsize":"","customizer_print_hovericon":"","customizer_print_hovericonbgsize":"","customizer_mail_bgcolor":"","customizer_mail_textcolor":"","customizer_mail_hovercolor":"","customizer_mail_hovertextcolor":"","customizer_mail_icon":"","customizer_mail_iconbgsize":"","customizer_mail_hovericon":"","customizer_mail_hovericonbgsize":"","customizer_reddit_bgcolor":"","customizer_reddit_textcolor":"","customizer_reddit_hovercolor":"","customizer_reddit_hovertextcolor":"","customizer_reddit_icon":"","customizer_reddit_iconbgsize":"","customizer_reddit_hovericon":"","customizer_reddit_hovericonbgsize":"","customizer_flattr_bgcolor":"","customizer_flattr_textcolor":"","customizer_flattr_hovercolor":"","customizer_flattr_hovertextcolor":"","customizer_flattr_icon":"","customizer_flattr_iconbgsize":"","customizer_flattr_hovericon":"","customizer_flattr_hovericonbgsize":"","customizer_del_bgcolor":"","customizer_del_textcolor":"","customizer_del_hovercolor":"","customizer_del_hovertextcolor":"","customizer_del_icon":"","customizer_del_iconbgsize":"","customizer_del_hovericon":"","customizer_del_hovericonbgsize":"","customizer_buffer_bgcolor":"","customizer_buffer_textcolor":"","customizer_buffer_hovercolor":"","customizer_buffer_hovertextcolor":"","customizer_buffer_icon":"","customizer_buffer_iconbgsize":"","customizer_buffer_hovericon":"","customizer_buffer_hovericonbgsize":"","customizer_love_bgcolor":"","customizer_love_textcolor":"","customizer_love_hovercolor":"","customizer_love_hovertextcolor":"","customizer_love_icon":"","customizer_love_iconbgsize":"","customizer_love_hovericon":"","customizer_love_hovericonbgsize":"","customizer_weibo_bgcolor":"","customizer_weibo_textcolor":"","customizer_weibo_hovercolor":"","customizer_weibo_hovertextcolor":"","customizer_weibo_icon":"","customizer_weibo_iconbgsize":"","customizer_weibo_hovericon":"","customizer_weibo_hovericonbgsize":"","customizer_pocket_bgcolor":"","customizer_pocket_textcolor":"","customizer_pocket_hovercolor":"","customizer_pocket_hovertextcolor":"","customizer_pocket_icon":"","customizer_pocket_iconbgsize":"","customizer_pocket_hovericon":"","customizer_pocket_hovericonbgsize":"","customizer_xing_bgcolor":"","customizer_xing_textcolor":"","customizer_xing_hovercolor":"","customizer_xing_hovertextcolor":"","customizer_xing_icon":"","customizer_xing_iconbgsize":"","customizer_xing_hovericon":"","customizer_xing_hovericonbgsize":"","customizer_css":"","customizer_is_active":"false","customizer_remove_bg_hover_effects":"false","twitter_tweet":"follow","pinterest_native_type":"follow","skin_native_skin":"flat","use_wpmandrill":"false","scripts_in_head":"false","twitter_shareshort_service":"wp_get_shortlink","translate_mail_message_error_send":"","translate_mail_message_invalid_captcha":"","translate_mail_message_sent":"","fixed_width_value":"","fixed_width_active":"false","sso_apply_the_content":"false","facebook_like_button_height":"","facebook_like_button_margin_top":"","module_off_lv":"false","module_off_sfc":"false","load_js_async":"false","encode_url_nonlatin":"false","stumble_noshortlink":"false","turnoff_essb_advanced_box":"false","esml_monitor_types":[],"esml_active":"false","esml_ttl":"1 hour","avoid_nextpage":"false"}';
							var essbImport = {
								cmd: "restore",
								restore: "Restore",
								essb_options: essbOptionsRaw
							}

							$.ajax({
								type: "POST",
								cache: false,
								data : essbImport,
								url: "admin.php?page=essb_settings&tab=backup"
							});

							// mark installer step as completed
							$("#theme-setup-step-2").removeClass('loading');
						},

					}); //ajax

				} else {
					$( "#theme-setup-step-1" ).effect( "bounce", 1000);
				}
			}
		});

		// Show error log functionality
		$('.show-error-log').on('click', function(event) {
			event.preventDefault();
			/* Act on the event */
			$(".error-log-window").show();
		});


		/**
		 * ----------------------------------------------------------------------
		 * Theme installation wizard action:
		 * 3. Import demo content
		 */

		$("#do_demo-import").click(function(event) {
			event.preventDefault();

			// Do not run multiply times
			// if ( ! $("#theme-setup-step-3").hasClass('step-completed') ) {
				// Do not run before step 1
				if ( $("#theme-setup-step-1").hasClass('step-completed') ) {
					// Do not run before step 2
					if ( $("#theme-setup-step-2").hasClass('step-completed') ) {

						$("#theme-setup-step-3").addClass('loading');

						ajax_importcontent_part();

					} else {
						$( "#theme-setup-step-2" ).effect( "bounce", 1000);
					}
				} else {
					$( "#theme-setup-step-1" ).effect( "bounce", 1000);
				}
			// }
		});



		/**
		 * ----------------------------------------------------------------------
		 * LiveComposer Settings Page
		 * Warning to use only letters and numbers in the sidebar name
		 */

		// if current admin screens is Live Composer > Widgets Module
		if ( $("body").hasClass('live-composer_page_dslc_plugin_options_widgets_m') ) {
			$(".dslca-plugin-opts-list-wrap .dslca-plugin-opts-list-add-hook").on('click', function(event) {
				// event.preventDefault();
				/* Act on the event */
				$(this).before(
					"<p style=' width: 300px; margin-bottom: 20px; font-size: 13px; color: #CF522A;'> Only letters, numbers and spaces may be used in sidebar names. </p>"
				);
			});

		}

		// On Live Composer settings page
		// hide "Archives Settings" and "Tutorials" tabs
		$("a[href='?page=dslc_plugin_options_archives'], a[href='?page=dslc_plugin_options_tuts'] ").hide();


		/**
		 * ----------------------------------------------------------------------
		 * Hide unwanted metaboxes on edit screen
		 */

		if ( $("body").hasClass('wp-admin') ) {
			// Hide Mega Main Options metabox
			$(".postbox#mm_general").hide();

			// For pages only
			if ( $("body").hasClass('post-type-page') ) {
				// Hide discussion metabox
				$(".postbox#commentstatusdiv").hide();
			}
		}


		/**
		 * ----------------------------------------------------------------------
		 * Update menus screen if Mega Menu not initialized
		 * (to solve bug when mega menu breaks on the first edit )
		 */

		if ( $("body").hasClass('nav-menus-php') ) {
			// If "Demo Mega Menu (Header)" selected
			if ( $(".manage-menus select#menu option[selected='selected']").text().indexOf("Demo Mega Menu (Header)") != -1 ) {
				if ( $("#menu-management .menu-item .background_image_type").length == 0 ) {
					location.reload(true);
				}
			}
		}


	}); // document.ready


	/**
	 * ----------------------------------------------------------------------
	 * Special Functions That Called from TGMPA php file to update
	 * installer required plugins status
	 */

	// This function will be caled on the end of plugin installation from
	// hidden 'iframe-plugins-install' iframe
	window.pluginsInstalledSuccessfully = function () {
		// disable spinner
		$("#theme-setup-step-1").removeClass('loading');
		// mark installer step as completed
		$(".lumberman-message.quick-setup .step-plugins").addClass("step-completed");
		// hide standard TGMPA notice
		$("#setting-error-tgmpa").hide();
	}

	window.pluginsInstallFailed = function () {
		// disable spinner
		$("#theme-setup-step-1").removeClass('loading');
		// show error message
		$(".lumberman-message.quick-setup .step-plugins .error").css("display","inline-block");
	}



	function ajax_importcontent_part(part){

		var part_num = part;

		var progressbar_position = (parseInt(part_num)+1) * 6.25; // 100% / 28steps = 3.58% per step
		var currentdate = new Date();
		console.info( "--------------------" );
		console.info( currentdate.getHours() + ":"
                + currentdate.getMinutes() + ":"
                + currentdate.getSeconds() + "  >>  "
                + part_num);

		if(part)
			part = '&importcontent_part='+part;
		else
			part = '&importcontent_part=0';
		$.ajax({
			cache: false,
			url: location.protocol + '//' + location.host + location.pathname + "?importcontent=alldemocontent"+part,
			success: function(response){
				
				$('.import-progress').css('opacity','1');
				$('.progress-indicator').css('width', progressbar_position + '%');

				if($(response).find('#importcontent_part').length > 0){
					console.info( 'part' + part );

					if ( $(response).find(".ajax-request-error").length > 0 ) {
						$(".lumberman-message.quick-setup .step-basic_config .error").css('display', 'inline-block');
						$(".lumberman-message.quick-setup").after('<div class="error-log-window" style="display:none"></div>');
						$(".error-log-window").append( $(response).find(".ajax-log") );
						$('.lumberman-message.quick-setup .step-basic_config .error-log-window').css('display','inline-block');

						// config process succeeded
					}else{
						ajax_importcontent_part($(response).find('#importcontent_part').val());
					}
				}else{
					$("#theme-setup-step-3").removeClass('loading');
					// config process failed
					if ( $(response).find(".ajax-request-error").length > 0 ) {
						$(".lumberman-message.quick-setup .step-basic_config .error").css('display', 'inline-block');
						$(".lumberman-message.quick-setup").after('<div class="error-log-window" style="display:none"></div>');
						$(".error-log-window").append( $(response).find(".ajax-log") );
						$('.lumberman-message.quick-setup .step-basic_config .error-log-window').css('display','inline-block');

						// config process succeeded
					} else {
						$(".lumberman-message.quick-setup .step-demoimport").addClass("step-completed");
						// update option "LBMN_THEME_NAME . '_democontent_imported'"
						// with 'true' value
						$.ajax({
							cache: false,
							url: location.protocol + '//' + location.host + location.pathname + "?demoimport=completed"
						});
					}
				}
			}
		}); //ajax
	}


	/**
	 * ----------------------------------------------------------------------
	 * Remove Rankie License Box
	 */
	if ( $('body').hasClass('wp-rankie_page_wp_rankie_settings') ) {
		$('.wp-rankie_page_wp_rankie_settings .metabox-holder .postbox').last().hide();
		$('.wp-rankie_page_wp_rankie_settings #postbox-container-1').css('min-width','800px');
	}

})(jQuery);

/**
 * ----------------------------------------------------------------------
 * Override YOAST functions to work with Live Composer
 */

// Override original Yoasts functions only when LC activated for the current page

if (typeof lbmnData.currentPageLCCode !== 'undefined') {
	if ( lbmnData.currentPageLCCode.length) {

		function yst_testFocusKw() {
			// Retrieve focus keyword and trim
			var focuskw = jQuery.trim(jQuery('#' + wpseoMetaboxL10n.field_prefix + 'focuskw').val());
			focuskw = yst_escapeFocusKw(focuskw).toLowerCase();

			if (jQuery('#editable-post-name-full').length) {
				var postname = jQuery('#editable-post-name-full').text();
				var url = wpseoMetaboxL10n.wpseo_permalink_template.replace('%postname%', postname).replace('http://', '');
			}
			var p = new RegExp("(^|[ \s\n\r\t\.,'\(\"\+;!?:\-])" + focuskw + "($|[ \s\n\r\t.,'\)\"\+!?:;\-])", 'gim');
			//remove diacritics of a lower cased focuskw for url matching in foreign lang
			var focuskwNoDiacritics = removeLowerCaseDiacritics(focuskw);
			var p2 = new RegExp(focuskwNoDiacritics.replace(/\s+/g, "[-_\\\//]"), 'gim');

			var focuskwresults = jQuery('#focuskwresults');
			var metadesc = jQuery('#wpseosnippet').find('.desc span.content').text();


			if (focuskw != '') {
				var html = '<p>' + wpseoMetaboxL10n.keyword_header + '</p>';
				html += '<ul>';
				if (jQuery('#title').length) {
					html += '<li>' + wpseoMetaboxL10n.article_header_text + ptest(jQuery('#title').val(), p) + '</li>';
				}
				html += '<li>' + wpseoMetaboxL10n.page_title_text + ptest(jQuery('#wpseosnippet_title').text(), p) + '</li>';
				html += '<li>' + wpseoMetaboxL10n.page_url_text + ptest(url, p2) + '</li>';
				if (lbmnData.currentPageContent.length) {
					html += '<li>' + wpseoMetaboxL10n.content_text + ptest(lbmnData.currentPageContent, p) + '</li>';
				}
				html += '<li>' + wpseoMetaboxL10n.meta_description_text + ptest(metadesc, p) + '</li>';
				html += '</ul>';
				focuskwresults.html(html);
			} else {
				focuskwresults.html('');
			}
		}

		function yst_updateDesc() {
			var desc = jQuery.trim(yst_clean(jQuery('#' + wpseoMetaboxL10n.field_prefix + 'metadesc').val()));
			var divHtml = jQuery('<div />');
			var snippet = jQuery('#wpseosnippet');

			if (desc == '' && wpseoMetaboxL10n.wpseo_metadesc_template != '') {
				desc = wpseoMetaboxL10n.wpseo_metadesc_template;
			}

			if (desc != '') {
				desc = yst_replaceVariables(desc, function (desc) {
					desc = divHtml.text(desc).html();
					desc = yst_clean(desc);


					var len = -1;
					len = wpseoMetaboxL10n.wpseo_meta_desc_length - desc.length;

					if (len < 0)
						len = '<span class="wrong">' + len + '</span>';
					else
						len = '<span class="good">' + len + '</span>';

					jQuery('#' + wpseoMetaboxL10n.field_prefix + 'metadesc-length').html(len);

					desc = yst_trimDesc(desc);
					desc = yst_boldKeywords(desc, false);
					// Clear the autogen description.
					snippet.find('.desc span.autogen').html('');
					// Set our new one.
					snippet.find('.desc span.content').html(desc);

					yst_testFocusKw();
				});
			} else {
				// Clear the generated description
				snippet.find('.desc span.content').html('');
				yst_testFocusKw();

				if (lbmnData.currentPageContent.length) {
					desc = lbmnData.currentPageContent;
					desc = yst_clean(desc);
				}

				var focuskw = yst_escapeFocusKw(jQuery.trim(jQuery('#' + wpseoMetaboxL10n.field_prefix + 'focuskw').val()));
				if (focuskw != '') {
					var descsearch = new RegExp(focuskw, 'gim');
					if (desc.search(descsearch) != -1 && desc.length > wpseoMetaboxL10n.wpseo_meta_desc_length) {
						desc = desc.substr(desc.search(descsearch), wpseoMetaboxL10n.wpseo_meta_desc_length);
					} else {
						desc = desc.substr(0, wpseoMetaboxL10n.wpseo_meta_desc_length);
					}
				} else {
					desc = desc.substr(0, wpseoMetaboxL10n.wpseo_meta_desc_length);
				}
				desc = yst_boldKeywords(desc, false);
				desc = yst_trimDesc(desc);
				snippet.find('.desc span.autogen').html(desc);
			}

		}

	}

}

