<?php // Conditionally load fontface styles
function crucible_loadfonts() {
	global $smartestthemes_options;

	$haystack1 = empty($smartestthemes_options['heading_font']) ? '' : $smartestthemes_options['heading_font'];
	$haystack2 = empty($smartestthemes_options['logo_font']) ? '' : $smartestthemes_options['logo_font'];
	$haystack3 = empty($smartestthemes_options['body_font']) ? '' : $smartestthemes_options['body_font'];
	$haystack4 = empty($smartestthemes_options['tagline_font']) ? '' : $smartestthemes_options['tagline_font'];
	$haystack5 = empty($smartestthemes_options['att_grabber_font']) ? '' : $smartestthemes_options['att_grabber_font'];

	
	// @new list fonts from customizer.php $logo_fonts array indices
	$font1 = 'qumpellkano12regular,Arial,Helvetica,sans-serif';
	$font2 = 'bebasregular,Arial,Helvetica,sans-serif';
	$font3 = 'dayposterblackregular,Arial,Helvetica,sans-serif';
	$font4 = 'forqueregular,Arial,Helvetica,sans-serif';
	$font5 = 'florante_at_lauraregular,Garamond, Hoefler Text, Times New Roman, Times, serif';
	$font6 = 'fontleroybrownregular,Arial,Helvetica,sans-serif';
	$font7 = 'kingthings_exeterregular,Arial,Helvetica,sans-serif';
	$font8 = 'roboto_slabregular,Arial,Helvetica,sans-serif';
	$font9 = 'roboto_slabbold,Arial,Helvetica,sans-serif';

	$fontdir = get_template_directory_uri(). '/inc/fonts/';
	
	$css = '';

	if ( in_array($font1,array($haystack1, $haystack2, $haystack4, $haystack5)) ) {

		
		$css .= "@font-face{font-family:qumpellkano12regular;src:url(" .$fontdir. "bluechip/qumpellkano12-webfont.eot);src:url(" .$fontdir. "bluechip/qumpellkano12-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "bluechip/qumpellkano12-webfont.woff) format('woff'),url(" .$fontdir. "bluechip/qumpellkano12-webfont.ttf) format('truetype');font-weight:400;font-style:normal}";
		
		
	}
	if ( in_array($font2,array($haystack1, $haystack2, $haystack4, $haystack5)) ) {
		
		
		$css .= "@font-face{font-family:bebasregular;src:url(" .$fontdir. "bebas_regular/BEBAS___-webfont.eot);src:url(" .$fontdir. "bebas_regular/BEBAS___-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "bebas_regular/BEBAS___-webfont.woff) format('woff'),url(" .$fontdir. "bebas_regular/BEBAS___-webfont.ttf) format('truetype'),url(" .$fontdir. "bebas_regular/BEBAS___-webfont.svg#bebasregular) format('svg');font-weight:400;font-style:normal}";
	}
	if ( in_array($font3,array($haystack1, $haystack2, $haystack4, $haystack5)) ) {
		
		
		$css .= "@font-face{font-family:dayposterblackregular;src:url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.eot);src:url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.woff) format('woff'),url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.ttf) format('truetype'),url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.svg#dayposterblackregular) format('svg');font-weight:400;font-style:normal}";
	}
	if ( in_array($font4,array($haystack1, $haystack2, $haystack4, $haystack5)) ) {
		
		
		$css .= "@font-face{font-family:forqueregular;src:url(" .$fontdir. "forque/Forque-webfont.eot);src:url(" .$fontdir. "forque/Forque-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "forque/Forque-webfont.woff) format('woff'),url(" .$fontdir. "forque/Forque-webfont.ttf) format('truetype'),url(" .$fontdir. "forque/Forque-webfont.svg#forqueregular) format('svg');font-weight:400;font-style:normal}";
	}
	if ( in_array($font5,array($haystack1, $haystack2, $haystack4, $haystack5)) ) {
		
		
		$css .= "@font-face{font-family:florante_at_lauraregular;src:url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.eot);src:url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.woff) format('woff'),url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.ttf) format('truetype'),url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.svg#florante_at_lauraregular) format('svg');font-weight:400;font-style:normal}";
	}
	if ( in_array($font6,array($haystack1, $haystack2, $haystack4, $haystack5)) ) {
	
		
		$css .= "@font-face{font-family:fontleroybrownregular;src:url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.eot);src:url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.woff) format('woff'),url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.ttf) format('truetype'),url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.svg#fontleroybrownregular) format('svg');font-weight:400;font-style:normal}";
	}
	if ( in_array($font7,array($haystack1, $haystack2, $haystack4, $haystack5)) ) {
	
		
		$css .= "@font-face{font-family:kingthings_exeterregular;src:url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.eot);src:url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.woff) format('woff'),url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.ttf) format('truetype'),url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.svg#kingthings_exeterregular) format('svg');font-weight:400;font-style:normal}";
	}
	if ( in_array($font8,array($haystack1, $haystack2, $haystack3, $haystack4, $haystack5)) ) {
	
		
		$css .= "@font-face{font-family:roboto_slabregular;src:url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.eot);src:url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.woff) format('woff'),url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.ttf) format('truetype'),url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.svg#roboto_slabregular) format('svg');font-weight:400;font-style:normal}";
	}
	if ( in_array($font9,array($haystack1, $haystack2, $haystack3, $haystack4, $haystack5)) ) {
	
		
		$css .= "@font-face{font-family:roboto_slabbold;src:url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.eot);src:url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.woff) format('woff'),url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.ttf) format('truetype'),url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.svg#roboto_slabbold) format('svg');font-weight:400;font-style:normal}";
	}
	
	if ( $css ) {
		update_option('crucible_inline_font_css',$css);
	}
}
add_action( 'wp_enqueue_scripts', 'crucible_loadfonts' );


/**
 * Load custom fontface styles on wp-login screen only if no logo is used.
 * This is almost same as above but only check logo font, not all fonts.
 */

 function crucible_logofontface() {

	global $smartestthemes_options;
	// logo fonts are only needed if no logo image
	if( empty($smartestthemes_options['logo_setting']) ? '' : $smartestthemes_options['logo_setting'] ) {
		return;
	}

	$fontdir = get_template_directory_uri(). '/inc/fonts/';
	$haystack2 = empty($smartestthemes_options['logo_font']) ? '' : $smartestthemes_options['logo_font'];
	$css = '';	
		
	/* FONTS */
	$font1 =  'qumpellkano12regular,Arial,Helvetica,sans-serif';
	$font2 =  'bebasregular,Arial,Helvetica,sans-serif';
	$font3 =  'dayposterblackregular,Arial,Helvetica,sans-serif';
	$font4 =  'forqueregular,Arial,Helvetica,sans-serif';
	$font5 =  'florante_at_lauraregular,Arial,Helvetica,sans-serif';
	$font6 =  'fontleroybrownregular,Arial,Helvetica,sans-serif';
	$font7 =  'kingthings_exeterregular,Arial,Helvetica,sans-serif';
	$font8 =  'roboto_slabregular,Arial,Helvetica,sans-serif';
	$font9 =  'roboto_slabbold,Arial,Helvetica,sans-serif';
			

	if ( $font1 == $haystack2 ) {
		
		$css .= "@font-face{font-family:qumpellkano12regular;src:url(" .$fontdir. "/qumpellkano12-webfont.eot);src:url(" .$fontdir. "/qumpellkano12-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "/qumpellkano12-webfont.woff) format('woff'),url(" .$fontdir. "/qumpellkano12-webfont.ttf) format('truetype');font-weight:400;font-style:normal}";
		
		
		
	}
	if ( $font2 == $haystack2 ) {
			
		
		$css .= "@font-face{font-family:bebasregular;src:url(" .$fontdir. "bebas_regular/BEBAS___-webfont.eot);src:url(" .$fontdir. "bebas_regular/BEBAS___-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "bebas_regular/BEBAS___-webfont.woff) format('woff'),url(" .$fontdir. "bebas_regular/BEBAS___-webfont.ttf) format('truetype'),url(" .$fontdir. "bebas_regular/BEBAS___-webfont.svg#bebasregular) format('svg');font-weight:400;font-style:normal}";
		
		
		
	}	
	if ( $font3 == $haystack2 ) {
		
		
		$css .= "@font-face{font-family:dayposterblackregular;src:url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.eot);src:url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.woff) format('woff'),url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.ttf) format('truetype'),url(" .$fontdir. "dayposterblack/DAYPBL__-webfont.svg#dayposterblackregular) format('svg');font-weight:400;font-style:normal}";
		
		
		
	}
	if ( $font4 == $haystack2 ) {
		
		
		$css .= "@font-face{font-family:forqueregular;src:url(" .$fontdir. "forque/Forque-webfont.eot);src:url(" .$fontdir. "forque/Forque-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "forque/Forque-webfont.woff) format('woff'),url(" .$fontdir. "forque/Forque-webfont.ttf) format('truetype'),url(" .$fontdir. "forque/Forque-webfont.svg#forqueregular) format('svg');font-weight:400;font-style:normal}";
		
		
		
	}	
	if ( $font5 == $haystack2 ) {
				
		
		$css .= "@font-face{font-family:florante_at_lauraregular;src:url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.eot);src:url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.woff) format('woff'),url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.ttf) format('truetype'),url(" .$fontdir. "floranteatlaura/FLORLRG_-webfont.svg#florante_at_lauraregular) format('svg');font-weight:400;font-style:normal}";
		
		
		
	}	
	if ( $font6 == $haystack2 ) {
			
		
			$css .= "@font-face{font-family:fontleroybrownregular;src:url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.eot);src:url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.woff) format('woff'),url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.ttf) format('truetype'),url(" .$fontdir. "fontleroybrown/FontleroyBrown-webfont.svg#fontleroybrownregular) format('svg');font-weight:400;font-style:normal}";

		
	}
	if ( $font7 == $haystack2 ) {

		
		
		$css .= "@font-face{font-family:kingthings_exeterregular;src:url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.eot);src:url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.woff) format('woff'),url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.ttf) format('truetype'),url(" .$fontdir. "kingthingsexete/Kingthings_Exeter-webfont.svg#kingthings_exeterregular) format('svg');font-weight:400;font-style:normal}";
		
		
	}	
	if ( $font8 == $haystack2 ) {

		
		
		$css .= "@font-face{font-family:roboto_slabregular;src:url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.eot);src:url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.woff) format('woff'),url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.ttf) format('truetype'),url(" .$fontdir. "robotoslab_regular/RobotoSlab-Regular-webfont.svg#roboto_slabregular) format('svg');font-weight:400;font-style:normal}";
		
		
		
	}	
	if ( $font9 == $haystack2 ) {
	
		
		
		
		$css .= "@font-face{font-family:roboto_slabbold;src:url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.eot);src:url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.eot?#iefix) format('embedded-opentype'),url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.woff) format('woff'),url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.ttf) format('truetype'),url(" .$fontdir. "robotoslab_bold/RobotoSlab-Bold-webfont.svg#roboto_slabbold) format('svg');font-weight:400;font-style:normal}";
		
	}
	
	if ( $css ) {
		update_option('crucible_login_font_css',$css);
	}
}
add_action( 'login_enqueue_scripts', 'crucible_logofontface' ); ?>