<style>
<?php 
if ( smartestthemes_get_option('smartestthemes_header_color') ) { ?>
	#navigation, #primary-navigation.toggled-on .menu{background:<?php echo smartestthemes_get_option('smartestthemes_header_color'); ?>; }
<?php } if ( smartestthemes_get_option('smartestthemes_footer_color') ) { ?>
	footer#site-footer,#home-footer{background:<?php echo smartestthemes_get_option('smartestthemes_footer_color'); ?>; }
<?php }
if ( smartestthemes_get_option('smartestthemes_bg_color') ) { ?>body {background-color:<?php echo smartestthemes_get_option('smartestthemes_bg_color'); ?>; }<?php }
if (smartestthemes_get_option('smartestthemes_bg_texture') == 'none' ) {
	if ( smartestthemes_get_option('smartestthemes_bg_image') ) { ?>
			body {background-image:url('<?php echo smartestthemes_get_option('smartestthemes_bg_image'); ?>'); }
			<?php if ( smartestthemes_get_option('smartestthemes_bg_image_repeat') ) { ?>
				body {background-repeat:<?php echo smartestthemes_get_option('smartestthemes_bg_image_repeat'); ?>; }
			<?php } if ( smartestthemes_get_option('smartestthemes_bg_image_position') ) { ?>
				body {background-position:<?php echo smartestthemes_get_option('smartestthemes_bg_image_position'); ?>; }
			<?php } if ( smartestthemes_get_option('smartestthemes_bg_image_fixed') == 'true' ) { ?>
				body {background-attachment:fixed; }
			<?php }
	}
} elseif (smartestthemes_get_option('smartestthemes_bg_texture')) { ?>
		body {background-image:url('<?php echo get_template_directory_uri(); ?>/images/<?php echo smartestthemes_get_option('smartestthemes_bg_texture'); ?>.png');background-repeat:repeat;}
<?php }
if ( smartestthemes_get_option('smartestthemes_body_font_color') ) { ?>
	body, blockquote, .entry-meta, time, .widget_smartest_announcements time, dl.main-address span, .widget ul a {color:<?php echo smartestthemes_get_option('smartestthemes_body_font_color'); ?>;}
<?php } 

if ( smartestthemes_get_option('smartestthemes_menu_text_color') ) { ?>
	.menu li a, .menu-toggle:before{color:<?php echo smartestthemes_get_option('smartestthemes_menu_text_color'); ?>;}
<?php } 

if ( smartestthemes_get_option('smartestthemes_footer_text_color') ) { ?>
	#site-footer,#home-footer{color:<?php echo smartestthemes_get_option('smartestthemes_footer_text_color'); ?>;}
<?php } 

if ( smartestthemes_get_option('smartestthemes_body_font') ) { ?>
	#content {font-family:<?php echo smartestthemes_get_option('smartestthemes_body_font'); ?>;}
	body a.button, body button.button, body input.button, body #review_form #submit {font-family:<?php echo smartestthemes_get_option('smartestthemes_body_font'); ?>!important;}
<?php } 
if ( smartestthemes_get_option('smartestthemes_body_font_size') ) { ?>
	#content .main, #home-footer, blockquote {font-size:<?php echo smartestthemes_get_option('smartestthemes_body_font_size'); ?>;}
	body a.button, body button.button, body input.button, body #review_form #submit {
		font-size:<?php echo get_option('smartestthemes_body_font_size'); ?>!important;}
	<?php 
	$font_size_pre = get_option('smartestthemes_body_font_size');
	$font_size = (int)str_replace('px', '', $font_size_pre);
	if ( $font_size > 25 ) { ?>
		blockquote {line-height:<?php echo $font_size_pre; ?>;}
	<?php }
	if ( $font_size > 24 ) { ?>
		#content .main, #home-footer, #home-footer a {line-height:<?php echo $font_size_pre; ?>;}
	<?php }
} if(get_option('smartestthemes_heading_font_color')) { ?>
		h3, .indent-left h3, .pad h3, .page-title, #entry-title, h4, h6, h2, article.hentry h1{
		color:<?php echo get_option('smartestthemes_heading_font_color'); ?>;
		}
<?php }

// HEADING
if ( get_option('smartestthemes_heading_one_font_size') ) { ?>
	#content h1, #content h1 a {font-size:<?php echo get_option('smartestthemes_heading_one_font_size'); ?>;}
<?php } if ( get_option('smartestthemes_heading_two_font_size') ) { ?>
	h2, h2 a {font-size:<?php echo get_option('smartestthemes_heading_two_font_size'); ?>;}
<?php } if ( get_option('smartestthemes_heading_three_font_size') ) { ?>
	h3, h3 a {font-size:<?php echo get_option('smartestthemes_heading_three_font_size'); ?>;}
<?php } if ( get_option('smartestthemes_heading_four_font_size') ) { ?>
	h4, h4 a {font-size:<?php echo get_option('smartestthemes_heading_four_font_size'); ?>;}
<?php } if ( get_option('smartestthemes_heading_font') ) { ?>
	#content h1, #content h1 a, h2, h2 a, h3, h3 a, h4, h4 a {font-family:<?php echo get_option('smartestthemes_heading_font'); ?>;}
<?php } 

// HIGHLIGHT MENU
// @new active menu item style from css.
?>
body.page-id-<?php echo get_option('smartestthemes_about_page_id'); ?> .menu li.about a, 
body.page-id-<?php echo get_option('smartestthemes_contact_page_id'); ?> .menu li.contact a, 
body.page-id-<?php echo get_option('smartestthemes_reviews_page_id'); ?> .menu li.reviews a,
body.page-id-<?php echo get_option('smartestthemes_home_page_id'); ?> .menu > li.home > a,
.menu .current-menu-item a,
body.post-type-archive-smartest_staff .menu li.staff a,
	body.post-type-archive-smartest_services .menu li.services a,
	body.post-type-archive-smartest_news .menu li.news a,
	body.single-smartest_services .menu .services a,
	body.single-smartest_staff .menu .staff a,
	body.single-smartest_news .menu .news a,
	body.about .menu li.about a, 
	body.contact .menu li.contact a,
	body.reviews .menu li.reviews a,
	body.single-post .menu li.blog a,
	body.archive.author .menu li.blog a,
	body.archive.category .menu li.blog a,
	body.archive.tag .menu li.blog a,
	body.archive.date .menu li.blog a {
		color: #37C878;
		text-decoration: none;
}

<?php // ACCENT COLORS
unset($accent_color, $hover_color);

if ( get_option('smartestthemes_override_accent_color') == 'true' ) { // accent color override is checked...
	$accent_color = get_option('smartestthemes_custom_accent_color');
	$hover_color = '#1C1C1C'; // original black
} else { // check if regular accent color is selected
	// then begin my choices
		if ( get_option('smartestthemes_accent_color') == 'red' ) {
			$accent_color = '#D81919';
			$hover_color = '#c21616';
		}
		if ( get_option('smartestthemes_accent_color') == 'orange' ) {
			$accent_color = '#F3A600';
			$hover_color = '#da9500';
		}
		if ( get_option('smartestthemes_accent_color') == 'lime' ) {
			$accent_color = '#79BF00';
			$hover_color = '#6cab00';
		}
		if ( get_option('smartestthemes_accent_color') == 'blue' ) {
			$accent_color = '#0F4D92';
			$hover_color = '#265e9c';
		}
		if ( get_option('smartestthemes_accent_color') == 'light blue' ) {
			$accent_color = '#11B7E7';
			$hover_color = '#0fa4cf';
		}
		if ( get_option('smartestthemes_accent_color') == 'violet' ) {
			$accent_color = '#616FF3';
			$hover_color = '#5763da';
		}
		if ( get_option('smartestthemes_accent_color') == 'bronze brown' ) {
			$accent_color = '#804000';
			$hover_color = '#996632';
		}
		if ( get_option('smartestthemes_accent_color') == 'sand' ) {
			$accent_color = '#c0b870';
			$hover_color = '#c6bf7e';
		}
		if ( get_option('smartestthemes_accent_color') == 'gray' ) {
			$accent_color = '#949494';
			$hover_color = '#6b6b6b';
		}
	// end my choices
}

// output CSS if needed.
if ( isset($accent_color) && ! empty($accent_color) ) { ?>
	a, .color-3, .widget ul a:hover, .fa-bullhorn, .widget ul li, .widget.widget_smartest_announcements ul li, .widget.widget_smartest_announcements ul a, .site-title a strong, .entry-meta.jobtitle,
	.menu .current-menu-item a,
	.menu li:hover a,
	.menu li:hover li a:hover,
	.menu li > ul li:hover li:hover a,
	body.post-type-archive-smartest_staff .menu li.staff a,
	body.post-type-archive-smartest_services .menu li.services a,
	body.post-type-archive-smartest_news .menu li.news a,
	body.tax-smartest_service_category .menu li.services a,
	body.single-smartest_services .menu .services a,
	body.single-smartest_staff .menu .staff a,
	body.single-smartest_news .menu .news a,
	body.about .menu li.about a, 
	body.contact .menu li.contact a,
	body.reviews .menu li.reviews a,
	.menu > li:first-child:hover a,
	body.single-post .menu li.blog a,
	body.archive.author .menu li.blog a,
	body.archive.category .menu li.blog a,
	body.archive.tag .menu li.blog a,
	body.archive.date .menu li.blog a,
	body.page-id-<?php echo get_option('smartestthemes_about_page_id'); ?> .menu li.about a, 
	body.page-id-<?php echo get_option('smartestthemes_contact_page_id'); ?> .menu li.contact a, 
	body.page-id-<?php echo get_option('smartestthemes_reviews_page_id'); ?> .menu li.reviews a,
	body.page-id-<?php echo get_option('smartestthemes_home_page_id'); ?> .menu > li.home > a {
	     color:<?php echo $accent_color; ?>;
	}
	.site-title a:hover {
		color:<?php echo $hover_color; ?>;
	}
	.button, button, html input[type="button"], #smar_pagination .smar_current, #smar_pagination a:hover,  .commentlist .reply a,  
input[type="reset"],input[type="submit"], #contact-form input[type="reset"], #contact-form input[type="submit"] {
	    background:<?php echo $accent_color; ?>;
	}
	.button:hover, button:hover, .fa-bullhorn:hover, .commentlist .reply a:hover, .commentlist .reply a:active, html input[type="button"]:hover,
input[type="reset"]:hover,input[type="submit"]:hover, #contact-form input[type="reset"]:hover, #contact-form input[type="submit"]:hover, #contact-form input[type="reset"]:active, #contact-form input[type="submit"]:active {
	    background:<?php echo $hover_color; ?>;
	}
<?php }

// LOGO
if ( get_option('smartestthemes_logo_color') ) {
	echo '.site-title a { color:'. get_option('smartestthemes_logo_color').'; }';
}

if ( get_option('smartestthemes_logo_color_2') ) {
	echo '.site-title a strong { color:'. get_option('smartestthemes_logo_color_2').'; }';
}
if ( get_option('smartestthemes_logo_color_4') ) {
	echo '.site-title span { color:'. get_option('smartestthemes_logo_color_4').'; }';
}

if ( get_option('smartestthemes_logo_hover_color') ) {
	echo '.site-title a:hover { color:'.get_option('smartestthemes_logo_hover_color').'; }';
}

$logo_font = get_option('smartestthemes_logo_font');
if ( $logo_font )
	echo '.site-title a,.site-title span {font-family:'. $logo_font. ' }';
if( in_array($logo_font, array('florante_at_lauraregular,Arial,Helvetica,sans-serif') ) ) echo '.site-title a{letter-spacing: -3px;}';
if ( get_option('smartestthemes_logo_font_size') )
	echo '.site-title a {font-size:'.get_option('smartestthemes_logo_font_size').'; }';
if ( get_option('smartestthemes_logo_font_size_4') )
	echo '.site-title span {font-size:'.get_option('smartestthemes_logo_font_size_4').';line-height:'.get_option('smartestthemes_logo_font_size_4').'; }';

if ( get_option('smartestthemes_increase_logo') )
	echo 'a#logolink #customlogo {max-height:' . get_option('smartestthemes_increase_logo') . 'px !important;}';
// tagline
if ( get_option('smartestthemes_tagline_color') ) {
		echo '#mast h4 { color:'. get_option('smartestthemes_tagline_color').'; }';
}
if ( get_option('smartestthemes_tagline_font') ) { ?>
	#mast h4 {font-family:<?php echo get_option('smartestthemes_tagline_font'); ?>;}
<?php } 
 if ( get_option('smartestthemes_tagline_font_size') ) { ?>
	#mast h4 {font-size:<?php echo get_option('smartestthemes_tagline_font_size'); ?>;}
<?php }
// attention grabber
if ( get_option('smartestthemes_attention_grabber_color') ) { 
		echo '.titles { color:'. get_option('smartestthemes_attention_grabber_color').'; }';
}
if ( get_option('smartestthemes_attention_grabber_font') ) { ?>
	.titles {font-family:<?php echo get_option('smartestthemes_attention_grabber_font'); ?>;}
<?php } 
 if ( get_option('smartestthemes_attention_grabber_font_size') ) { ?>
	.titles {font-size:<?php echo get_option('smartestthemes_attention_grabber_font_size'); ?>;}
<?php }
if ( get_option('smartestthemes_menu_hover_color') ) { ?>
	.menu .current-menu-item a,
	.menu li:hover a,
	.menu li:hover li a:hover,
	.menu li > ul li:hover li:hover a,
	body.post-type-archive-smartest_staff .menu li.staff a,
	body.post-type-archive-smartest_services .menu li.services a,
	body.post-type-archive-smartest_news .menu li.news a,
	body.tax-smartest_service_category .menu li.services a,
	body.single-smartest_services .menu .services a,
	body.single-smartest_staff .menu .staff a,
	body.single-smartest_news .menu .news a,
	body.about .menu li.about a, 
	body.contact .menu li.contact a,
	body.reviews .menu li.reviews a,
	.menu > li:first-child:hover a,
	body.single-post .menu li.blog a,
	body.archive.author .menu li.blog a,
	body.archive.category .menu li.blog a,
	body.archive.tag .menu li.blog a,
	body.archive.date .menu li.blog a,
	body.page-id-<?php echo get_option('smartestthemes_about_page_id'); ?> .menu li.about a, 
	body.page-id-<?php echo get_option('smartestthemes_contact_page_id'); ?> .menu li.contact a, 
	body.page-id-<?php echo get_option('smartestthemes_reviews_page_id'); ?> .menu li.reviews a,
	body.page-id-<?php echo get_option('smartestthemes_home_page_id'); ?> .menu > li.home > a
	{color:<?php echo get_option('smartestthemes_menu_hover_color'); ?>;}
<?php } if ( get_option('smartestthemes_colorful_social') == 'true' ) { ?>
.social-google{background-position: 0 -168px;}.social-google:hover{background-position:0 -112px}.social-facebook{background-position:0 -56px}.social-facebook:hover{background-position:0 0}.social-twitter{background-position:0 -392px}.social-twitter:hover{background-position:0 -336px}.social-linkedin{background-position:0 -280px}.social-linkedin:hover{background-position:0 -224px}.social-youtube{background-position:0 -504px}.social-youtube:hover{background-position:0 -448px}
<?php }
// custom css from theme options
echo get_option('smartestthemes_custom_css'); ?></style>