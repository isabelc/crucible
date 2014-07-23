<?php
/**
 * @new edit name per diff framework
 */
function smartestthemes_options(){
$themeobject = wp_get_theme();
$themename = $themeobject->Name;
$themeslug = $themeobject->Template;
$manualurl = 'http://www.smartestthemes.com/docs/category/'.$themeslug.'/';
$rlink = '<a href="'.admin_url('options-reading.php').'">'. __('Reading Settings', 'crucible').'</a>';
$slink = '<a href="'.admin_url('options-general.php').'">'. __('Settings', 'crucible'). '</a>';
$bnam = get_bloginfo('name');
$user_info = get_userdata(1);
if ($user_info == true) {
	$admin_name = $user_info->user_login;
} else {
	$admin_name = __( 'Site Administrator', 'crucible' );
}
$currtime = date("l, F jS, Y @ g:i a");
/* @new edit shortname per diff framework */
$shortname = 'st';// @test  @todo update all option names in theme

// Globalize theme options variable for use in theme
global $smartestthemes_options;
$smartestthemes_options = array();
$smartestthemes_options = get_option('smartestthemes_options');


/**
 * @new choose headfonts, 1st option blank
 */
$headfonts = array(
'' => '',
'Arial,Helvetica,sans-serif' => 'Arial',
'qumpellkano12regular,Arial,Helvetica,sans-serif' => 'Bluechip',
'bebasregular,Arial,Helvetica,sans-serif' => 'Bebas',
'Cambria, Georgia, Times, Times New Roman, serif' => 'Cambria',
'Copperplate Light, Copperplate Gothic Light, serif' => 'Copperplate Gothic Light',
'Copperplate Bold, Copperplate Gothic Bold, serif' => 'Copperplate Gothic Bold',
'dayposterblackregular,Arial,Helvetica,sans-serif' => 'DayPoster Black',
'florante_at_lauraregular,Arial,Helvetica,sans-serif' => 'Florante at Laura',
'fontleroybrownregular,Arial,Helvetica,sans-serif' => 'FontLeroy Brown',
'forqueregular,Arial,Helvetica,sans-serif' => 'Forque',
'Futura, Century Gothic, AppleGothic, sans-serif' => 'Futura, Century Gothic',
'Garamond, Hoefler Text, Times New Roman, Times, serif' => 'Garamond',
'Georgia, Times, Times New Roman, serif' => 'Georgia',
'GillSans, Calibri, Trebuchet MS, sans-serif' => 'GillSans, Calibri',
'Impact, Haettenschweiler, Arial Narrow Bold, sans-serif' => 'Impact',
'Monotype Corsiva, Arial, sans-serif' => 'Monotype Corsiva',
'kingthings_exeterregular,Arial,Helvetica,sans-serif' => 'Kingthings Exeter',
'Lucida Console,Monaco,monospace' => 'Lucida Console, Monaco, monospace',
'Lucida Sans Unicode,Lucida Grande,sans-serif' => 'Lucida Sans',
'Palatino Linotype,Book Antiqua,Palatino,serif' => 'Palatino Linotype, Book Antiqua, serif',
'roboto_slabbold,Arial,Helvetica,sans-serif' => 'Roboto Slab Bold',
'roboto_slabregular,Arial,Helvetica,sans-serif' => 'Roboto Slab Regular',
'Tahoma,Geneva,sans-serif' => 'Tahoma, Geneva',
'Trebuchet MS,Arial,Helvetica,sans-serif' => 'Trebuchet MS',
'Verdana,Geneva,sans-serif' => 'Verdana, Geneva',
);

$bodyfonts = array(
'',
'Arial, Helvetica, sans-serif' => 'Arial, Helvetica',
'Cambria, Georgia, Times, Times New Roman, serif' => 'Cambria, Georgia, Times',
'Copperplate Light, Copperplate Gothic Light, serif' => 'Copperplate Light',
'Futura, Century Gothic, AppleGothic, sans-serif' => 'Futura, Century Gothic, AppleGothic',
'Garamond, Hoefler Text, Times New Roman, Times, serif' => 'Garamond',
'Georgia, Times, Times New Roman, serif' => 'Georgia, Times',
'GillSans, Calibri, Trebuchet MS, sans-serif' => 'GillSans, Calibri',
'Lucida Console, Monaco, monospace' => 'Lucida Console, Monaco',
'Lucida Sans Unicode, Lucida Grande, sans-serif' => 'Lucida Sans Unicode',
'Monotype Corsiva, Arial, sans-serif' => 'Monotype Corsiva',
'Palatino Linotype,Book Antiqua,Palatino,serif' => 'Palatino Linotype, Book Antiqua',
'roboto_slabbold,Arial,Helvetica,sans-serif' => 'Roboto Slab Bold',
'roboto_slabregular,Arial,Helvetica,sans-serif' => 'Roboto Slab Regular',
'Tahoma, Geneva, sans-serif' => 'Tahoma, Geneva',
'Trebuchet MS, Helvetica, sans-serif' => 'Trebuchet MS, Helvetica',
'Verdana,Geneva,sans-serif' => 'Verdana, Geneva'
);
$fontsizes = array('','11px','12px','13px','14px','16px','18px','20px','22px','24px','26px','28px','30px','32px','35px','36px','38px','40px','41px','44px','48px','52px','56px','60px');
// @new custom color list, list default 1st
$colors = array('green', 'red','orange','lime','blue','light blue','violet','bronze brown','sand','gray');

/* @new choices */
$schema_itemtypes = array(
'LocalBusiness' => 'Local Business',
'ProfessionalService' => 'Professional Service',
'AccountingService' => 'Accounting Service',
'Attorney' => 'Attorney',
'Dentist' => 'Dentist',
'Electrician' => 'Electrician',
'FinancialService' => 'Financial Service',
'GeneralContractor' => 'General Contractor',
'HousePainter' => 'House Painter',
'InsuranceAgency' => 'Insurance Agency',
'Locksmith' => 'Locksmith',
'Notary' => 'Notary',
'Plumber' => 'Plumber',
'RoofingContractor' => 'Roofing Contractor');
$options = array();
$options[] = array( 'name' => __('Welcome','crucible'),
					'type' => 'heading');
$options[] = array( 'name' => sprintf( __('Welcome to %s by Smartest Themes!','crucible'), $themename ),
				'type' => 'info',
				'std' => __('Your business website is up and running. On the left are tabs to customize your site, but everything is optional.<br /><br />To make your website more complete, enter the <strong>Business Info</strong> tab on the left. <br /><br />Then, take a moment to browse all the tabs so you can see what options are available. You can upload your logo in Appearance -> Customize.<br /><br />To get started, first click the \'<strong>Save all Changes</strong>\' button to save the theme defaults.','crucible') );

/* Business */

$options[] = array( 'name' => __('Business Info','crucible'),'class' => 'money',
					'type' => 'heading');
					
$options[] = array( 'name' => __('Business Name','crucible'),
					'desc' => __('Enter the name of your business or organization.','crucible'),
					'id' => $shortname.'_business_name',
					'type' => 'text');
$options[] = array( 'name' => __('Attention Grabber For Homepage','crucible'),
                    'desc' => __('The large tag line shown under the slider on the home page. For example, "How can we help you?"','crucible'),
                    'id' => $shortname.'_attention_grabber',
				'std' => __( 'How can we help you?', 'crucible' ),
                    'type' => 'text');
$options[] = array( 'name' => __('Business Street Address','crucible'),
                    'desc' => __('The street address of your business','crucible'),
                    'id' => $shortname.'_address_street',
                    'type' => 'text');
$options[] = array(
                    'desc' => __('Business suite or apartment number','crucible'),
                    'id' => $shortname.'_address_suite',
                    'type' => 'text');
$options[] = array(
                    'desc' => __('Business city','crucible'),
                    'id' => $shortname.'_address_city',
				'class' => 'half',
                    'type' => 'text');
$options[] = array( 
                    'desc' => __('Business state: if in the U.S., enter the state that your business is in','crucible'),
                    'id' => $shortname.'_address_state',
				'class' => 'half',
                    'type' => 'text');
$options[] = array(
                    'desc' => __('Business zip code','crucible'),
                    'id' => $shortname.'_address_zip',
				'class' => 'half',
                    'type' => 'text');
$options[] = array(
                    'desc' => __('Business Country: the country that your business is in','crucible'),
                    'id' => $shortname.'_address_country',
				'class' => 'half',
                    'type' => 'text');
$options[] = array( 'name' => __('Business Phone Number','crucible'),
                    'desc' => __('Optional. Your business phone number to be displayed on your Contact page. Example: 555-555-5555.','crucible'),
                    'id' => $shortname.'_phone_number',
                    'type' => 'text');
$options[] = array( 'name' => __('Business Fax Number','crucible'),
                    'desc' => __('Optional. Your business fax number to be displayed on your Contact page. Example: 555-555-5555.','crucible'),
                    'id' => $shortname.'_fax_numb',
                    'type' => 'text');
$options[] = array( 'name' => __('Display Business Email Address?','crucible'),
					'desc' => sprintf(__('Check this to show your business email address on your Contact Page. You can change your email address in %s.', 'crucible'), $slink ),
					'id' => $shortname.'_show_contactemail',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array( 'name' => __('Google Map','crucible'),
                    'desc' => sprintf(__('If you want to show a Google Map for your business address, paste here your HTML embed code from %s.','crucible'), '<a href="http://maps.google.com" target="_blank">Google Maps</a>' ),
                    'id' => $shortname.'_google_map',
                    'std' => '',
                    'type' => 'textarea');
$options[] = array( 'name' => __('Business Hours','crucible'),
						'desc' => __('Optional. Enter your hours here if you want to display them. Example:<br /><br />Monday - Friday: 7:30 am - 6:00<br />Saturday: 7:30 am - Noon<br /><br />', 'crucible'),
					'id' => $shortname.'_hours',
					'std' => '',
					'type' => 'textarea');
/* Preferences */
$options[] = array( 'name' => __('Preferences','crucible'),'class' => 'pencil',
					'type' => 'heading');
$options[] = array( 'name' => __('Add Staff section?','crucible'),
					'desc' => __('Check this to show your staff memebers.','crucible'),
					'id' => $shortname.'_show_staff',
					'std' => 'true',
					'type' => 'checkbox');
$options[] = array(
                    'desc' => __('Optional: Enter a custom title for the Staff Page. Default is \'Meet The Staff\'','crucible'),
                    'id' => $shortname.'_business_staffpagetitle',
					'std' => '',
                    'type' => 'text');
$options[] = array(
                    'desc' => __('Optional: Enter a custom title for the Staff menu label . Default is \'Staff\'','crucible'),
                    'id' => $shortname.'_business_staffmenulabel',
					'std' => '',
                    'type' => 'text');
$options[] = array( 'name' => __('Add Announcements section?','crucible'),
					'desc' => __('Check this to add an Announcements (News) section.','crucible'),
					'id' => $shortname.'_show_news',
					'std' => 'true',
					'type' => 'checkbox');
$options[] = array( 
                    'desc' => __('Optional: Enter a custom title for the News Page. Default is \'Announcements\'','crucible'),
                    'id' => $shortname.'_business_newspagetitle',
					'std' => '',
                    'type' => 'text');
$options[] = array(
                    'desc' => __('Optional: Enter a custom title for the News menu label. Default is \'News\'','crucible'),
                    'id' => $shortname.'_business_newsmenulabel',
					'std' => '',
                    'type' => 'text');

$options[] = array( 'name' => __('Add Services?','crucible'),
					'desc' => __('Check this to show your services.','crucible'),
					'id' => $shortname.'_show_services',
					'std' => 'true',
					'type' => 'checkbox');

$options[] = array( 
                    'desc' => __('Optional: Enter a custom title for the Services Page. Default is \'Services\'','crucible'),
                    'id' => $shortname.'_business_servicespagetitle',
					'std' => '',
                    'type' => 'text');
					
$options[] = array(
                    'desc' => __('Optional: Enter a custom title for the Services menu label. Default is \'Services\'','crucible'),
                    'id' => $shortname.'_business_servicesmenulabel',
					'std' => '',
                    'type' => 'text');

$options[] = array( 	'desc' => sprintf( __('%s Set Custom Sort-Order? %s Check this to set a custom sort-order for services. Default sort-order is descending order by date of post.','crucible'), '<strong>', '</strong>' ),
					'id' => $shortname.'_enable_service_sort',
					'std' => 'false',
					'type' => 'checkbox');

$options[] = array( 'name' => __('Add Reviews Section?','crucible'),
					'desc' => __('Check this to add a page to let visitors submit reviews.','crucible'),
					'id' => $shortname.'_add_reviews',
					'std' => 'true',
					'type' => 'checkbox');

// @new comment out NEXT 3 OPTIONS if not using home slideshow.
$options[] = array( 'name' => __('Show Slideshow on Homepage?','crucible'),
					'desc' => __('Check this if you want to show the slideshow on your homepage.','crucible'),
					'id' => $shortname.'_show_slider',
					'std' => 'true',
					'type' => 'checkbox');
$options[] = array( 'name' => __('Slide Duration','crucible'),
                    'desc' => __('How many seconds do you want to pause on each slide?','crucible'),
                    'id' => $shortname.'_slide_duration',
                    'std' => '5',
                    'type' => 'select',
                    'options' => array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15'));
$options[] = array( 'name' => __('Slideshow Transition Speed','crucible'),
                    'desc' => __('How fast do you want the transition between images to be? (in milliseconds)','crucible'),
                    'id' => $shortname.'_slider_trans_speed',
                    'std' => '800',
                    'type' => 'select',
                    'options' => array('300','400','500','600','700','800','900','1000','1100','1200','1300','1400','1500','1600','1700','1800','1900','2000'));
		
$options[] = array( 'name' => __('Force Crop Images to Fit The Slider Size?','crucible'),
					'desc' => __('Check this if you want the slider to crop your images to fit the slider. If left unchecked, images are resized instead of cropped so that the entire image is seen.','crucible'),
					'id' => $shortname.'_force_crop_slider',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array( 'name' => __('Change Slideshow Height','crucible'),
                    'desc' => __('Enter a height in pixels, for example "400px". If left blank, default height is 300px.','crucible'),
                    'id' => $shortname.'_slideshow_height',
                    'std' => '',
                    'type' => 'text');
					
/* Custom CSS */
$options[] = array(
	'name' => __('Custom CSS','crucible'),'class' => 'colors',
	'type' => 'heading'
);
$options[] = array(
	'name' => __('Custom CSS','crucible'),
	'desc' => __('Quickly add CSS to your theme by pasting it here. Paste only CSS, no HTML style tags.','crucible'),
	'id' => $shortname.'_custom_css',
	'std' => '',
	'type' => 'textarea'
);

/* Fonts 
@TODO PERHAPS  THESE ALSO IN CUSTOMIZER
*/
$options[] = array( 'name' => __('Fonts','crucible'),'class' => 'typography',
					'type' => 'heading'); 
$options[] = array( 'name' => __('Customize Heading Fonts','crucible'),
						'type' => 'info',
						'std' => __('This page is optional. The default fonts are nice. Leave these options blank to use the default.','crucible'));
$options[] = array( 'name' => __('Body Text Color','crucible'),
					'desc' => __('The color of your main body text.','crucible'),
					'id' => $shortname.'_body_font_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Headings Text Color','crucible'),
					'desc' => __('The color for page titles and other headings.','crucible'),
					'id' => $shortname.'_heading_font_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Main Menu Text Color','crucible'),
					'desc' => __('The color of your site\'s main menu text.','crucible'),
					'id' => $shortname.'_menu_text_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Main Menu Text Hover Color','crucible'),
					'desc' => __('The color of the main menu text when hovered over.','crucible'),
					'id' => $shortname.'_menu_hover_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Footer Text Color','crucible'),
					'desc' => __('The color of your site\'s footer text.','crucible'),
					'id' => $shortname.'_footer_text_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Attention Grabber Color','crucible'),
					'desc' => __('The color of your home page Attention Grabber text.','crucible'),
					'id' => $shortname.'_attention_grabber_color',
					'std' => '',
					'type' => 'color');
					
$options[] = array( 'name' => __('Attention Grabber Font','crucible'),
					'desc' => __('Select which font you would like to use for your Attention Grabber text.  Leave blank to use the default.','crucible'),
					'id' => $shortname.'_attention_grabber_font',
					'type' => 'select2',
					'class' => 'clearfix',
					'std' => '',
					'options' => $headfonts);
$options[] = array( 'name' => __('Attention Grabber Font Size','crucible'),
					'desc' => __('Select a font-size for your Attention Grabber. Leave blank to use the default.','crucible'),
					'id' => $shortname.'_attention_grabber_font_size',
					'type' => 'select',
					'std' => '',
					'options' => $fontsizes);
$options[] = array( 'name' => __('Body Font','crucible'),
					'desc' => __('Select which font you would like to use for your main body text.','crucible'),
					'id' => $shortname.'_body_font',
					'type' => 'select2',
					'std' => '',
					'class' => 'clearfix',
					'options' => $bodyfonts);
$options[] = array( 'name' => __('Body Font Size','crucible'),
					'desc' => __('Select a font-size for your body.','crucible'),
					'id' => $shortname.'_body_font_size',
					'type' => 'select',
					'std' => '',
					'options' => $fontsizes);
$options[] = array( 'name' => __('Heading Font','crucible'),
					'desc' => __('Select which font you would like to use for your headings.','crucible'),
					'id' => $shortname.'_heading_font',
					'type' => 'select2',
					'std' => '',
					'options' => $headfonts);
$options[] = array( 'name' => __('H1 Heading Font Size','crucible'),
					'desc' => __('Optional: select a font-size for your H1 heading tags.','crucible'),
					'id' => $shortname.'_heading_one_font_size',
					'type' => 'select',
					'std' => '',
					'options' => $fontsizes);
$options[] = array( 'name' => __('H2 Heading Font Size','crucible'),
					'desc' => __('Optional: select a font-size for your H2 heading tags.','crucible'),
					'id' => $shortname.'_heading_two_font_size',
					'type' => 'select',
					'std' => '',
					'options' => $fontsizes);
$options[] = array( 'name' => __('H3 Heading Font Size','crucible'),
					'desc' => __('Optional: select a font-size for your H3 heading tags.','crucible'),
					'id' => $shortname.'_heading_three_font_size',
					'type' => 'select',
					'std' => '',
					'options' => $fontsizes);
$options[] = array( 'name' => __('H4 Heading Font Size','crucible'),
					'desc' => __('Optional: select a font-size for your H4 heading tags.','crucible'),
					'id' => $shortname.'_heading_four_font_size',
					'type' => 'select',
					'std' => '',
					'options' => $fontsizes);
/* About Page */
$options[] = array( 'name' => __('About Page','crucible'),'class' => 'aboutcircle',
					'type' => 'heading');
					
$options[] = array( 'name' => __('About Your Business','crucible'),
						'desc' => __('The \'About Page\' is a page about your business. Type what you want your visitors to read here. It may be a history, a sales pitch, or anything you like. To enlarge the text area, drag the lower right corner down.', 'crucible'),
					'id' => $shortname.'_about_page',
					'std' => '',
					'type' => 'textarea');

$options[] = array( 'name' => __('About Page Picture','crucible'),
					'desc' => __('Upload a picture for your About page, or specify the image address of an online picture, like http://yoursite.com/picture.png','crucible'),
					'id' => $shortname.'_about_picture',
					'std' => '',
					'type' => 'upload');
$options[] = array( 'name' => __('Disable About Page','crucible'),
					'desc' => __('Check this to disable the About page altogether. This will delete the automatically-created About page.', 'crucible'),
					'id' => $shortname.'_stop_about',
					'std' => 'false',
					'type' => 'checkbox');

/* Social Media */
$options[] = array( 'name' => __('Social Media','crucible'),'class' => 'smartsocial',
					'type' => 'heading');
					
$options[] = array( 'name' => __('Facebook Page','crucible'),
                    'desc' => sprintf(__('The ID of your business Facebook page. Tip: the part of the address that comes after %1$swww.facebook.com/%2$s','crucible'), '<code>', '</code>'),
                    'id' => $shortname.'_business_facebook',
                    'type' => 'text');

$options[] = array( 'name' => __('Twitter','crucible'),
                    'desc' => __('The username of your business Twitter profile. Tip: the part after \'@\'','crucible'),
                    'id' => $shortname.'_business_twitter',
                    'type' => 'text');

$options[] = array( 'name' => __('Google Plus','crucible'),
                    'desc' => __('The ID of your business Google Plus page.','crucible'),
                    'id' => $shortname.'_business_gplus',
                    'type' => 'text');

$options[] = array( 'name' => __('YouTube','crucible'),
                    'desc' => sprintf(__('The name of your YouTube channel. Tip: Your Youtube name or ID, or the part of the address after %1$swww.youtube.com/user/%2$s','crucible'), '<code>', '</code>'),
                    'id' => $shortname.'_business_youtube',
                    'type' => 'text');

$options[] = array( 'name' => __('Linkedin','crucible'),
                    'desc' => sprintf(__('Your company Linkedin profile. The part of the profile address after %1$swww.linkedin.com/%2$s.', 'crucible'), '<code>', '</code>'),
                    'id' => $shortname.'_business_linkedin',
                    'type' => 'text');

					
$options[] = array( 'name' => __('Instagram','crucible'),
                    'desc' => sprintf(__('Your company\'s Instagram username. The part of the profile address after %1$swww.instagram.com/%2$s. ', 'crucible'), '<code>', '</code>'),
                    'id' => $shortname.'_business_instagram',
                    'type' => 'text');

$options[] = array( 'name' => __('Pinterest','crucible'),
                    'desc' => sprintf(__('Your company\'s Pinterest username. The part of the address after %1$swww.pinterest.com/%2$s. You can enter just the username, or a particular board, such as %1$susername/BOARD_NAME%2$s', 'crucible'), '<code>', '</code>'),
                    'id' => $shortname.'_business_pinterest',
                    'type' => 'text');

$options[] = array( 'name' => __('Another Profile','crucible'),
                    'desc' => __('Add another business profile URL.  Example: https://www.flickr.com/photos/YourUserName','crucible'),
                    'id' => $shortname.'_business_socialurl1',
                    'type' => 'text');
$options[] = array(
                    'desc' => __('Give a title for the business profile you entered above. Example: Flickr','crucible'),
                    'id' => $shortname.'_business_sociallabel1',
                    'type' => 'text');

$options[] = array(
	'name' => __('Another Profile','crucible'),
	'desc' => __('Add another business profile URL. Example: http://YourName.tumblr.com/','crucible'),
	'id' => $shortname.'_business_socialurl2',
	'type' => 'text'
);
$options[] = array(
	'desc' => __('Give a title for the business profile you entered above. Example: Tumblr','crucible'),
	'id' => $shortname.'_business_sociallabel2',
	'type' => 'text'
);

/* SEO */
$options[] = array(
	'name' => __('SEO','crucible'),
	'class' => 'seo',
	'type' => 'heading'
);
$options[] = array( 
	'type' => 'info',
	'std' => __('<strong>Search Engine Optimization:</strong> &nbsp;&nbsp; Let\'s get your site found.','crucible')
);
$options[] = array(
	'name' => __('Business Type','crucible'),
	'desc' => __('Select the main category of your business. This is Microdata to maximize your search-engine ranking.','crucible'),
	'id' => $shortname.'_business_itemtype',
	'type' => 'select2',
	'std' => 'LocalBusiness',
	'options' => $schema_itemtypes
);
$options[] = array(
	'name' => __('Home Page Meta Title','crucible'),
	'desc' => __('Enter a title for your site for search engines to see.','crucible'),
	'id' => $shortname.'_home_meta_title',
	'type' => 'text'
);
$options[] = array(
	'name' => __('Home Page Meta Description','crucible'),
	'desc' => __('Enter a keyword-rich description of your site for search engines to see.','crucible'),
	'id' => $shortname.'_home_meta_desc',
	'type' => 'textarea'
);
$options[] = array(
'name' => __('Home Page Meta Keywords','crucible'),
	'desc' => __('Enter some keywords, seperated by commas, about your site for search engines to see.','crucible'),
	'id' => $shortname.'_home_meta_key',
	'type' => 'text'
);
$options[] = array(
	'name' => __('Disable SEO Meta Tags','crucible'),
	'desc' => __('Description meta tags will include the category description on category pages, tag description on tag pages, and post excerpts on single posts and pages. <strong>Check this to disable</strong> description and keyword meta tags. This will also disable the robots meta tag that gets added to page 2 and up of your archives. Check this option if you prefer to use a separate plugin for SEO.', 'crucible'),
	'id' => $shortname.'_disable_seo',
	'std' => 'false',
	'type' => 'checkbox'
);


/* Footer */
$options[] = array( 'name' => __('Footer','crucible'),'class' => 'footer',
					'type' => 'heading');
$options[] = array( 'name' => __('Footer Text','crucible'),
                    'desc' => __('Add some text or basic html (strong, a, em, br, etc) to the footer area. By default, this will go <strong>under</strong> the current copyright notice on your footer.<br /><br />To override the default copyright notice, check below.','crucible'),
                    'id' => $shortname.'_footer_text',
                    'std' => '',
                    'type' => 'textarea');
$options[] = array( 'name' => __('Override the Default Footer Copyright Notice','crucible'),
					'desc' => __('Check this to remove the default copyright text on the footer. This will allow your custom Footer text (that you entered above) to completely replace any default footer.', 'crucible'),
					'id' => $shortname.'_override_footer',
					'std' => 'false',
					'type' => 'checkbox');
/* Branding */
$options[] = array( 'name' => __('Backend Branding','crucible'),'class' => 'branding',
					'type' => 'heading');
$options[] = array( 'name' => __('Replace This Page\'s Logo','crucible'),
					'desc' => __('See the "Smartest Themes" logo at the top of this page? Upload a logo here to replace this page\'s logo. Or specify the image address of your online logo, like http://yoursite.com/logo.png','crucible'),
					'id' => $shortname.'_backend_logo',
					'std' => '',
					'type' => 'upload');
$options[] = array( 'name' => __('Custom WP Admin Footer Text','crucible'),
                    'desc' => __('By default, the text at the bottom of this page is "Thank you for creating with WordPress." Replace it with your own custom text here.','crucible'),
                    'id' => $shortname.'_admin_footer',
                    'type' => 'textarea');
$options[] = array( 
                    'desc' => __('Or check here to completely remove the Admin Footer Text.','crucible'),
                    'id' => $shortname.'_remove_adminfooter',
                    'type' => 'checkbox');

$options[] = array( 'name' => __('Remove WordPress Links From Admin Bar','crucible'),
					'desc' => __('See the Wordpress link on the left of the bar across the top of this page? Check here to remove that link.','crucible'),
					'id' => $shortname.'_remove_wplinks',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array( 'type' => 'info',
						'std' => __('<em>Refresh this page to see the effect of these changes.</em>','crucible')
						);
/* Contact form */
$options[] = array( 'name' => __( 'Contact Form','crucible' ),
		'class' => 'mail',
		'type' => 'heading');
$options[] = array( 'name' => __( 'Your Name', 'crucible' ),
		'desc' => __( 'How would you like to be addressed in messages sent from the contact form?', 'crucible' ),
		'id' => $shortname.'_contactform_name',
		'std' => $admin_name,
		'type' => 'text');
$options[] = array( 'name' => __( 'Your Email', 'crucible' ),
		'desc' => __( 'Where would you like to receive messages sent from the contact form? If blank, the default is the admin email set in Settings -> General', 'crucible' ),
		'id' => $shortname.'_contactform_email',
		'std' => '',
		'type' => 'text');
$options[] = array( 'name' => __( 'Show "From:" Your Visitor', 'crucible' ),
		'desc' => sprintf(__( 'Check this box if you want the %1$s"From:"%2$s in the email header to be the name and email of the visitor. Please note that some web hosts, such as DreamHost and BlueHost, will not send these messages. (GoDaddy hosting is okay with this option.) Test this option after you enable it. If left unchecked, the default is "From" your email setting from above.','smartestb' ), '<strong>', '</strong>'),
		'id' => $shortname.'_contactform_header_from',
		'std' => 'false',
		'type' => 'checkbox');
$options[] = array( 'name' => __( 'Default Subject', 'crucible' ),
		'desc' => __( 'What should be the default subject line for the contact messages? Default is "Message sent from your contact form".', 'crucible' ),
		'id' => $shortname.'_contactform_subject',
		'std' => __( 'Message sent from your contact form', 'crucible' ),
		'type' => 'text');
$options[] = array( 'name' => __( 'Success Message', 'crucible' ),
		'desc' => __( 'When the form is sucessfully submitted, this message will be displayed to the sender. Default is "Success! Your message has been sent."', 'crucible' ),
		'id' => $shortname.'_contactform_success',
		'std' => '<strong>' . __( 'Success! ', 'crucible' ) . '</strong> ' . __( 'Your message has been sent.', 'crucible'),
		'type' => 'textarea');
$options[] = array( 'name' => __( 'Error Message', 'crucible' ),
		'desc' => __( 'If the user skips a required field, this message will be displayed. Default is "Please complete the required fields."', 'crucible' ),
		'id' => $shortname.'_contactform_error',
		'std' => '<strong>' . __( 'Please complete the required fields.', 'crucible' ) . '</strong>',
		'type' => 'textarea');
$options[] = array( 'name' => __( 'Enable Captcha', 'crucible' ),
		'desc' => __( 'Check this box if you want to enable the captcha (challenge question/answer).', 'crucible' ),
		'id' => $shortname.'_contactform_captcha',
		'std' => 'true',
		'type' => 'checkbox');
$options[] = array( 'name' => __( 'Time Offset', 'crucible' ), 
		'desc' => sprintf( __( 'Please specify the time offset from the "Current time" listed below. For example, +1 or -1. If no offset, enter "0" (zero).<br />Current time: %s <br /><br />', 'crucible' ), $currtime ),
		'id' => $shortname.'_contactform_offset',
		'std' => '',
		'type' => 'text');
$options[] = array( 'name' => __( 'Add Phone Number Field', 'crucible' ),
		'desc' => __( 'Check this box to add a phone number field to the contact form.', 'crucible' ),
		'id' => $shortname.'_contactform_include_phone',
		'std' => 'false',
		'type' => 'checkbox');
$options[] = array(
		'desc' => sprintf(__( 'Make the phone number %srequired.%s This has no effect if you do not check the box above.', 'crucible' ), '<strong>', '</strong>' ),
		'id' => $shortname.'_contactform_required_phone',
		'std' => 'false',
		'type' => 'checkbox');
$options[] = array( 'name' => __( 'Custom content before the form', 'crucible' ),
		'desc' => __( 'Add some text/markup to appear <em>before</em> the contact form (optional).', 'crucible' ),
		'id' => $shortname.'_contactform_preform',
		'std' => '',
		'type' => 'textarea');
$options[] = array( 'name' => __( 'Custom content after the form', 'crucible' ),
		'desc' => __( 'Add some text/markup to appear <em>after</em> the contact form (optional).', 'crucible' ),
		'id' => $shortname.'_contactform_appform',
		'std' => '',
		'type' => 'textarea');
$options[] = array( 'name' => __( 'Custom content before results', 'crucible' ),
		'desc' => __( 'Add some text/markup to appear <em>before</em> the success message (optional).', 'crucible' ),
		'id' => $shortname.'_contactform_prepend',
		'std' => '',
		'type' => 'textarea');
$options[] = array( 'name' => __( 'Custom content after results', 'crucible' ),
		'desc' => __( 'Add some text/markup to appear <em>after</em> the success message (optional).', 'crucible' ),
		'id' => $shortname.'_contactform_append',
		'std' => '',
		'type' => 'textarea');
/* Scripts */
$options[] = array(
	'name' => __('Scripts','crucible'),
	'class' => 'scripts',
	'type' => 'heading'
);
$options[] = array(
	'name' => __('Analytics Code','crucible'),
	'desc' => __('Paste your analytics script here.','crucible'),
	'id' => $shortname.'_script_analytics',
	'std' => '',
	'type' => 'textarea'
);
$options[] = array(
	'name' => __('Additional Scripts To Load','crucible'),
	'desc' => __('Paste any scripts here to be loaded into wp_head. Remember your script tags.','crucible'),
	'id' => $shortname.'_scripts_head',
	'std' => '',
	'type' => 'textarea'
);
/* Advanced */
$options[] = array(
	'name' => __('Advanced','crucible'),
	'class' => 'settings',
	'type' => 'heading'
);
$options[] = array( 'name' => __('Disable Automatic Smartest Theme Actions','crucible'),
						'type' => 'info',
						'std' => __('This Smartest Theme does things by default that regular themes don\'t do. You may decide that you don\'t need such performance.<br /><br />Here you can disable some of these actions.','crucible'));
$options[] = array( 'name' => __('Front Page and Posts Page Settings','crucible'),
					'desc' => sprintf(__('Check this to stop forcing \'Posts Page\' setting to a page titled \'Blog\'. Checking this will allow you to choose your own Posts Page in %s.', 'crucible'), $rlink),
					'id' => $shortname.'_stop_blog',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array('desc' => sprintf(__('Check this to stop forcing \'Static Front Page\' setting to \'Home\'. Checking this will allow you to choose your own static Front Page in %s.', 'crucible'), $rlink),
					'id' => $shortname.'_stop_static',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array( 'name' => __('Disable Automatic Home Page Creation','crucible'),
					'desc' => __('Check this to stop the page titled "Home" to be automatically created every time you delete it. This will permanently delete the automatically-created Home page.', 'crucible'),
					'id' => $shortname.'_stop_home',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array( 'name' => __('Disable Contact Page','crucible'),
					'desc' => sprintf( __( 'Check this to disable the Contact page. This will delete the automatically-created Contact page. You will still be able to use the shortcode to add a contact form: %s', 'crucible' ), '<code>[smartestthemes_contact_form]</code>' ),
					'id' => $shortname.'_stop_contact',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array( 'name' => __('Disable Social Share Buttons','crucible'),
	'desc' => __('Check this to stop inserting Facebook Like, Tweet, and Google +1 buttons at the bottom of single posts.', 'crucible'),
	'id' => $shortname.'_stop_smartshare',
	'std' => 'false',
	'type' => 'checkbox');
$options[] = array( 'name' => __('Disable Announcements Icon','crucible'),
	'desc' => __('If an Announcement (News) post does not have a featured image, a cute icon will show up as its featured image in the News archives and in the Featured Announcements widget. Check this to get rid of that icon.', 'crucible'),
	'id' => $shortname.'_stop_theme_icon',
	'std' => 'false',
	'type' => 'checkbox');
			
update_option('smartestthemes_template',$options);
update_option('st_manual',$manualurl);
update_option('st_sshow_description','Images of size 980 (width) x 300 look best. However, the slider is responsive and will work with any size of images.');// @new update
}
add_action('init','smartestthemes_options');
?>