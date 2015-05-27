jQuery(document).ready(function($){
	
	jQuery.fn.essb_get_counters = function(){
		return this.each(function(){
			
			var plugin_url 		= $(this).find('.essb_info_plugin_url').val();
			var url 			= $(this).find('.essb_info_permalink').val();
			var counter_pos     = $(this).find('.essb_info_counter_pos').val();
			var fb_value        = $(this).find('.essb_fb_total_count').val();
			var counter_admin   = $(this).find('.essb_counter_ajax').val();
			
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
			
			
			var twitter_url		= "https://cdn.api.twitter.com/1/urls/count.json?url=" + url + "&callback=?"; 
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

			var love_url   = essb_count_data.ajax_url+"?action=essb_counts&nw=love&url=" + ((typeof(essb_loveyou_post_id) != "undefined") ? essb_loveyou_post_id : "");


			google_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=google&url=" + url;
			stumble_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=stumbleupon&url=" + url;
			vk_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=vk&url=" + url;
			reddit_url   = essb_count_data.ajax_url+"?action=essb_self_counts&nw=reddit&url=" + url;
			ok_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=ok&url=" + url;
			buffer_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=buffer&url=" + url;
			facebook_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=facebook&url=" + url;
			twitter_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=twitter&url=" + url;
			delicious_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=del&url=" + url;
			linkedin_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=linkedin&url=" + url;
			pinterest_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=pinterest&url=" + url;
			
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

			if ( $twitter.length ) {
				$.getJSON(twitter_url)
					.done(function(data){
						if (counter_pos == "right") {
							$twitter.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$twitter_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$twitter.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
						}
						else {
							$twitter.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						
					});
			}
			if ( $linkedin.length ) {
				$.getJSON(linkedin_url)
					.done(function(data){
						if (counter_pos == "right") {
							$linkedin.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$linkedin_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$linkedin.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
						}
						else {
							$linkedin.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
					});
			}
			if ( $pinterest.length ) {
				$.getJSON(pinterest_url)
					.done(function(data){
						if (counter_pos == "right") {
							$pinterest.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$pinterest_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$pinterest.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
						}
						else {
							$pinterest.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}						
					});
			}
			if ( $google.length ) {
				$.getJSON(google_url)
					.done(function(data){
						//var count = data.count;
						//alert(count);
						if (counter_pos == "right") {
							$google.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$google_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$google.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
						}
						else {
							$google.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}	

					})
			}
			if ( $stumble.length ) {
				$.getJSON(stumble_url)
					.done(function(data){
						if (counter_pos == "right") {
							$stumble.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$stumble_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$stumble.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
						}
						else {
							$stumble.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}	

					})
			}
			if ( $facebook.length ) {
				$.getJSON(facebook_url)
					.done(function(data){
						if (counter_pos == "right") {
							// changed to total_count from share_count
							if (fb_value == 'true') {
								$facebook.append('<span class="essb_counter_right" cnt="' + data.count  + '">' + shortenNumber(data.count ) + '</span>');
							}
							else {
								$facebook.append('<span class="essb_counter_right" cnt="' + data.count  + '">' + shortenNumber(data.count ) + '</span>');
							}
						}
						else if (counter_pos == "inside") {
							// changed to total_count from share_count
							if (fb_value == 'true') {
								$facebook_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
							}
							else {
								$facebook_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
							}
						}						
						else if (counter_pos == "hidden") {
							// changed to total_count from share_count
							if (fb_value == 'true') {
								$facebook.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
							}
							else {
								$facebook.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
							}
						}						
						else {
							if (fb_value == 'true') {
								$facebook.prepend('<span class="essb_counter" cnt="' + data.count  + '">' + shortenNumber(data.count ) + '</span>');
							}
							else {
								$facebook.prepend('<span class="essb_counter" cnt="' + data.count  + '">' + shortenNumber(data.count ) + '</span>');
							}
						}	
					});
			}
			if ( $del.length ) {

				$.getJSON(delicious_url)
					.done(function(data){
						try {
						if (counter_pos == "right") {
							$del.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$del_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$del.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
						}
						else {
							$del.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}	
						}
						catch (e) {
							
						}
					});
			}
			
			if ( $buffer.length ) {

				$.getJSON(buffer_url)
					.done(function(data){
						if (counter_pos == "right") {
							$buffer.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$buffer_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$buffer.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
						}
						else {
							$buffer.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}	
					});
			}			
			if ( $vk.length ) {
				$.getJSON(vk_url)
					.done(function(data){
						if (counter_pos == "right") {
							$vk.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$vk_inside.html('<span class="essb_counter_inside" cnt="' +  data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$vk.append('<span class="essb_counter_hidden" cnt="' +  data.count + '"></span>');
						}
						else {
							$vk.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
					});
			}
			
			if ( $love.length ) {
				$.getJSON(love_url)
					.done(function(data){
						if (counter_pos == "right") {
							$love.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$love_inside.html('<span class="essb_counter_inside" cnt="' +  data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$love.append('<span class="essb_counter_hidden" cnt="' +  data.count + '"></span>');
						}
						else {
							$love.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
					});
			}
						
			if ( $ok.length ) {
				$.getJSON(ok_url)
					.done(function(data){
						var counter_value = data.count;
						if (counter_pos == "right") {
							$ok.append('<span class="essb_counter_right" cnt="' + counter_value + '">' + shortenNumber(counter_value) + '</span>');
						}
						else if (counter_pos == "inside") {
							$ok_inside.html('<span class="essb_counter_inside" cnt="' +  counter_value + '">' + shortenNumber(counter_value) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$ok.append('<span class="essb_counter_hidden" cnt="' +  counter_value + '"></span>');
						}
						else {
							$ok.prepend('<span class="essb_counter" cnt="' + counter_value + '">' + shortenNumber(counter_value) + '</span>');
						}
					});
			}
						
			
			if ( $reddit.length ) {				
				$.getJSON(reddit_url)
					.done(function(data){											
						if (counter_pos == "right") {
							$reddit.append('<span class="essb_counter_right" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "inside") {
							$reddit_inside.html('<span class="essb_counter_inside" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}
						else if (counter_pos == "hidden") {
							$reddit.append('<span class="essb_counter_hidden" cnt="' + data.count + '"></span>');
						}
						else {
							$reddit.prepend('<span class="essb_counter" cnt="' + data.count + '">' + shortenNumber(data.count) + '</span>');
						}	

					})
			}			
		});
	}; 

	jQuery.fn.essb_update_counters = function(){
		return this.each(function(){

			var $group			= $(this);
			var $total_count 	= $group.find('.essb_totalcount');
			var $total_count_nb = $total_count.find('.essb_t_nb');
			var total_text = $total_count.attr('title');
			var total_text_after = $total_count.attr('title_after');
			$total_count.prepend('<span class="essb_total_text">'+total_text+'</span>');

			function count_total() {
				var total = 0;
				var counter_pos     = $('.essb_info_counter_pos').val();
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

			var love_url   = $ajax_counter+"?action=essb_counts&nw=love&url=" + ((typeof(essb_loveyou_post_id) != "undefined") ? essb_loveyou_post_id : "");

			google_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=google&url=" + url;
			stumble_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=stumbleupon&url=" + url;
			vk_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=vk&url=" + url;
			reddit_url   = essb_count_data.ajax_url+"?action=essb_self_counts&nw=reddit&url=" + url;
			ok_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=ok&url=" + url;
			buffer_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=buffer&url=" + url;
			facebook_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=facebook&url=" + url;
			twitter_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=twitter&url=" + url;
			delicious_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=del&url=" + url;
			linkedin_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=linkedin&url=" + url;
			pinterest_url = essb_count_data.ajax_url+"?action=essb_self_counts&nw=pinterest&url=" + url;


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
										$root.attr('data-facebook', data.count);
									}
									else {
										$root.attr('data-facebook', data.count);
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
								$root.attr(append_attr_value, data.count);							
						});

						break;				
					case "buffer":
						$.getJSON(buffer_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-buffer', data.count);							
						});

						break;				
					case "vk":
						$.getJSON(delicious_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-vk', data.count);							
						});

						break;				
					case "ok":
						$.getJSON(delicious_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-ok', data.count);							
						});

						break;				
					case "love":
						$.getJSON(love_url)
						.done(function(data){
							if (typeof(data) != "undefined")
								$root.attr('data-love', data.count);							
						});

						break;				
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
