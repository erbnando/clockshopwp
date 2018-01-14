<?php 

function create_events_pt() {
	register_post_type( 'events',
		array(
			'labels' => array(
				'name' => 'Events',
				'menu_name' => 'Events',
				'singular_name' => 'Event',
				'all_items' => 'All Events',
				'add_new' => 'Add New Event',
				'add_new_item' => 'Add New Event',
				'edit' => 'Edit',
				'edit_item' => 'Edit Event',
				'new_item' => 'New Event',
				'view' => 'View',
				'view_item' => 'View Event',
				'search_items' => 'Search Events',
				'not_found' => 'No Events found',
				'not_found_in_trash' => 'No Events found in Trash',
				'parent' => 'Parent'
			),
	        'taxonomies' => array('projects'),
			'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'revisions', 'page-attributes', 'excerpt' ),
			'public' => true,
			'has_archive' => true,
			'show_in_menu' => true,
			'hierarchical' => true,
			'menu_icon' => 'dashicons-calendar',
		)
	);
}
add_action( 'init', 'create_events_pt' );

// Format Dates

function spellerberg_sp_date($postid, $format='') {
	echo spellerberg_return_sp_date($postid, $format);
}

function spellerberg_return_sp_date($postid, $format='') {

	$startYear = date('Y', strtotime(get_field('_EventStartDate' , $postid)));
	$endYear = date('Y', strtotime(get_field('_EventEndDate' , $postid)));

	$startMonth = date('F', strtotime(get_field('_EventStartDate' , $postid)));
	$endMonth = date('F', strtotime(get_field('_EventEndDate' , $postid)));

	$startDay = date('j', strtotime(get_field('_EventStartDate' , $postid)));
	$endDay = date('j', strtotime(get_field('_EventEndDate' , $postid)));

	$weekday = date('D', strtotime(get_field('_EventStartDate' , $postid)));

	$startTime = date('g', strtotime(get_field('_EventStartDate' , $postid)));
	$endTime = date('g', strtotime(get_field('_EventEndDate' , $postid)));

	$s_hour = date('g', strtotime(get_field('_EventStartDate' , $postid)));
	$s_min = date('i', strtotime(get_field('_EventStartDate' , $postid)));
	$s_am = ' ' . date('a', strtotime(get_field('_EventEndDate' , $postid)));
	
	$e_hour = date('g', strtotime(get_field('_EventStartDate' , $postid)));
	$e_min = date('i', strtotime(get_field('_EventStartDate' , $postid)));
	$e_am = ' ' . date('a', strtotime(get_field('_EventEndDate' , $postid)));

	return spellerberg_show_event_datetime( $startYear, $endYear, $startMonth, $endMonth, $startDay, $endDay, $startTime, $endTime, $weekday, $s_hour, $s_min, $s_am, $e_hour, $e_min, $e_am, $format );

}

function spellerberg_show_event_datetime ( $startYear, $endYear, $startMonth, $endMonth, $startDay, $endDay, $startTime, $endTime, $weekday, $s_hour, $s_min, $s_am, $e_hour, $e_min, $e_am, $format) {

	$output = '';


	if ( $startYear == $endYear && $startMonth == "Jan" && $endMonth == "Dec" && $startDay == "1" && $endDay == "31") {

		// Is a year-long program
		$output .= 'Annual program';

	} else {

		if ( $startYear !== $endYear ) {

			//Different Years
			$output .= $startMonth . ' ' . $startDay;
			if ( $format != 'noyears' ) $output .= ', ' . $startYear;
			$output .= ' &ndash; ' . $endMonth . ' ' . $endDay . ', ' . $endYear;

		} else {

			//Same Years
			if ($startMonth !== $endMonth) {

				// Different Months
				$output .= $startMonth . ' ' . $startDay . ' &ndash; ' . $endMonth . ' ' . $endDay;
				if ( $format != 'noyears' ) $output .= ', ' . $endYear;


			} else {
				// Same Months
				if ($startDay !== $endDay) {
					// Different Day
					$output .= $startMonth . ' ' . $startDay . '&ndash;' . $endDay;
					if ( $format != 'noyears' ) $output .= ', ' . $endYear;
				} else {
					// Same Day
					$output .= $startMonth . ' ' . $startDay;
					if ( $format != 'noyears' ) $output .= ', ' . $startYear;
				}
			}
		}


		if ( $s_hour == '12' && $s_min == '00' && $s_am == ' am' && $e_hour == '11' && $e_min == '59') {
			// Is All Day, no output	
		} else {
			// Not All Day

			// Set PM, and also output format
			if ( $s_am == " am" ) :
				$s_am = "am";
			else :
				$s_am = "pm";		
			endif;

			if ( $e_am == " am" ) :
				$e_am = "am";
			else :
				$e_am = "pm";		
			endif;

			if ( $s_min == '00' ) {
				if($s_hour == "12"){
					if($s_am == "am"){
						$output .= ", Midnight";
					}else{
						$output .= ", Noon";
					}
				}else{
					$output .= ' @ ' . $s_hour;
					if ( $s_am !== $e_am ) {
						// Different AM
						$output .= '' . $s_am;
					}elseif ( $e_hour == "12" && $e_min == '00' ) {
						// End time is midnight or noon
						$output .= '' . $s_am;
					}

				}
			} else {
				$output .= ' @ ' . $s_hour . ':' . $s_min;
				if ( $s_am !== $e_am ) {
					// Different AM
					$output .= ' ' . $s_am;
				}
			}

			if ( $e_min == '00' ) {
				if($e_hour == "12"){
					if($e_am == "am"){
						$output .= "&ndash;Midnight";
					}else{
						$output .= "&ndash;Noon";
					}
				}else{
					$output .= '&ndash;' . $e_hour;
					$output .= $e_am;
				}
			} else {
				$output .= '&ndash;' . $e_hour . ':' . $e_min;
				$output .= $e_am;
			}

		}

	}

	//if ( $recurrence_text != '' ) $output .= '<br />' . $recurrence_text;

	return $output;
	
}

?>