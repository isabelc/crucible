<style><?php global $smartestthemes_options;
$header_bg_color = isset($smartestthemes_options['header_bg_color']) ? $smartestthemes_options['header_bg_color'] : '';
$footer_bg_color = isset($smartestthemes_options['footer_bg_color']) ? $smartestthemes_options['footer_bg_color'] : '';

if ( $header_bg_color ) {
	?>#masthead{background:<?php echo $header_bg_color; ?>; }<?php
}

if ( $footer_bg_color ) {
	?>footer.site-footer{background:<?php echo $footer_bg_color; ?>; }<?php
}

/* FONTS */

$body_text_color = isset($smartestthemes_options['body_text_color']) ? $smartestthemes_options['body_text_color'] : '';
$heading_text_color = isset($smartestthemes_options['heading_text_color']) ? $smartestthemes_options['heading_text_color'] : '';
$footer_text_color = isset($smartestthemes_options['footer_text_color']) ? $smartestthemes_options['footer_text_color'] : '';
$att_grabber_color = isset($smartestthemes_options['att_grabber_color']) ? $smartestthemes_options['att_grabber_color'] : '';
$att_grabber_font = isset($smartestthemes_options['att_grabber_font']) ? $smartestthemes_options['att_grabber_font'] : 'Impact, Haettenschweiler, Arial Narrow Bold, sans-serif';// @new default
$attgrabber_fontsize = isset($smartestthemes_options['attgrabber_fontsize']) ? $smartestthemes_options['attgrabber_fontsize'] : '';

$body_font = isset($smartestthemes_options['body_font']) ? $smartestthemes_options['body_font'] : '';
$body_fontsize = isset($smartestthemes_options['body_fontsize']) ? $smartestthemes_options['body_fontsize'] : '';

$heading_font = isset($smartestthemes_options['heading_font']) ? $smartestthemes_options['heading_font'] : '';

$h1_fontsize =  isset($smartestthemes_options['h1_fontsize']) ? $smartestthemes_options['h1_fontsize'] : '';
$h2_fontsize =  isset($smartestthemes_options['h2_fontsize']) ? $smartestthemes_options['h2_fontsize'] : '';
$h3_fontsize =  isset($smartestthemes_options['h3_fontsize']) ? $smartestthemes_options['h3_fontsize'] : '';
$h4_fontsize =  isset($smartestthemes_options['h4_fontsize']) ? $smartestthemes_options['h4_fontsize'] : '';



if ( $body_text_color ) { 

	// @test do i need all these selectors....
	// blockquote, .entry-meta, time, dl.main-address span, .widget ul a
	
	?>body {color:<?php echo $body_text_color; ?>;}<?php
} 


if ($footer_text_color) { 

	// @test this selector to make sure it doesn't hijace social icons
	// @test see how it works with links color

	?>.site-info{color:<?php echo $footer_text_color; ?>;}<?php
} 



if ( $body_font ) {
	
	// @test which selectors to use? Do I need all these (also fix these in customizer.js ....
	
	?>#content {font-family:<?php echo $body_font; ?>;}
	body a.button, body button.button, body input.button, body #review_form #submit {font-family:<?php echo $body_font; ?>!important;}<?php
	
}

if ( $body_fontsize ) {
	?>#content .main, #home-footer, blockquote {font-size:<?php echo $body_fontsize; ?>;}
	body a.button, body button.button, body input.button, body #review_form #submit {
		font-size:<?php echo $body_fontsize; ?>!important;}
	<?php 
}



if( $heading_text_color ) {
	// @test do i need all these selectors for headings ....
	//  .indent-left h3, .pad h3, .page-title, #entry-title
	
	?>#content h1,#content h2,h3,h4,h5,h6{
		color:<?php echo $heading_text_color; ?>;
		}<?php
}

if ( $h1_fontsize ) {
	?>#content h1, #content h1 a {font-size:<?php echo $h1_fontsize; ?>;}<?php
}
if ( $h2_fontsize ) {
	?>#content h2, #content h2 a {font-size:<?php echo $h2_fontsize; ?>;}<?php
}
if ( $h3_fontsize ) { ?>
	h3, h3 a {font-size:<?php echo $h3_fontsize; ?>;}<?php
}
if ( $h4_fontsize ) { ?>
	h4, h4 a {font-size:<?php echo $h4_fontsize; ?>;}<?php
}
if ( $heading_font ) {
	?>#content h1, #content h1 a, #content h2, #content h2 a, h3, h3 a, h4, h4 a, h5, h5 a {font-family:<?php echo $heading_font; ?>;}<?php
}

// attention grabber
if ( is_front_page() ) {
		
		if ( $att_grabber_font ) { ?>
	
			.attention-grab{ font-family:<?php echo $att_grabber_font; ?>;
		
		<?php }
		
		if ( $att_grabber_color ) { ?>
		
				color:<?php echo $att_grabber_color; ?>;
		<?php }
		
		if ( $attgrabber_fontsize ) { ?>
				font-size:<?php echo $attgrabber_fontsize; ?>;
		<?php } ?>
		}
		
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

// @new default for next 7
$link_color = isset($smartestthemes_options['link_color']) ? $smartestthemes_options['link_color'] : 'green';
$link_hover_color = isset($smartestthemes_options['link_hover_color']) ? $smartestthemes_options['link_hover_color'] : 'pink';
$button_color = isset($smartestthemes_options['button_color']) ? $smartestthemes_options['button_color'] : '#e6e6e6';
$button_hover_color = isset($smartestthemes_options['button_hover_color']) ? $smartestthemes_options['button_hover_color'] : '#e6e6e6';
$button_text_color = isset($smartestthemes_options['button_text_color']) ? $smartestthemes_options['button_text_color'] : 'rgba(0, 0, 0, .8)';
$table_caption_color = isset($smartestthemes_options['table_caption_bg_color']) ? $smartestthemes_options['table_caption_bg_color'] : '#999';
$table_alt_row_color = isset($smartestthemes_options['table_alt_row_color']) ? $smartestthemes_options['table_alt_row_color'] : '#e0e0e0';

	?>a, i.fa-clock-o, i.fa-bullhorn, .entry-meta.jobtitle,.menu .current-menu-item a,body.post-type-archive-smartest_staff .menu li.staff a,body.post-type-archive-smartest_services .menu li.services a,body.post-type-archive-smartest_news .menu li.news a,body.tax-smartest_service_category .menu li.services a,body.single-smartest_services .menu .services a,body.single-smartest_staff .menu .staff a,body.single-smartest_news .menu .news a,body.about .menu li.about a, body.contact .menu li.contact a,body.reviews .menu li.reviews a,body.single-post .menu li.blog a,body.archive.author .menu li.blog a,body.archive.category .menu li.blog a,body.archive.tag .menu li.blog a,body.archive.date .menu li.blog a,body.page-id-<?php echo get_option('smartestthemes_about_page_id'); ?> .menu li.about a, body.page-id-<?php echo get_option('smartestthemes_contact_page_id'); ?> .menu li.contact a, body.page-id-<?php echo get_option('smartestthemes_reviews_page_id'); ?> .menu li.reviews a,body.page-id-<?php echo get_option('smartestthemes_home_page_id'); ?> .menu > li.home > a { 
		color:<?php echo $link_color; ?>;
}
	
a:hover, a:focus, a:active, i.fa:hover,.site-title a:hover{
	color:<?php echo $link_hover_color; ?>;
}

.button, button, html input[type="button"], #smar_pagination .smar_current, #smar_pagination a:hover, input[type="reset"],input[type="submit"],#smar_button_1,#smar_submit_btn{

    background:<?php echo $button_color; ?>;
	color:<?php echo $button_text_color; ?>;
}
	
.button:hover, button:hover, input[type="button"]:hover,input[type="reset"]:hover,input[type="submit"]:hover,#smar_button_1:hover,#smar_submit_btn:hover{
    background:<?php echo $button_hover_color; ?>;
}

#today, table caption, thead {background:<?php echo $table_caption_color; ?> }
tbody tr:nth-child(even) {background:<?php echo $table_alt_row_color; ?> }



<?php

// LOGO

/* avoid PHP notices */
$logo_color = isset($smartestthemes_options['logo_color']) ? $smartestthemes_options['logo_color'] : '';
$logo_font = isset($smartestthemes_options['logo_font']) ? $smartestthemes_options['logo_font'] : '';
$logo_fontsize = isset($smartestthemes_options['logo_fontsize']) ? $smartestthemes_options['logo_fontsize'] : '';
$increase_logo = isset($smartestthemes_options['increase_logo']) ? $smartestthemes_options['increase_logo'] : '';
$hide_tagline = isset($smartestthemes_options['hide_tagline']) ? $smartestthemes_options['hide_tagline'] : '';
$tagline_color = isset($smartestthemes_options['tagline_color']) ? $smartestthemes_options['tagline_color'] : '';
$tagline_font = isset($smartestthemes_options['tagline_font']) ? $smartestthemes_options['tagline_font'] : '';
$tagline_size = isset($smartestthemes_options['tagline_size']) ? $smartestthemes_options['tagline_size'] : '';

if ( $logo_color ) {
	?>.site-title a { color:<?php echo $logo_color; ?>; }<?php
}
if ( $logo_font ) {
	?>.site-title a {font-family:<?php echo $logo_font; ?>}<?php
}
if ( $logo_fontsize ) {
	?>.site-title a {font-size:<?php echo $logo_fontsize; ?>}<?php
}
	
if ( $increase_logo ) {
	?>img#customlogo {max-height:<?php echo $increase_logo; ?>px}<?php
}

// tagline
if( empty($hide_tagline) ) {
	if ( $tagline_color ) {
		?>h2.site-description { color:<?php echo $tagline_color; ?>;}<?php
	}
	if ( $tagline_font ) {
		?>h2.site-description{font-family:<?php echo $tagline_font; ?>;}<?php
	} 
	if ( $tagline_size ) {
		?>h2.site-description{font-size:<?php echo $tagline_size; ?>;}<?php
	}
}

// custom css from theme options
echo $smartestthemes_options['st_custom_css']; ?></style>