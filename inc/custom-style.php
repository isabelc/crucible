<style><?php global $smartestthemes_options;

$header_color = isset($smartestthemes_options['header_bg_color']) ? $smartestthemes_options['header_bg_color'] : '';
$footer_color = isset($smartestthemes_options['footer_bg_color']) ? $smartestthemes_options['footer_bg_color'] : '';
$texture = isset($smartestthemes_options['bg_texture']) ? $smartestthemes_options['bg_texture'] : '';

if ( $header_color ) {
	?>#masthead{background:<?php echo $header_color; ?>; }<?php
}

if ( $footer_color ) {
	?>footer.site-footer{background:<?php echo $footer_color; ?>; }<?php
}

// only if there is no bg image do we check for texture

$bg_image = get_theme_mod( 'background_image' );

if ( ! $bg_image ) {

	if ($texture) {
		?>body {background-image:url('<?php echo get_template_directory_uri(); ?>/images/<?php echo $texture; ?>.png');background-repeat:repeat;}<?php
	}
}


if ( $smartestthemes_options['st_body_font_color'] ) { ?>
	body, blockquote, .entry-meta, time, .widget_smartest_announcements time, dl.main-address span, .widget ul a {color:<?php echo $smartestthemes_options['st_body_font_color']; ?>;}
<?php } 

if ( $smartestthemes_options['st_menu_text_color'] ) { ?>
	.menu li a, .menu-toggle:before{color:<?php echo $smartestthemes_options['st_menu_text_color']; ?>;}
<?php } 

if ( $smartestthemes_options['st_footer_text_color'] ) { ?>
	#site-footer,#home-footer{color:<?php echo $smartestthemes_options['st_footer_text_color']; ?>;}
<?php } 

if ( $smartestthemes_options['st_body_font'] ) { ?>
	#content {font-family:<?php echo $smartestthemes_options['st_body_font']; ?>;}
	body a.button, body button.button, body input.button, body #review_form #submit {font-family:<?php echo $smartestthemes_options['st_body_font']; ?>!important;}
<?php } 
if ( $smartestthemes_options['st_body_font_size'] ) { ?>
	#content .main, #home-footer, blockquote {font-size:<?php echo $smartestthemes_options['st_body_font_size']; ?>;}
	body a.button, body button.button, body input.button, body #review_form #submit {
		font-size:<?php echo $smartestthemes_options['st_body_font_size']; ?>!important;}
	<?php 
	$font_size_pre = $smartestthemes_options['st_body_font_size'];
	$font_size = (int)str_replace('px', '', $font_size_pre);
	if ( $font_size > 25 ) { ?>
		blockquote {line-height:<?php echo $font_size_pre; ?>;}
	<?php }
	if ( $font_size > 24 ) { ?>
		#content .main, #home-footer, #home-footer a {line-height:<?php echo $font_size_pre; ?>;}
	<?php }
} if($smartestthemes_options['st_heading_font_color']) { ?>
		h3, .indent-left h3, .pad h3, .page-title, #entry-title, h4, h6, h2, article.status-draft h1,article.status-private h1,article.status-publish h1{
		color:<?php echo $smartestthemes_options['st_heading_font_color']; ?>;
		}
<?php }

// HEADING
if ( $smartestthemes_options['st_heading_one_font_size'] ) { ?>
	#content h1, #content h1 a {font-size:<?php echo $smartestthemes_options['st_heading_one_font_size']; ?>;}
<?php } if ( $smartestthemes_options['st_heading_two_font_size'] ) { ?>
	h2, h2 a {font-size:<?php echo $smartestthemes_options['st_heading_two_font_size']; ?>;}
<?php } if ( $smartestthemes_options['st_heading_three_font_size'] ) { ?>
	h3, h3 a {font-size:<?php echo $smartestthemes_options['st_heading_three_font_size']; ?>;}
<?php } if ( $smartestthemes_options['st_heading_four_font_size'] ) { ?>
	h4, h4 a {font-size:<?php echo $smartestthemes_options['st_heading_four_font_size']; ?>;}
<?php } if ( $smartestthemes_options['st_heading_font'] ) { ?>
	#content h1, #content h1 a, h2, h2 a, h3, h3 a, h4, h4 a {font-family:<?php echo $smartestthemes_options['st_heading_font']; ?>;}
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

$link_color = isset($smartestthemes_options['link_color']) ? $smartestthemes_options['link_color'] : 'green';// @new default
$link_hover_color = isset($smartestthemes_options['link_hover_color']) ? $smartestthemes_options['link_hover_color'] : 'pink';// @new default
$button_color = isset($smartestthemes_options['button_color']) ? $smartestthemes_options['button_color'] : '#e6e6e6';// @new default
$button_hover_color = isset($smartestthemes_options['button_hover_color']) ? $smartestthemes_options['button_hover_color'] : '#e6e6e6';// @new default
$button_text_color = isset($smartestthemes_options['button_text_color']) ? $smartestthemes_options['button_text_color'] : 'rgba(0, 0, 0, .8)';// @new default

	?>a, i.fa, .widget ul li, .entry-meta.jobtitle,.menu .current-menu-item a,body.post-type-archive-smartest_staff .menu li.staff a,body.post-type-archive-smartest_services .menu li.services a,body.post-type-archive-smartest_news .menu li.news a,body.tax-smartest_service_category .menu li.services a,body.single-smartest_services .menu .services a,body.single-smartest_staff .menu .staff a,body.single-smartest_news .menu .news a,body.about .menu li.about a, body.contact .menu li.contact a,body.reviews .menu li.reviews a,body.single-post .menu li.blog a,body.archive.author .menu li.blog a,body.archive.category .menu li.blog a,body.archive.tag .menu li.blog a,body.archive.date .menu li.blog a,body.page-id-<?php echo get_option('smartestthemes_about_page_id'); ?> .menu li.about a, body.page-id-<?php echo get_option('smartestthemes_contact_page_id'); ?> .menu li.contact a, body.page-id-<?php echo get_option('smartestthemes_reviews_page_id'); ?> .menu li.reviews a,body.page-id-<?php echo get_option('smartestthemes_home_page_id'); ?> .menu > li.home > a { 
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

// attention grabber
if ( $smartestthemes_options['st_attention_grabber_color'] ) { 
		echo '.titles { color:'. $smartestthemes_options['st_attention_grabber_color'].'; }';
}
if ( $smartestthemes_options['st_attention_grabber_font'] ) { ?>
	.titles {font-family:<?php echo $smartestthemes_options['st_attention_grabber_font']; ?>;}
<?php } 
 if ( $smartestthemes_options['st_attention_grabber_font_size'] ) { ?>
	.titles {font-size:<?php echo $smartestthemes_options['st_attention_grabber_font_size']; ?>;}
<?php }
if ( $smartestthemes_options['st_menu_hover_color'] ) { ?>
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
	{color:<?php echo $smartestthemes_options['st_menu_hover_color']; ?>;}
<?php }
// custom css from theme options
echo $smartestthemes_options['st_custom_css']; ?></style>