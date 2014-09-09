<style><?php global $smartestthemes_options;
$header_bg_color = empty($smartestthemes_options['header_bg_color']) ? '' : $smartestthemes_options['header_bg_color'];
$footer_bg_color = empty($smartestthemes_options['footer_bg_color']) ? '' : $smartestthemes_options['footer_bg_color'];

if ( $header_bg_color ) {
	?>#masthead{background:<?php echo $header_bg_color; ?>; }<?php
}

if ( $footer_bg_color ) {
	?>.site-footer{background:<?php echo $footer_bg_color; ?>; }<?php
}


/* FONTS */
$att_grabber_color = empty($smartestthemes_options['att_grabber_color']) ? '' : $smartestthemes_options['att_grabber_color'];
$att_grabber_font = empty($smartestthemes_options['att_grabber_font']) ? '' : $smartestthemes_options['att_grabber_font'];
$attgrabber_fontsize = empty($smartestthemes_options['attgrabber_fontsize']) ? '' : $smartestthemes_options['attgrabber_fontsize'];
$body_text_color = empty($smartestthemes_options['body_text_color']) ? '' : $smartestthemes_options['body_text_color'];
$body_font = empty($smartestthemes_options['body_font']) ? '' : $smartestthemes_options['body_font'];
$body_fontsize = empty($smartestthemes_options['body_fontsize']) ? '' : $smartestthemes_options['body_fontsize'];
$heading_text_color = empty($smartestthemes_options['heading_text_color']) ? '' : $smartestthemes_options['heading_text_color'];
$heading_font = empty($smartestthemes_options['heading_font']) ? '' : $smartestthemes_options['heading_font'];
$h1_fontsize =  empty($smartestthemes_options['h1_fontsize']) ? '' : $smartestthemes_options['h1_fontsize'];
$h2_fontsize =  empty($smartestthemes_options['h2_fontsize']) ? '' : $smartestthemes_options['h2_fontsize'];
$h3_fontsize =  empty($smartestthemes_options['h3_fontsize']) ? '' : $smartestthemes_options['h3_fontsize'];
$h4_fontsize =  empty($smartestthemes_options['h4_fontsize']) ? '' : $smartestthemes_options['h4_fontsize'];

$footer_text_color = empty($smartestthemes_options['footer_text_color']) ? '' : $smartestthemes_options['footer_text_color'];

if ( $body_text_color ) { 

	?>body {color:<?php echo $body_text_color; ?>;}<?php
} 


if ($footer_text_color) { 
	?>.site-info{color:<?php echo $footer_text_color; ?>;}<?php
} 



if ( $body_font ) {
	?>#content{font-family:<?php echo $body_font; ?>;}<?php
	
}

if ( $body_fontsize ) {
	?>#content, #home-footer, blockquote {font-size:<?php echo $body_fontsize; ?>;}<?php 
}



if( $heading_text_color ) {
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
	?>#content h1, #content h1 a, #content h2, #content h2 a, h3, h3 a, h4, h4 a, h5, h5 a, h6, h6 a {font-family:<?php echo $heading_font; ?>;}<?php
}

// attention grabber

if ( $att_grabber_font || $att_grabber_color || $attgrabber_fontsize ) { ?>

	.attention-grab{
	
	<?php if ( $att_grabber_font ) { ?>
			font-family:<?php echo $att_grabber_font; ?>;
	<?php }
			
	if ( $att_grabber_color ) { ?>
			color:<?php echo $att_grabber_color; ?>;
	<?php }
			
	if ( $attgrabber_fontsize ) { ?>
			font-size:<?php echo $attgrabber_fontsize; ?>;
	<?php } ?>
	
	}
		
<?php }

/** @new
/* Create link relationships for fallback menu, to highlight current the page.
*/
?>
.page-id-<?php echo get_option('smartestthemes_about_page_id'); ?> .main-navigation .about a, 
.page-id-<?php echo get_option('smartestthemes_contact_page_id'); ?> .main-navigation .contact a, 
.page-id-<?php echo get_option('smartestthemes_reviews_page_id'); ?> .main-navigation .reviews a,
.post-type-archive-smartest_staff .main-navigation .staff a,
.post-type-archive-smartest_news .main-navigation .news a,
.post-type-archive-smartest_services .main-navigation .services a,
.tax-smartest_service_category .main-navigation .services a,
.single-smartest_services .main-navigation .services a,
.single-smartest_staff .main-navigation .staff a,
.single-smartest_news .main-navigation .news a,
.about .main-navigation .about a, 
.contact .main-navigation .contact a,
.reviews .main-navigation .reviews a,
.single-post .main-navigation .blog a,
.archive.author .main-navigation .blog a,
.archive.category .main-navigation .blog a,
.archive.tag .main-navigation .blog a,
.archive.date .main-navigation .blog a {
		color: #fff;
}

<?php


// ACCENT COLORS

$link_color = empty($smartestthemes_options['link_color']) ? '' : $smartestthemes_options['link_color'];
$link_hover_color = empty($smartestthemes_options['link_hover_color']) ? '' : $smartestthemes_options['link_hover_color'];
$button_color = empty($smartestthemes_options['button_color']) ? '' : $smartestthemes_options['button_color'];
$button_text_color = empty($smartestthemes_options['button_text_color']) ? '' : $smartestthemes_options['button_text_color'];
$button_hover_color = empty($smartestthemes_options['button_hover_color']) ? '' : $smartestthemes_options['button_hover_color'];
$table_caption_color = empty($smartestthemes_options['table_caption_bg_color']) ? '' : $smartestthemes_options['table_caption_bg_color'];
$table_alt_row_color = empty($smartestthemes_options['table_alt_row_color']) ? '' : $smartestthemes_options['table_alt_row_color'];



// @new default for this and next 6 colors.
if ( $link_color && ( '#008000' != $link_color) ) {

	?>a, i.fa-clock-o, i.fa-bullhorn, .entry-meta.jobtitle,.main-navigation .current-menu-item a,
	.main-navigation .current_page_item a,
	.home .main-navigation .home>a,
	.page-id-<?php echo get_option('smartestthemes_about_page_id'); ?> .main-navigation .about a, 
	.page-id-<?php echo get_option('smartestthemes_contact_page_id'); ?> .main-navigation .contact a, 
	.page-id-<?php echo get_option('smartestthemes_reviews_page_id'); ?> .main-navigation .reviews a,
	.post-type-archive-smartest_staff .main-navigation .staff a,
	.post-type-archive-smartest_news .main-navigation .news a,
	.post-type-archive-smartest_services .main-navigation .services a,
	.tax-smartest_service_category .main-navigation .services a,
	.single-smartest_services .main-navigation .services a,
	.single-smartest_staff .main-navigation .staff a,
	.single-smartest_news .main-navigation .news a,
	.about .main-navigation .about a, 
	.contact .main-navigation .contact a,
	.reviews .main-navigation .reviews a,
	.single-post .main-navigation .blog a,
	.archive.author .main-navigation .blog a,
	.archive.category .main-navigation .blog a,
	.archive.tag .main-navigation .blog a,
	.archive.date .main-navigation .blog a{ color:<?php echo $link_color; ?>;} <?php
	
}
	
if ( $link_hover_color && ( '#ffc0cb' != $link_hover_color) ) {

	?>a:hover, a:focus, a:active, i.fa:hover,.site-title a:hover{color:<?php echo $link_hover_color; ?>;}<?php
	
}

$print_button_color = '';
if ( $button_color && ('#e6e6e6' != $button_color) ) {
	$print_button_color = true;
}
$print_button_text_color = '';
if ($button_text_color && ('#191919' != $button_text_color)) {
	$print_button_text_color = true;
}


if ( $print_button_color || $print_button_text_color ) {

	?>.button, button, html input[type="button"], .smar_pagination .smar_current, .smar_pagination a:hover, input[type="reset"],input[type="submit"],#smar_button_1,#smar_submit_btn{<?php
	
	if ( $print_button_color ) {

		?>background:<?php echo $button_color; ?>;<?php
	}
	
	if ( $print_button_text_color ) {
		?>color:<?php echo $button_text_color; ?>;<?php
	}
	?>}<?php

}

if ( $button_hover_color && ('#e6e6e6' != $button_hover_color) ) {

	?>.button:hover, button:hover, input[type="button"]:hover,input[type="reset"]:hover,input[type="submit"]:hover,#smar_button_1:hover,#smar_submit_btn:hover{background:<?php echo $button_hover_color; ?>;}<?php

}
	
if ( $table_caption_color && ('#999999' != $table_caption_color) ) {
	?>#today, table caption, thead {background:<?php echo $table_caption_color; ?> }<?php
	
}
	
if ( $table_alt_row_color && ('#e0e0e0' != $table_alt_row_color) ) {
	?>tbody tr:nth-child(even) {background:<?php echo $table_alt_row_color; ?> }<?php
}



// LOGO

$logo_color = empty($smartestthemes_options['logo_color']) ? '' : $smartestthemes_options['logo_color'];
$logo_fontsize = empty($smartestthemes_options['logo_fontsize']) ? '' : $smartestthemes_options['logo_fontsize'];
$increase_logo = empty($smartestthemes_options['increase_logo']) ? '' : $smartestthemes_options['increase_logo'];
$tagline_color = empty($smartestthemes_options['tagline_color']) ? '' : $smartestthemes_options['tagline_color'];
$tagline_size = empty($smartestthemes_options['tagline_size']) ? '' : $smartestthemes_options['tagline_size'];

$print_logo_color = '';
if ( $logo_color && ('#000000' != $logo_color) ) {
	$print_logo_color = true;
}

if ( $print_logo_color || $logo_fontsize ) {

	?>.site-title a {<?php
	
		if ( $print_logo_color ) {
			?>color:<?php echo $logo_color; ?>;<?php
		}

		if ( $logo_fontsize ) {
			?>font-size:<?php echo $logo_fontsize; ?>;<?php
		}
	?>}<?php

}
	
if ( $increase_logo ) {
	?>img#customlogo {max-height:<?php echo $increase_logo; ?>px}<?php
}

// tagline


$print_tagline_color = '';
if ( $tagline_color && ('#404040' != $tagline_color) ) {
	$print_tagline_color = true;
}

if( empty($smartestthemes_options['hide_tagline']) ) {

	if ( $print_tagline_color || $tagline_size ) {
	
		?>h2.site-description {<?php

			if ( $print_tagline_color ) {
				?>color:<?php echo $tagline_color; ?>;<?php
			}
			
			if ( $tagline_size ) {
				?>font-size:<?php echo $tagline_size; ?>;<?php
			}
		
		?>}<?php
		
	}
}

$widget_css = get_option('smartestthemes_widget_styles');
if ( $widget_css )
	echo $widget_css;

// custom css from theme options
if ( isset($smartestthemes_options['st_custom_css']) ) {
	echo $smartestthemes_options['st_custom_css'];
}
?></style>