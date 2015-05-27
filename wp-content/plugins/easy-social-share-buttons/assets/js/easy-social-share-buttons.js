
jQuery(document).ready(function($){

	var essb_cached = {};
	
	jQuery.fn.essb_get_counters = function(){
		return this.each(function(){
			
			var plugin_url 		= $(this).find('.essb_info_plugin_url').val();
			var url 			= $(this).find('.essb_info_permalink').val();
			var counter_pos     = $(this).find('.essb_info_counter_pos').val();
			var fb_value        = $(this).find('.essb_fb_total_count').val();
			var twitter_url     = $(this).find('.essb_info_permalink_twitter').val();
			var counter_admin   = $(this).find('.essb_counter_ajax').val();
			var post_self_count_id = $(this).find('.essb_info_permalink').attr("post-id");
			
			var key_base = url+"|"+post_self_count_id+"|";
			//alert(counter_admin);
			// tetsing
			//url = "http://google.com";
			
			var $twitter 	= $(this).find('.essb_link_twitter');
			var $linkedin 	= $(this).find('.essb_link_linkedin');
			var $delicious 	= $(this).find('.essb_link_delicious');
			var $facebook 	= $(this).find('.essb_link_facebook');
			var $pinterest 	= $(this).find('.essb_link_pinterest');
			var $google 	= $(this).find('.essb_link_google');
			var $stumble 	= $(this).find('.essb_link_stumbleupon');
			var $vk         = $(this).find('.essb_link_vk');
			var $reddit     = $(this).find('.essb_link_reddit');
			var $del     = $(this).find('.essb_link_del');
			var $buffer     = $(this).find('.essb_link_buffer');
			var $love     = $(this).find('.essb_link_love');
			var $ok     = $(this).find('.essb_link_ok');
			var $mwp     = $(this).find('.essb_link_mwp');
			var $xing     = $(this).find('.essb_link_xing');
			var $pocket     = $(this).find('.essb_link_pocket');

			var $print     = $(this).find('.essb_link_print');
			var $mail     = $(this).find('.essb_link_mail');

			
			var $twitter_inside = $twitter.find('.essb_network_name');
			var $linkedin_inside = $linkedin.find('.essb_network_name');
			var $delicious_inside = $delicious.find('.essb_network_name');
			var $facebook_inside = $facebook.find('.essb_network_name');
			var $pinterest_inside = $pinterest.find('.essb_network_name');
			var $google_inside = $google.find('.essb_network_name');
			var $stumble_inside = $stumble.find('.essb_network_name');
			var $vk_inside = $vk.find('.essb_network_name');
			var $reddit_inside = $reddit.find('.essb_network_name');
			var $del_inside = $del.find('.essb_network_name');
			var $buffer_inside = $buffer.find('.essb_network_name');
			var $love_inside = $love.find('.essb_network_name');
			var $ok_inside = $ok.find('.essb_network_name');
			var $mwp_inside = $mwp.find('.essb_network_name');
			var $xing_inside = $xing.find('.essb_network_name');
			var $pocket_inside = $pocket.find('.essb_network_name');
			
			var $print_inside = $print.find('.essb_network_name');
			var $mail_inside = $mail.find('.essb_network_name');
			
			
			var twitter_url		= "https://cdn.api.twitter.com/1/urls/count.json?url=" + twitter_url + "&callback=?"; 
			//
			var delicious_url	= "http://feeds.delicious.com/v2/json/urlinfo/data?url=" + url + "&callback=?" ;
			// 
			var linkedin_url	= "https://www.linkedin.com/countserv/count/share?format=jsonp&url=" + url + "&callback=?";
			// 
			var pinterest_url   = "https://api.pinterest.com/v1/urls/count.json?callback=?&url=" + url;
			// 
			var facebook_url	= "https://graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22"+url+"%22";
			// 
			var google_url		= plugin_url+"/public/get-noapi-counts.php?nw=google&url=" + url;
			var stumble_url		= plugin_url+"/public/get-noapi-counts.php?nw=stumble&url=" + url;
			var vk_url  = plugin_url+"/public/get-noapi-counts.php?nw=vk&url=" + url;
			
			var reddit_url   = plugin_url+"/public/get-noapi-counts.php?nw=reddit&url=" + url;
			var del_url   = "http://feeds.delicious.com/v2/json/urlinfo/data?url="+url+"&amp;callback=?"
			var buffer_url   = "https://api.bufferapp.com/1/links/shares.json?url="+url+"&callback=?";
			
			var ok_url   = plugin_url+"/public/get-noapi-counts.php?nw=ok&url=" + url;
			var mwp_url   = plugin_url+"/public/get-noapi-counts.php?nw=mwp&url=" + url;
			var xing_url   = plugin_url+"/public/get-noapi-counts.php?nw=xing&url=" + url;
			var pocket_url   = plugin_url+"/public/get-noapi-counts.php?nw=pocket&url=" + url;

			var love_url   = essb_count_data.ajax_url+"?action=essb_counts&nw=love&url=" + ((typeof(post_self_count_id) != "undefined") ? post_self_count_id : "");
			var mail_url   = essb_count_data.ajax_url+"?action=essb_counts&nw=mail&url=" + ((typeof(post_self_count_id) != "undefined") ? post_self_count_id : "");
			var print_url   = essb_count_data.ajax_url+"?action=essb_counts&nw=print&url=" + ((typeof(post_self_count_id) != "undefined") ? post_self_count_id : "");

			if (counter_admin == "true") {
				google_url = essb_count_data.ajax_url+"?action=essb_counts&nw=google&url=" + url;
				stumble_url = essb_count_data.ajax_url+"?action=essb_counts&nw=stumble&url=" + url;
				vk_url = essb_count_data.ajax_url+"?action=essb_counts&nw=vk&url=" + url;
				reddit_url   = essb_count_data.ajax_url+"?action=essb_counts&nw=reddit&url=" + url;
				ok_url = essb_count_data.ajax_url+"?action=essb_counts&nw=ok&url=" + url;
				mwp_url = essb_count_data.ajax_url+"?action=essb_counts&nw=mwp&url=" + url;
				xing_url = essb_count_data.ajax_url+"?action=essb_counts&nw=xing&url=" + url;
				pocket_url = essb_count_data.ajax_url+"?action=essb_counts&nw=pocket&url=" + url;
			}
			
			//var twitter_url		= plugin_url+"/public/get-count.php?nw=twitter&url=" + url;
			//var pinterest_url   = plugin_url+"/public/get-count.php?nw=pinterest&url=" + url;

			
			function shortenNumber(n) {
				    if ('number' !== typeof n) n = Number(n);
				    var sgn      = n < 0 ? '-' : ''
				      , suffixes = ['k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y']
				      , overflow = Math.pow(10, suffixes.length * 3 + 3)
				      , suffix, digits;
				    n = Math.abs(Math.round(n));
				    if (n < 1000) return sgn + n;
				    if (n >= 1e100) return sgn + 'many';
				    if (n >= overflow) return (sgn + n).replace(/(\.\d*)?e\+?/i, 'e'); // 1e24
				 
				    do {
				      n      = Math.floor(n);
				      suffix = suffixes.shift();
				      digits = n % 1e6;
				      n      = n / 1000;
				      if (n >= 1000) continue; // 1M onwards: get them in the next iteration
				      if (n >= 10 && n < 1000 // 10k ... 999k
				       || (n < 10 && (digits % 1000) < 100) // Xk (X000 ... X099)
				         )
				        return sgn + Math.floor(n) + suffix;
				      return (sgn + n).replace(/(\.\d).*/, '$1') + suffix; // #.#k
				    } while (suffixes.length)
				    return sgn + 'many';
				  }

			function counter_display($counter_pos, $element, $element_inside, $cnt) {
				if (counter_pos == "right") {
					$element.append('<span class="essb_counter_right" cnt="' + $cnt + '">' + shortenNumber($cnt) + '</span>');
				}
				else if (counter_pos == "inside") {
					$element_inside.html('<span class="essb_counter_inside" cnt="' + $cnt + '">' + shortenNumber($cnt) + '</span>');
				}
				else if (counter_pos == "insidename") {
					$element_inside.append('<span class="essb_counter_insidename" cnt="' + $cnt + '">' + shortenNumber($cnt) + '</span>');
				}
				else if (counter_pos == "hidden") {
					$element.append('<span class="essb_counter_hidden" cnt="' + $cnt + '"></span>');
				}
				else {
					$element.prepend('<span class="essb_counter" cnt="' + $cnt + '">' + shortenNumber($cnt) + '</span>');
				}
				
			}
			
			if ( $twitter.length ) {
				$.getJSON(twitter_url)
					.done(function(data){
						counter_display(counter_pos, $twitter, $twitter_inside, data.count);						
					});
			}
			if ( $linkedin.length ) {
				$.getJSON(linkedin_url)
					.done(function(data){
						counter_display(counter_pos, $linkedin, $linkedin_inside, data.count);						
					});
			}
			if ( $pinterest.length ) {
				$.getJSON(pinterest_url)
					.done(function(data){
						counter_display(counter_pos, $pinterest, $pinterest_inside, data.count);						
					});
			}
			if ( $google.length ) {
				$.getJSON(google_url)
					.done(function(data){
						counter_display(counter_pos, $google, $google_inside, data.count);						
				})
			}
			if ( $stumble.length ) {
				$.getJSON(stumble_url)
					.done(function(data){
						counter_display(counter_pos, $stumble, $stumble_inside, data.count);						

					})
			}
			if ( $facebook.length ) {
				$.getJSON(facebook_url)
					.done(function(data){
						if (fb_value == 'true') {
							counter_display(counter_pos, $facebook, $facebook_inside, data.data[0].total_count);
						}
						else {
							counter_display(counter_pos, $facebook, $facebook_inside, data.data[0].share_count);	
						}
					});
			}
			if ( $del.length ) {

				$.getJSON(delicious_url)
					.done(function(data){
						try {
							counter_display(counter_pos, $del, $del_inside, data[0].total_posts);
						}
						catch (e) {
							
						}
					});
			}
			
			if ( $buffer.length ) {

				$.getJSON(buffer_url)
					.done(function(data){
						counter_display(counter_pos, $buffer, $buffer_inside, data.shares);
					});
			}			
			if ( $vk.length ) {
				$.getJSON(vk_url)
					.done(function(data){
						counter_display(counter_pos, $vk, $vk_inside, data.count);
					});
			}
			
			if ( $love.length ) {
				$.getJSON(love_url)
					.done(function(data){
						counter_display(counter_pos, $love, $love_inside, data.count);
					});
			}
						
			if ( $ok.length ) {
				$.getJSON(ok_url)
					.done(function(data){
						var counter_value = data.count;
						counter_display(counter_pos, $ok, $ok_inside, counter_value);
					});
			}

			if ( $mwp.length ) {
				$.getJSON(mwp_url)
					.done(function(data){
						var counter_value = data.count;
						counter_display(counter_pos, $mwp, $mwp_inside, counter_value);
					});
			}			
			if ( $xing.length ) {
				$.getJSON(xing_url)
					.done(function(data){
						var counter_value = data.count;
						counter_display(counter_pos, $xing, $xing_inside, counter_value);
					});
			}			
			if ( $pocket.length ) {
				$.getJSON(pocket_url)
					.done(function(data){
						var counter_value = data.count;
						counter_display(counter_pos, $pocket, $pocket_inside, counter_value);
					});
			}			
			if ( $print.length ) {
				$.getJSON(print_url)
					.done(function(data){
						counter_display(counter_pos, $print, $print_inside, data.count);
					});
			}

			if ( $mail.length ) {
				$.getJSON(mail_url)
					.done(function(data){
						counter_display(counter_pos, $mail, $mail_inside, data.count);
					});
			}
			
			if ( $reddit.length ) {				
				$.getJSON(reddit_url)
					.done(function(data){											
						counter_display(counter_pos, $reddit, $reddit_inside, data.count);

					})
			}			
		});
	}; 

	jQuery.fn.essb_update_counters = function(){
		return this.each(function(){

			var $group			= $(this);
			var $total_count 	= $group.find('.essb_totalcount');
			var $total_count_nb = $total_count.find('.essb_t_nb');
			var $total_count_item = $group.find('.essb_totalcount_item');
			
			var $total_counter_hidden = $total_count_item.attr('essb-hide-till') || "";
			var total_text = $total_count.attr('title');
			var total_text_after = $total_count.attr('title_after');
			if (typeof(total_text) == "undefined") { total_text = ""; }
			if (typeof(total_text_after) == "undefined") { total_text_after = ""; }
			$total_count.prepend('<span class="essb_total_text">'+total_text+'</span>');
			
			function count_total() {
				var total = 0;
				var counter_pos     = $('.essb_info_counter_pos').val();
				
				var exist_data_counter_pos = $total_count_item.attr('data-counter-pos') || "";
//				alert(exist_data_counter_pos);
				if (exist_data_counter_pos != '') {
					counter_pos = exist_data_counter_pos;
				}
				//alert(counter_pos);
				if (counter_pos == "right") {
					$group.find('.essb_counter_right').each(function(){
						total += parseInt($(this).attr('cnt'));		
						
						var value = parseInt($(this).attr('cnt'));
						
						if (!$total_count_nb) {
						value = shortenNumber(value);
						$(this).text(value);
					}
						//alert(shortenNumber(total));
					});
					
				}
				else if (counter_pos == "inside") {
					$group.find('.essb_counter_inside').each(function(){
						total += parseInt($(this).attr('cnt'));		
						
						var value = parseInt($(this).attr('cnt'));
						
						if (!$total_count_nb) {
						value = shortenNumber(value);
						$(this).text(value);
					}
						//alert(shortenNumber(total));
					});
					
				}
				else if (counter_pos == "insidename") {
					$group.find('.essb_counter_insidename').each(function(){
						total += parseInt($(this).attr('cnt'));		
						
						var value = parseInt($(this).attr('cnt'));
						
						if (!$total_count_nb) {
						value = shortenNumber(value);
						$(this).text(value);
					}
						//alert(shortenNumber(total));
					});
					
				}				
				else if (counter_pos == "hidden") {
					$group.find('.essb_counter_hidden').each(function(){
						total += parseInt($(this).attr('cnt'));		
						
						var value = parseInt($(this).attr('cnt'));
						
						if (!$total_count_nb) {
						value = shortenNumber(value);
						$(this).text(value);
					}
						//alert(shortenNumber(total));
					});
					
				}
				else {
					$group.find('.essb_counter').each(function(){
						total += parseInt($(this).attr('cnt'));		
					
						var value = parseInt($(this).attr('cnt'));
					
						if (!$total_count_nb) {
							value = shortenNumber(value);
							$(this).text(value);
						}
					//alert(shortenNumber(total));
					});
				}
				$total_count_nb.text(shortenNumber(total)+total_text_after);
				
				// show total counter when value is reached
				if ($total_counter_hidden != '') {
					if (parseInt($total_counter_hidden) <= total) {
						$total_count_item.show();
					}
				}
 			}
			
			  function shortenNumber(n) {
				    if ('number' !== typeof n) n = Number(n);
				    var sgn      = n < 0 ? '-' : ''
				      , suffixes = ['k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y']
				      , overflow = Math.pow(10, suffixes.length * 3 + 3)
				      , suffix, digits;
				    n = Math.abs(Math.round(n));
				    if (n < 1000) return sgn + n;
				    if (n >= 1e100) return sgn + 'many';
				    if (n >= overflow) return (sgn + n).replace(/(\.\d*)?e\+?/i, 'e'); // 1e24
				 
				    do {
				      n      = Math.floor(n);
				      suffix = suffixes.shift();
				      digits = n % 1e6;
				      n      = n / 1000;
				      if (n >= 1000) continue; // 1M onwards: get them in the next iteration
				      if (n >= 10 && n < 1000 // 10k ... 999k
				       || (n < 10 && (digits % 1000) < 100) // Xk (X000 ... X099)
				         )
				        return sgn + Math.floor(n) + suffix;
				      return (sgn + n).replace(/(\.\d).*/, '$1') + suffix; // #.#k
				    } while (suffixes.length)
				    return sgn + 'many';
				  }
			setInterval(count_total, 1200);

		});
	}; 
	
	jQuery.fn.essb_update_total_counters = function(){
		return this.each(function(){
			var $network_list = $(this).attr('data-network-list');
			var $networkContainer = $network_list.split(",");
			var $value_element = $(this).find('.essb-total-value');
			var $full_number = $(this).attr('data-full-number');
			var $root = $(this);
			
			function update_total() {
				var current_total = 0;
				for (var i=0;i<$networkContainer.length;i++) {
					var $singleNetwork = $networkContainer[i];
					
					var value = $root.attr('data-'+$singleNetwork);
					if (typeof(value) == "undefined") { value = 0; }
					
					if (Number(value) <= 0) { value = 0; }
					
					//console.log($singleNetwork + ' | ' + value);
					current_total += parseInt(value);
					
				}
				
				if ($full_number == 'true') {
					$value_element.text(current_total);
				}
				else {
					$value_element.text(shortenNumber(current_total));
				}
			}
			
			
			function shortenNumber(n) {
			    if ('number' !== typeof n) n = Number(n);
			    var sgn      = n < 0 ? '-' : ''
			      , suffixes = ['k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y']
			      , overflow = Math.pow(10, suffixes.length * 3 + 3)
			      , suffix, digits;
			    n = Math.abs(Math.round(n));
			    if (n < 1000) return sgn + n;
			    if (n >= 1e100) return sgn + 'many';
			    if (n >= overflow) return (sgn + n).replace(/(\.\d*)?e\+?/i, 'e'); // 1e24
			 
			    do {
			      n      = Math.floor(n);
			      suffix = suffixes.shift();
			      digits = n % 1e6;
			      n      = n / 1000;
			      if (n >= 1000) continue; // 1M onwards: get them in the next iteration
			      if (n >= 10 && n < 1000 // 10k ... 999k
			       || (n < 10 && (digits % 1000) < 100) // Xk (X000 ... X099)
			         )
			        return sgn + Math.floor(n) + suffix;
			      return (sgn + n).replace(/(\.\d).*/, '$1') + suffix; // #.#k
			    } while (suffixes.length)
			    return sgn + 'many';
			  }
			
			setInterval(update_total, 1200);
		});
	};
 
	jQuery.fn.essb_total_counters = function(){
		return this.each(function(){
			var $network_list = $(this).attr('data-network-list');
			var $url = $(this).attr('data-url');
			var $facebook_total = $(this).attr('data-fb-total');
			var $counter_url = $(this).attr('data-counter-url');
			var $ajax_counter = $(this).attr('data-ajax-url');
			var $force_ajax = $(this).attr('data-force-ajax');
			var $post_id =  $(this).attr('data-post');
			
			//var $root = $(this).find('.essb-total-value');
			var $value_element = $(this).find('.essb-total-value');
			var isAjax = false;
			if ($force_ajax == 'true') {
				isAjax = true;				
			}
			
			//alert($network_list);
			var $root = $(this);
			
			var twitter_url		= "https://cdn.api.twitter.com/1/urls/count.json?url=" + $url + "&callback=?"; 
			var delicious_url	= "http://feeds.delicious.com/v2/json/urlinfo/data?url=" + $url + "&callback=?" ;
			var linkedin_url	= "https://www.linkedin.com/countserv/count/share?format=jsonp&url=" + $url + "&callback=?";
			var pinterest_url   = "https://api.pinterest.com/v1/urls/count.json?callback=?&url=" + $url;
			var facebook_url	= "https://graph.facebook.com/fql?q=SELECT%20like_count,%20total_count,%20share_count,%20click_count,%20comment_count%20FROM%20link_stat%20WHERE%20url%20=%20%22"+$url+"%22";
			var google_url		= $counter_url+"/public/get-noapi-counts.php?nw=google&url=" + $url;
			var stumble_url		= $counter_url+"/public/get-noapi-counts.php?nw=stumble&url=" + $url;
			var vk_url  = $counter_url+"/public/get-noapi-counts.php?nw=vk&url=" + $url;					
			
			var reddit_url   = $counter_url+"/public/get-noapi-counts.php?nw=reddit&url=" + $url;
			var del_url   = "http://feeds.delicious.com/v2/json/urlinfo/data?url="+$url+"&amp;callback=?"
			var buffer_url   = "https://api.bufferapp.com/1/links/shares.json?url="+$url+"&callback=?";
			var ok_url   = $counter_url+"/public/get-noapi-counts.php?nw=ok&url=" + $url;
			var mwp_url   = $counter_url+"/public/get-noapi-counts.php?nw=mwp&url=" + $url;

			var love_url   = $ajax_counter+"?action=essb_counts&nw=love&url=" + ((typeof($post_id) != "undefined") ? $post_id : "");
			var mail_url   = $ajax_counter+"?action=essb_counts&nw=mail&url=" + ((typeof($post_id) != "undefined") ? $post_id : "");
			var print_url   = $ajax_counter+"?action=essb_counts&nw=print&url=" + ((typeof($post_id) != "undefined") ? $post_id : "");

			if (isAjax) {
				google_url = $ajax_counter+"?action=essb_counts&nw=google&url=" + $url;
				stumble_url = $ajax_counter+"?action=essb_counts&nw=stumble&url=" + $url;
				vk_url = $ajax_counter+"?action=essb_counts&nw=vk&url=" + $url;
				reddit_url   = $ajax_counter+"?action=essb_counts&nw=reddit&url=" + $url;
				ok_url   = $ajax_counter+"?action=essb_counts&nw=ok&url=" + $url;
				mwp_url   = $ajax_counter+"?action=essb_counts&nw=mwp&url=" + $url;
			}

			var $networkContainer = $network_list.split(",");
						
			for (var i=0;i<$networkContainer.length;i++) {
				var $singleNetwork = $networkContainer[i];
				
				var append_attr_value = 'data-'+$singleNetwork;
				//alert(append_attr_value);				
				
				switch ($singleNetwork) {
					case "facebook":
						$.getJSON(facebook_url)
							.done(function(data){
									if (typeof(data) != 'undefined') {
									if ($facebook_total == 'true') {
										$root.attr('data-facebook', data.data[0].total_count);
									}
									else {
										$root.attr('data-facebook', data.data[0].share_count);
									}
									}
							});
					break;
					case "twitter":
						$.getJSON(twitter_url)
						.done(function(data){
							if (typeof(data) != "undefined") {
								$root.attr('data-twitter', data.count);
								//console.log(append_attr_value + '- '+data.count);
							}
						});

						break;				
					case "linkedin":
						$.getJSON(linkedin_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-linkedin', data.count);							
						});

						break;				
					case "pinterest":
						$.getJSON(pinterest_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-pinterest', data.count);							
						});

						break;				
					case "google":
						$.getJSON(google_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-google', data.count);							
						});

						break;				
					case "stumbleupon":
						$.getJSON(stumble_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-stumbleupon', data.count);							
						});

						break;				
					case "del":
						$.getJSON(delicious_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr(append_attr_value, data[0].total_posts);							
						});

						break;				
					case "buffer":
						$.getJSON(buffer_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-buffer', data.shares);							
						});

						break;				
					case "vk":
						$.getJSON(vk_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-vk', data.count);							
						});

						break;				
					case "ok":
						$.getJSON(ok_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-ok', data.count);							
						});

						break;				
					case "mwp":
						$.getJSON(mwp_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-mwp', data.count);							
						});

						break;				
					case "love":
						$.getJSON(love_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-love', data.count);							
						});

					case "print":
						$.getJSON(print_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-print', data.count);							
						});

						break;

					case "mail":
						$.getJSON(mail_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-mail', data.count);							
						});
						
					case "reddit":
						$.getJSON(reddit_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-reddit', data.count);							
						});
						break;				
				}
			}
			//update_total($(this), $networkContainer);
			
			
 		});
	}
	
	$('.essb-total').essb_total_counters();
	$('.essb-total').essb_update_total_counters();
	
	$('.essb_links.essb_counters').essb_get_counters();
	$('.essb_counters .essb_links_list').essb_update_counters();
});