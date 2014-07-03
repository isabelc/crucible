<style>
<?php $options = get_option('smartestthemes_options');
if ( $options['st_header_color'] ) { ?>
	#navigation, #primary-navigation.toggled-on .menu{background:<?php echo $options['st_header_color']; ?>; }
<?php } if ( $options['st_footer_color'] ) { ?>
	footer#site-footer,#home-footer{background:<?php echo $options['st_footer_color']; ?>; }
<?php }
if ( $options['st_bg_color'] ) { ?>body {background-color:<?php echo $options['st_bg_color']; ?>; }<?php }
if ($options['st_bg_texture'] == 'none' ) {
	if ( $options['st_bg_image'] ) { ?>
			body {background-image:url('<?php echo $options['st_bg_image']; ?>'); }
			<?php if ( $options['st_bg_image_repeat'] ) { ?>
				body {background-repeat:<?php echo $options['st_bg_image_repeat']; ?>; }
			<?php } if ( $options['st_bg_image_position'] ) { ?>
				body {background-position:<?php echo $options['st_bg_image_position']; ?>; }
			<?php } if ( $options['st_bg_image_fixed'] == 'true' ) { ?>
				body {background-attachment:fixed; }
			<?php }
	}
} elseif ($options['st_bg_texture']) { ?>
		body {background-image:url('<?php echo get_template_directory_uri(); ?>/images/<?php echo $options['st_bg_texture']; ?>.png');background-repeat:repeat;}
<?php }
if ( $options['st_body_font_color'] ) { ?>
	body, blockquote, .entry-meta, time, .widget_smartest_announcements time, dl.main-address span, .widget ul a {color:<?php echo $options['st_body_font_color']; ?>;}
<?php } 

if ( $options['st_menu_text_color'] ) { ?>
	.menu li a, .menu-toggle:before{color:<?php echo $options['st_menu_text_color']; ?>;}
<?php } 

if ( $options['st_footer_text_color'] ) { ?>
	#site-footer,#home-footer{color:<?php echo $options['st_footer_text_color']; ?>;}
<?php } 

if ( $options['st_body_font'] ) { ?>
	#content {font-family:<?php echo $options['st_body_font']; ?>;}
	body a.button, body button.button, body input.button, body #review_form #submit {font-family:<?php echo $options['st_body_font']; ?>!important;}
<?php } 
if ( $options['st_body_font_size'] ) { ?>
	#content .main, #home-footer, blockquote {font-size:<?php echo $options['st_body_font_size']; ?>;}
	body a.button, body button.button, body input.button, body #review_form #submit {
		font-size:<?php echo $options['st_body_font_size']; ?>!important;}
	<?php 
	$font_size_pre = $options['st_body_font_size'];
	$font_size = (int)str_replace('px', '', $font_size_pre);
	if ( $font_size > 25 ) { ?>
		blockquote {line-height:<?php echo $font_size_pre; ?>;}
	<?php }
	if ( $font_size > 24 ) { ?>
		#content .main, #home-footer, #home-footer a {line-height:<?php echo $font_size_pre; ?>;}
	<?php }
} if($options['st_heading_font_color']) { ?>
		h3, .indent-left h3, .pad h3, .page-title, #entry-title, h4, h6, h2, article.hentry h1{
		color:<?php echo $options['st_heading_font_color']; ?>;
		}
<?php }

// HEADING
if ( $options['st_heading_one_font_size'] ) { ?>
	#content h1, #content h1 a {font-size:<?php echo $options['st_heading_one_font_size']; ?>;}
<?php } if ( $options['st_heading_two_font_size'] ) { ?>
	h2, h2 a {font-size:<?php echo $options['st_heading_two_font_size']; ?>;}
<?php } if ( $options['st_heading_three_font_size'] ) { ?>
	h3, h3 a {font-size:<?php echo $options['st_heading_three_font_size']; ?>;}
<?php } if ( $options['st_heading_four_font_size'] ) { ?>
	h4, h4 a {font-size:<?php echo $options['st_heading_four_font_size']; ?>;}
<?php } if ( $options['st_heading_font'] ) { ?>
	#content h1, #content h1 a, h2, h2 a, h3, h3 a, h4, h4 a {font-family:<?php echo $options['st_heading_font']; ?>;}
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

if ( $options['st_override_accent_color'] == 'true' ) { // accent color override is checked...
	$accent_color = $options['st_custom_accent_color'];
	$hover_color = '#1C1C1C'; // original black
} else { // check if regular accent color is selected
	// then begin my choices
		if ( $options['st_accent_color'] == 'red' ) {
			$accent_color = '#D81919';
			$hover_color = '#c21616';
		}
		if ( $options['st_accent_color'] == 'orange' ) {
			$accent_color = '#F3A600';
			$hover_color = '#da9500';
		}
		if ( $options['st_accent_color'] == 'lime' ) {
			$accent_color = '#79BF00';
			$hover_color = '#6cab00';
		}
		if ( $options['st_accent_color'] == 'blue' ) {
			$accent_color = '#0F4D92';
			$hover_color = '#265e9c';
		}
		if ( $options['st_accent_color'] == 'light blue' ) {
			$accent_color = '#11B7E7';
			$hover_color = '#0fa4cf';
		}
		if ( $options['st_accent_color'] == 'violet' ) {
			$accent_color = '#616FF3';
			$hover_color = '#5763da';
		}
		if ( $options['st_accent_color'] == 'bronze brown' ) {
			$accent_color = '#804000';
			$hover_color = '#996632';
		}
		if ( $options['st_accent_color'] == 'sand' ) {
			$accent_color = '#c0b870';
			$hover_color = '#c6bf7e';
		}
		if ( $options['st_accent_color'] == 'gray' ) {
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
if ( $options['st_logo_color'] ) {
	echo '.site-title a { color:'. $options['st_logo_color'].'; }';
}

if ( $options['st_logo_color_2'] ) {
	echo '.site-title a strong { color:'. $options['st_logo_color_2'].'; }';
}
if ( $options['st_logo_color_4'] ) {
	echo '.site-title span { color:'. $options['st_logo_color_4'].'; }';
}

if ( $options['st_logo_hover_color'] ) {
	echo '.site-title a:hover { color:'.$options['st_logo_hover_color'].'; }';
}

$logo_font = $options['st_logo_font'];
if ( $logo_font )
	echo '.site-title a,.site-title span {font-family:'. $logo_font. ' }';
if( in_array($logo_font, array('florante_at_lauraregular,Arial,Helvetica,sans-serif') ) ) echo '.site-title a{letter-spacing: -3px;}';
if ( $options['st_logo_font_size'] )
	echo '.site-title a {font-size:'.$options['st_logo_font_size'].'; }';
if ( $options['st_logo_font_size_4'] )
	echo '.site-title span {font-size:'.$options['st_logo_font_size_4'].';line-height:'.$options['st_logo_font_size_4'].'; }';
$increase_logo = $options['st_increase_logo'] ;
if ( $increase_logo ) {
	echo 'a#logolink #customlogo {max-height:' . $increase_logo . 'px !important;}';
}
// tagline
if ( $options['st_tagline_color'] ) {
		echo '#mast h4 { color:'. $options['st_tagline_color'].'; }';
}
if ( $options['st_tagline_font'] ) { ?>
	#mast h4 {font-family:<?php echo $options['st_tagline_font']; ?>;}
<?php } 
 if ( $options['st_tagline_font_size'] ) { ?>
	#mast h4 {font-size:<?php echo $options['st_tagline_font_size']; ?>;}
<?php }
// attention grabber
if ( $options['st_attention_grabber_color'] ) { 
		echo '.titles { color:'. $options['st_attention_grabber_color'].'; }';
}
if ( $options['st_attention_grabber_font'] ) { ?>
	.titles {font-family:<?php echo $options['st_attention_grabber_font']; ?>;}
<?php } 
 if ( $options['st_attention_grabber_font_size'] ) { ?>
	.titles {font-size:<?php echo $options['st_attention_grabber_font_size']; ?>;}
<?php }
if ( $options['st_menu_hover_color'] ) { ?>
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
	{color:<?php echo $options['st_menu_hover_color']; ?>;}
<?php } if ( $options['st_colorful_social'] == 'true' ) { ?>
.social-google{background-position: 0 -168px;}.social-google:hover{background-position:0 -112px}.social-facebook{background-position:0 -56px}.social-facebook:hover{background-position:0 0}.social-twitter{background-position:0 -392px}.social-twitter:hover{background-position:0 -336px}.social-linkedin{background-position:0 -280px}.social-linkedin:hover{background-position:0 -224px}.social-youtube{background-position:0 -504px}.social-youtube:hover{background-position:0 -448px}
<?php }
// custom css from theme options
echo $options['st_custom_css']; ?></style>