<?php
add_filter ( 'cron_schedules', 'wp_rankie_twice_a_minute' );
function wp_rankie_twice_a_minute($schedules) {
	
	// Adds once weekly to the existing schedules.
	$schedules ['twice_a_minute'] = array (
			'interval' => 120,
			'display' => __ ( 'Once every 2 minutes' ) 
	);
	return $schedules;
}

if (! wp_next_scheduled ( 'wp_rankie_rank_hook' )) {
	
	$wp_rankie_options=get_option('wp_rankie_options' , array());
	
	if(  in_array('OPT_EXTERNAL', $wp_rankie_options)){
		return;
	}
	
	wp_schedule_event ( time (), 'twice_a_minute', 'wp_rankie_rank_hook' );
	
}

add_action ( 'wp_rankie_rank_hook', 'wp_rankie_update_rank_function_wrap_hook' );

function wp_rankie_update_rank_function_wrap(){
	
	$wp_rankie_options=get_option('wp_rankie_options' , array('OPT_AUTO_UPDATE',));
	$disable_untill= get_option('wp_rankie_disabled_till','1401802146');
	
	if(time('now') < $disable_untill ){
		wp_rankie_log_new('Cron','Rank update via UI is working cron will sleep');
		return;
	}
 	
	if( ! in_array('OPT_AUTO_UPDATE', $wp_rankie_options)){
		return;
	}
	
	//wrap function that run updater no less than 120 minute
	$now=time('now');
	$lastRun =  get_option('wp_rankie_last_run') ;
	$diff = $now - $lastRun ;
	
	 
	
	if($diff > 1 ) {
		
		//run it now
		update_option('wp_rankie_last_run', $now);
		
		wp_rankie_update_rank_function();
		
	}else{
		echo 'Cron Last run from '.$diff ;
	}
	
	
}

function wp_rankie_update_rank_function_wrap_hook(){
	
	$wp_rankie_options=get_option('wp_rankie_options' , array('OPT_AUTO_UPDATE'));
	
	if(  in_array('OPT_EXTERNAL', $wp_rankie_options)){
		return;
	}
	
	 
	wp_rankie_update_rank_function_wrap();
	
	
}

function wp_rankie_update_rank_function() {

 	echo 'Lets update one rank ';
 	
 	$currentTime = time('now');
 	$yesterDay = $currentTime - ( 24 * 60 * 60 );
 	
 	//get records that was not updated from yesterday 
    
 	//keywords that are ranking have higher periority
 	global $wpdb;
    $query="SELECT * FROM wp_rankie_keywords where date_updated < $yesterDay and keyword_rank > 0 order by keyword_rank ASC limit 1";
    $rows=$wpdb->get_results($query);
    
    
    if(count($rows) == 0 ){
    	$query="SELECT * FROM wp_rankie_keywords where date_updated < $yesterDay and keyword_rank = 0 limit 1";
    	$rows=$wpdb->get_results($query);
    	
    }
    
 	 
    if(count($rows) > 0 ){
    	
    	wp_rankie_log_new('Cron Trigger', 'Cron is just run and there is eligible keywords rank update');
    	
    	//good we have a record to update 
    	$row = $rows[0];
    	
    	echo ' updating record '.$row->keyword_id;
    	
    	$rank= wp_rankie_fetch_rank ( $row->keyword_id );
    	
    	print_r($rank);
    	if (isset ($rank['status']) ){
    		if($rank['status'] == 'success'  ){
    			echo ' .. updated successfully .';
    			
    		}
    		
    	}else{
    		echo ' .. no valid reply ';
    	}
    	
    }
	
}