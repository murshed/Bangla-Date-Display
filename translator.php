<?php

defined( 'ABSPATH' )or die( 'Stop! You can not do this!' );

if ( !is_admin() ) { // if not admin

	if ( isset( $bddp_options[ 'trans_dt' ] ) == "1" ) {
		if ( function_exists( 'wp_date' ) ) {
			add_filter( 'wp_date', 'en_to_bn', 10, 2 ); // wp 5.3 or later
		} else {
			add_filter( 'date_i18n', 'en_to_bn', 10, 2 ); // wp 5.2 or earlier
		}
	}

	if ( isset( $bddp_options[ 'trans_cmnt' ] ) == "1" ) {
		add_filter( 'comments_number', 'en_to_bn' );
		add_filter( 'get_comment_count', 'en_to_bn' );
	}

	if ( isset( $bddp_options[ 'trans_num' ] ) == "1" ) {
		if ( !is_admin() ) {
			add_filter( 'number_format_i18n', 'en_to_bn', 10, 1 );
		}
	}

}

function en_to_bn( $str ) {
	$enMonth = array(
		'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
		'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
	);

	$enWeeks = array( 'Saturday',
		'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday',
		'Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'
	);

	$bnMonth = array( 'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর',
		'জানু', 'ফেব্রু', 'মার্চ', 'এপ্রি', 'মে', 'জুন', 'জুলা', 'আগ', 'সেপ্টে', 'অক্টো', 'নভে', 'ডিসে'
	);

	$bnWeeks = array(
		'শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', 'বুধবার', 'বৃহস্পতিবার', 'শুক্রবার',
		'শনি', 'রবি', 'সোম', 'মঙ্গল', 'বুধ', 'বৃহঃ', 'শুক্র'
	);

	$mergeA1 = array_merge( $enMonth, $enWeeks );
	$mergeA2 = array_merge( $bnMonth, $bnWeeks );

	array_push( $mergeA1, 'am', 'pm', 'st', 'th', 'nd', 'rd', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	array_push( $mergeA2, 'পূর্বাহ্ণ', 'অপরাহ্ণ', '', '', '', '', '০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯' );

	return str_ireplace( $mergeA1, $mergeA2, $str );
}

?>