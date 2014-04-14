<?php // Conditionally load fontface stylesheets
function crucible_loadfonts() {
	$fontdir = get_template_directory_uri(). '/inc/fonts/';
	$needle1 = get_option('smartestthemes_heading_font');
	$needle2 = get_option('smartestthemes_logo_font');
	$needle3 = get_option('smartestthemes_body_font');
	$needle4 = get_option('smartestthemes_tagline_font');
	$needle5 = get_option('smartestthemes_attention_grabber_font');

	// @new list font css output from theme options $logofonts array index [2]
	$font1 = 'qumpellkano12regular,Arial,Helvetica,sans-serif';
	$font2 = 'bebasregular,Arial,Helvetica,sans-serif';
	$font3 = 'dayposterblackregular,Arial,Helvetica,sans-serif';
	$font4 = 'forqueregular,Arial,Helvetica,sans-serif';
	$font5 = 'florante_at_lauraregular,Arial,Helvetica,sans-serif';
	$font6 = 'fontleroybrownregular,Arial,Helvetica,sans-serif';
	$font7 = 'kingthings_exeterregular,Arial,Helvetica,sans-serif';
	$font8 = 'roboto_slabregular,Arial,Helvetica,sans-serif';
	$font9 = 'roboto_slabbold,Arial,Helvetica,sans-serif';

	/* if only 1 family per font, do it this way */

	if ( in_array($font1,array($needle1, $needle2, $needle4, $needle5)) ) {
		wp_register_style('bluechip', $fontdir.'bluechip/stylesheet.css', ''); wp_enqueue_style('bluechip');
	}
	if ( in_array($font2,array($needle1, $needle2, $needle4, $needle5)) ) {
		wp_register_style('bebas_regular', $fontdir.'bebas_regular/stylesheet.css', ''); wp_enqueue_style('bebas_regular');
	}
	if ( in_array($font3,array($needle1, $needle2, $needle4, $needle5)) ) {
		wp_register_style('dayposterblack', $fontdir.'dayposterblack/stylesheet.css', ''); wp_enqueue_style('dayposterblack');
	}
	if ( in_array($font4,array($needle1, $needle2, $needle4, $needle5)) ) {
		wp_register_style('forque', $fontdir.'forque/stylesheet.css', ''); wp_enqueue_style('forque');
	}
	if ( in_array($font5,array($needle1, $needle2, $needle4, $needle5)) ) {
		wp_register_style('floranteatlaura', $fontdir.'floranteatlaura/stylesheet.css', ''); wp_enqueue_style('floranteatlaura');
	}
	if ( in_array($font6,array($needle1, $needle2, $needle4, $needle5)) ) {
		wp_register_style('fontleroybrown', $fontdir.'fontleroybrown/stylesheet.css', ''); wp_enqueue_style('fontleroybrown');
	}
	if ( in_array($font7,array($needle1, $needle2, $needle4, $needle5)) ) {
		wp_register_style('kingthingsexete', $fontdir.'kingthingsexete/stylesheet.css', ''); wp_enqueue_style('kingthingsexete');
	}
	if ( in_array($font8,array($needle1, $needle2, $needle3, $needle4, $needle5)) ) {
		wp_register_style('robotoslab_regular', $fontdir.'robotoslab_regular/stylesheet.css', ''); wp_enqueue_style('robotoslab_regular');
	}
	if ( in_array($font9,array($needle1, $needle2, $needle3, $needle4, $needle5)) ) {
		wp_register_style('robotoslab_bold', $fontdir.'robotoslab_bold/stylesheet.css', ''); wp_enqueue_style('robotoslab_bold');
	}
}
add_action( 'wp_enqueue_scripts', 'crucible_loadfonts' );

/**
 * On login screen only if no logo used.
 * This is almost same as above but only check for logo font, not all heading fonts.
 */
function crucible_logofontface() {

	if(!get_option('smartestthemes_logo')) {

		$fontdir = get_template_directory_uri(). '/inc/fonts/';
		$needle2 = get_option('smartestthemes_logo_font');
		
		/* FONT NAME VARS */
		$font1 =  'qumpellkano12regular,Arial,Helvetica,sans-serif';
		$font2 =  'bebasregular,Arial,Helvetica,sans-serif';
		$font3 =  'dayposterblackregular,Arial,Helvetica,sans-serif';
		$font4 =  'forqueregular,Arial,Helvetica,sans-serif';
		$font5 =  'florante_at_lauraregular,Arial,Helvetica,sans-serif';
		$font6 =  'fontleroybrownregular,Arial,Helvetica,sans-serif';
		$font7 =  'kingthings_exeterregular,Arial,Helvetica,sans-serif';
		$font8 =  'roboto_slabregular,Arial,Helvetica,sans-serif';
		$font9 =  'roboto_slabbold,Arial,Helvetica,sans-serif';
			
		/* if only 1 font family per font, do this way */

		if ( $font1 == $needle2 ) {
			wp_register_style('bluechip', $fontdir.'bluechip/stylesheet.css', ''); wp_enqueue_style('bluechip');
		}
		if ( $font2 == $needle2 ) {
			wp_register_style('bebas_regular', $fontdir.'bebas_regular/stylesheet.css', ''); wp_enqueue_style('bebas_regular');
		}	
		if ( $font3 == $needle2 ) {
			wp_register_style('dayposterblack', $fontdir.'dayposterblack/stylesheet.css', ''); wp_enqueue_style('dayposterblack');
		}
		if ( $font4 == $needle2 ) {
			wp_register_style('forque', $fontdir.'forque/stylesheet.css', ''); wp_enqueue_style('forque');
		}	
		if ( $font5 == $needle2 ) {
			wp_register_style('floranteatlaura', $fontdir.'floranteatlaura/stylesheet.css', ''); wp_enqueue_style('floranteatlaura');
		}	
		if ( $font6 == $needle2 ) {
			wp_register_style('fontleroybrown', $fontdir.'fontleroybrown/stylesheet.css', ''); wp_enqueue_style('fontleroybrown');
		}
		if ( $font7 == $needle2 ) {
			wp_register_style('kingthingsexete', $fontdir.'kingthingsexete/stylesheet.css', ''); wp_enqueue_style('kingthingsexete');
		}	
		if ( $font8 == $needle2 ) {
			wp_register_style('robotoslab_regular', $fontdir.'robotoslab_regular/stylesheet.css', ''); wp_enqueue_style('robotoslab_regular');
		}	
		if ( $font9 == $needle2 ) {
			wp_register_style('robotoslab_bold', $fontdir.'robotoslab_bold/stylesheet.css', ''); wp_enqueue_style('robotoslab_bold');
		}
	}
}
add_action( 'login_enqueue_scripts', 'crucible_logofontface' ); ?>