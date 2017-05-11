<?php

/*
	Birdeye Wp API
*/

if (!defined( 'ABSPATH' )) {
	exit;
}

function get_birdeye_reviews($type, $atts) {
	return Birdeye_Shortcodes::get_reviews( $type, $atts );
}

function birdeye_wp_summary( $atts ) {
	Birdeye_Shortcodes::get_summary($atts);	
}



