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

$sampleimg = '<br /><br /><img alt="logo text sample" src="'. get_bloginfo('template_directory') . '/images/text-logo-sample.png" /><br /><br />';

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
// @new @todo create new font demos with theme name and colors
$demoImgUrl = get_bloginfo('template_directory') . '/images/demo-';

/*
// @new choose logo fonts
// @new list first the default logo font
array[]=>
	[0]=> visible option
	[1]=> demo image url
	[2]=> css output
*/
$logofonts = array(
array( 'Forque', $demoImgUrl . 'forque.png', 'forqueregular,Arial,Helvetica,sans-serif' ),
array( 'Bebas', $demoImgUrl . 'bebas.png', 'bebasregular,Arial,Helvetica,sans-serif' ),
array( 'Bluechip', $demoImgUrl . 'bluechip.png', 'qumpellkano12regular,Arial,Helvetica,sans-serif' ),
array( 'DayPoster Black', $demoImgUrl . 'dayposterblack.png', 'dayposterblackregular,Arial,Helvetica,sans-serif' ),
array( 'Florante at Laura', $demoImgUrl . 'florante-at-laura.png', 'florante_at_lauraregular,Arial,Helvetica,sans-serif' ),
array( 'FontLeroy Brown', $demoImgUrl . 'fontleroy-brown.png', 'fontleroybrownregular,Arial,Helvetica,sans-serif' ),
array( 'Kingthings Exeter', $demoImgUrl . 'kingthings-exeter.png', 'kingthings_exeterregular,Arial,Helvetica,sans-serif' ),
array( 'Roboto Slab Regular', $demoImgUrl . 'roboto-slab.png', 'roboto_slabregular,Arial,Helvetica,sans-serif' ),
array( 'Roboto Slab Bold', $demoImgUrl . 'roboto-slab-bold.png', 'roboto_slabbold,Arial,Helvetica,sans-serif' ),
array( 'Arial', $demoImgUrl . 'arial.png', 'Arial,Helvetica,sans-serif' ),
array( 'Cambria', $demoImgUrl . 'cambria.png', 'Cambria, Georgia, Times, Times New Roman, serif' ),
array( 'Copperplate Gothic Light', $demoImgUrl . 'copperplate-gothic-light.png', 'Copperplate Light, Copperplate Gothic Light, serif' ),
array( 'Copperplate Gothic Bold', $demoImgUrl . 'copperplate-gothic-bold.png', 'Copperplate Bold, Copperplate Gothic Bold, serif' ),
array( 'Futura, Century Gothic', $demoImgUrl . 'century-gothic.png', 'Futura, Century Gothic, AppleGothic, sans-serif' ),
array( 'Garamond', $demoImgUrl . 'garamond.png', 'Garamond, Hoefler Text, Times New Roman, Times, serif' ),
array( 'Georgia', $demoImgUrl . 'georgia.png', 'Georgia, Times, Times New Roman, serif' ),
array( 'GillSans, Calibri', $demoImgUrl . 'gillsans.png', 'GillSans, Calibri, Trebuchet MS, sans-serif' ),
array( 'Impact', $demoImgUrl . 'impact.png', 'Impact, Haettenschweiler, Arial Narrow Bold, sans-serif' ),
array( 'Monotype Corsiva', $demoImgUrl . 'monotype-corsiva.png', 'Monotype Corsiva, Arial, sans-serif' ),
array( 'Palatino Linotype, Book Antiqua, serif', $demoImgUrl . 'palatino.png', 'Palatino Linotype,Book Antiqua,Palatino,serif' ),
array( 'Lucida Sans', $demoImgUrl . 'lucida-sans.png', 'Lucida Sans Unicode,Lucida Grande,sans-serif' ),
array( 'Tahoma, Geneva', $demoImgUrl . 'tahoma.png', 'Tahoma,Geneva,sans-serif' ),
array( 'Trebuchet MS', $demoImgUrl . 'trebuchet-ms.png', 'Trebuchet MS,Arial,Helvetica,sans-serif' ),
array( 'Verdana, Geneva', $demoImgUrl . 'verdana.png', 'Verdana,Geneva,sans-serif' ),
array( 'Lucida Console, Monaco, monospace', $demoImgUrl . 'lucida-console.png', 'Lucida Console,Monaco,monospace' ),
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

/* @new choices*/
$textures = array(
'none' => 'none',
'argyle' => 'argyle',
'dark_brick_wall' => 'brick wall - dark',
'white_brick_wall' => 'brick wall - light',
'carbon_fibre' => 'carbon fiber',
'carpet' => 'carpet',
'checkered_pattern' => 'checkered pattern',
'circles' => 'circles',
'crissXcross' => 'quilted',
'diagonal_striped_brick' => 'diagonal striped brick',
'double_lined' => 'double lined',
'hexellence' => 'hexellence',
'paven' => 'paven',
'plaid' => 'plaid',
'pinstripe' => 'pinstripe',
'speckled' => 'speckled',
'tiles' => 'tiles',
'wood' => 'wood', );
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
/* Style */
$options[] = array( 'name' => __('Style','crucible'),'class' => 'colors',
					'type' => 'heading');
$options[] = array( 'name' => __('Accent Color','crucible'),
					'desc' => __('Select a color for buttons, links, list bullet-points, and such.','crucible'),
					'id' => $shortname.'_accent_color',
					'std' => 'green',// @new default here
					'type' => 'select',
					'options' => $colors);  
$options[] = array(		'desc' => sprintf( __('Check here to override the Accent Color from above. Then choose a %scustom accent color%s below.','crucible'), '<strong>', '</strong>' ),
					'id' => $shortname.'_override_accent_color',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array(		'desc' => __('Choose a custom Accent Color. You must check the option above to make this work.','crucible'),
					'id' => $shortname.'_custom_accent_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Site Background Color','crucible'),
					'desc' => __('Customize your site\'s background color.','crucible'),
					'id' => $shortname.'_bg_color',
					'std' => '',
					'class' => 'clearfix',
					'type' => 'color');
$options[] = array( 'name' => __('Header Background Color','crucible'),
					'desc' => __('Customize your site\'s header background color.','crucible'),
					'id' => $shortname.'_header_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Footer Background Color','crucible'),
					'desc' => __('Customize your site\'s footer background color.','crucible'),
					'id' => $shortname.'_footer_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Background Texture','crucible'),
					'desc' => __('Select a texture for your site background. This will appear above the background color you set above.','crucible'),
					'id' => $shortname.'_bg_texture',
					'std' => 'none',
					'type' => 'select2',
					'class' => 'clearfix',
					'options' => $textures );
$options[] = array( 'name' => __('Custom Background Image','crucible'),
					'desc' => __('Upload a background image, or specify the image address of your image. (http://yoursite.com/image.png). <strong>NOTE: You must select \'None\' for your texture above for this to take effect.</strong>','crucible'),
					'id' => $shortname.'_bg_image',
					'std' => '',
					'type' => 'upload');

$options[] = array(
					'desc' => '<span class="black">' . __('Fix Background Image? ', 'crucible') . '</span>' . __('Check this if you want your background image to be fixed (no scrolling).','crucible'),
					'id' => $shortname.'_bg_image_fixed',
					'std' => 'false',
					'type' => 'checkbox');
$options[] = array(
					'desc' => '<span class="black">' . __('Background Image Repeat: ', 'crucible') . '</span>' . __('Select how you want your background image to display.','crucible'),
					'id' => $shortname.'_bg_image_repeat',
					'type' => 'select',
					'options' => array('no-repeat', 'repeat', 'repeat-x', 'repeat-y', 'inherit'));
$options[] = array(
					'desc' => '<span class="black">' . __('Background Image Position: ', 'crucible') . '</span>' . __('Select how you want your background image to be aligned.','crucible'),
					'id' => $shortname.'_bg_image_position',
					'type' => 'select',
					'options' => array('left top','left center','left bottom','center top','center center','center bottom','right top','right center','right bottom'));
$options[] = array( 'name' => __('Custom CSS','crucible'),
                    'desc' => __('Quickly add CSS to your theme by pasting it here. Paste only CSS, no HTML style tags.','crucible'),
                    'id' => $shortname.'_custom_css',
                    'std' => '',
                    'type' => 'textarea');
/* Logo */
$options[] = array( 'name' => __('Logo','crucible'),'class' => 'image',
					'type' => 'heading');
/** @test move this option to customizer					
$options[] = array( 'name' => __('Custom Logo Image','crucible'),
					'desc' => __('Upload a logo for your theme, or specify the image address of your online logo, like http://yoursite.com/logo.png','crucible'),
					'id' => $shortname.'_logo',
					'std' => '',
					'type' => 'upload');

					

*
$options[] = array( 'name' => __( 'Show Tagline With Your Logo?', 'crucible' ),
				'desc' => __( 'Check this to show the tagline under your logo. (You can edit your tagline at ettings -> General).', 'crucible' ),
				'id' => $shortname.'_show_tagline',
				'std' => 'false',
				'type' => 'checkbox');
				



$options[] = array( 'name' => __('Optional: Increase Logo Height','crucible'),
					'desc' => __('By default, your logo will shrink to a max height of 150px to fit within the theme\'s header. Preview your site to see this. To increase the size, enter a maximum height greater than 150. Enter just the number. The width increases as the height increases. The logo will stop growing when it reaches a certain width, in order to keep it looking good on all screen sizes.<br /><br /><strong>Tip: banner images of 700px by 150px fit perfectly without needing height modifications.</strong>','crucible'),
					'id' => $shortname.'_increase_logo',
					'std' => '',
					'type' => 'text');
*/


				
$options[] = array( 'name' => __('Text Logo','crucible'),
						'type' => 'info',
						'std' => sprintf(__('The rest of this page is for using a text logo instead of an image. Here you can create a text-based logo with custom fonts and colors like this:%1$s %2$s %1$s Part 1 is all the text before the alternate color. Part 2 is the alternate color text; it is the same size as part 1, but gets its own color. Part 3 is after the alternate color; it is the same size and color as Part 1. Part 4 is the smaller text which gets its own size and color. To make a text logo, only Part 1 is required.','crucible'), '<br />', $sampleimg ));

$options[] = array( 'name' => __('Logo Text Part 1','crucible'),
                    'desc' => __('Enter Part 1 of your logo text here. If you want the whole logo to be 1 color, then enter all of the text here and skip Parts 2 through 4.','crucible'),
                    'id' => $shortname.'_logo_text_part_1',
                    'std' => $bnam,
                    'type' => 'text');
$options[] = array( 'name' => __('Logo Text Part 2','crucible'),
                    'desc' => __('The alternate color part of your logo. It does not have to be a single letter. By default, this text will be the same color as the accent color for the site. You can change this alternate color below. Leave this blank if you do not want alternate color text. ','crucible'),
                    'id' => $shortname.'_logo_text_part_orange',
                    'std' => '',
                    'type' => 'text');
$options[] = array( 'name' => __('Logo Text Part 3','crucible'),
                    'desc' => __('The part after the alternate color. This text is the same color and size of Part 1. If you left Part 2 blank, you do not need to fill this in. Instead, add it to Part 1.','crucible'),
                    'id' => $shortname.'_logo_text_part_3',
                    'std' => '',
                    'type' => 'text');
$options[] = array( 'name' => __('Logo Text Part 4','crucible'),
                    'desc' => __('The text at the end with its own size and color. Leave this blank if you do not want this.','crucible'),
                    'id' => $shortname.'_logo_text_part_small',
                    'std' => '',
                    'type' => 'text');
$options[] = array( 'name' => __('Logo Font','crucible'),
					'desc' => __('Select which font you would like to use for your text-based logo.','crucible'),
					'id' => $shortname.'_logo_font',
					'std' => 'forqueregular,Arial,Helvetica,sans-serif',// @new default
					'type' => 'radio2',
					'options' => $logofonts);
$options[] = array( 'name' => __('Size for Parts 1, 2, and 3','crucible'),
					'desc' => __('Select a font-size for your text-based logo. This size will apply to parts 1, 2, and 3 of the logo.','crucible'),
					'id' => $shortname.'_logo_font_size',
					'type' => 'select',
					'std' => '54px',// @new default
					'options' => $fontsizes);
$options[] = array( 'name' => __('Size for Part 4','crucible'),
					'desc' => __('Select a font-size for your part 4 of the logo.','crucible'),
					'id' => $shortname.'_logo_font_size_4',
					'type' => 'select',
					'std' => '28px',// @new default
					'options' => $fontsizes);
$options[] = array( 'name' => __('Logo Color for Part 1 and 3','crucible'),
					'desc' => __('Customize the color of your logo text. Leave blank to use the default. NOTE: Only in effect if you are not using an image for a logo, but rather just text.','crucible'),
					'id' => $shortname.'_logo_color',
					'std' => '',
					'type' => 'color');						
$options[] = array( 'name' => __('Logo Color for Part 2','crucible'),
					'desc' => __('Customize the alternate color. Leave blank to use the default accent color. NOTE: Only in effect if you are not using an image for a logo, but rather just text.','crucible'),
					'id' => $shortname.'_logo_color_2',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Logo Color for Part 4','crucible'),
					'desc' => __('Customize the color of the smaller logo text. Leave blank to use the default. NOTE: Only in effect if you are not using an image for a logo, but rather just text.','crucible'),
					'id' => $shortname.'_logo_color_4',
					'std' => '',
					'type' => 'color');
				
$options[] = array( 'name' => __('Logo Hover Color','crucible'),
					'desc' => __('Customize the color of your logo text when hovered. NOTE: Only in effect if you are not using an image for a logo, but rather just text.','crucible'),
					'id' => $shortname.'_logo_hover_color',
					'std' => '',
					'type' => 'color');
/* Fonts */
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
$options[] = array( 'name' => __('Tag Line Color','crucible'),
					'desc' => __('The color of your tag line text.','crucible'),
					'id' => $shortname.'_tagline_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Attention Grabber Color','crucible'),
					'desc' => __('The color of your home page Attention Grabber text.','crucible'),
					'id' => $shortname.'_attention_grabber_color',
					'std' => '',
					'type' => 'color');
$options[] = array( 'name' => __('Tag Line Font','crucible'),
					'desc' => __('Select which font you would like to use for your tag line text.  Leave blank to use the default.','crucible'),
					'id' => $shortname.'_tagline_font',
					'type' => 'select2',
					'class' => 'clearfix',
					'std' => '',
					'options' => $headfonts);
$options[] = array( 'name' => __('Tag Line Font Size','crucible'),
					'desc' => __('Select a font-size for your tag line. Leave blank to use the default.','crucible'),
					'id' => $shortname.'_tagline_font_size',
					'type' => 'select',
					'std' => '',
					'options' => $fontsizes);
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

$options[] = array( 'name' => __('Another Profile','crucible'),
                    'desc' => __('Add another business profile URL. Example: http://YourName.tumblr.com/','crucible'),
                    'id' => $shortname.'_business_socialurl2',
                    'type' => 'text');
$options[] = array( 'desc' => __('Give a title for the business profile you entered above. Example: Tumblr','crucible'),
                    'id' => $shortname.'_business_sociallabel2',
                    'type' => 'text');
$options[] = array( 'name' => __('Use Colorful Social Media Buttons','crucible'),
					'desc' => __('Optional. Check this to use colorful social media follow buttons instead of the pale, colorless buttons. Note: the pale buttons do become colorful upon hover, and the colorful buttons become pale upon hover.','crucible'),
					'id' => $shortname.'_colorful_social',
					'std' => 'false',
					'type' => 'checkbox');// @todo remove
/* SEO */
$options[] = array( 'name' => __('SEO','crucible'),'class' => 'seo',
					'type' => 'heading');

$options[] = array( 
						'type' => 'info',
						'std' => __('<strong>Search Engine Optimization:</strong> &nbsp;&nbsp; Let\'s get your site found.','crucible'));

$options[] = array( 'name' => __('Business Type','crucible'),
					'desc' => __('Select the main category of your business. This is Microdata to maximize your search-engine ranking.','crucible'),
					'id' => $shortname.'_business_itemtype',
					'type' => 'select2',
					'std' => 'LocalBusiness',
					'options' => $schema_itemtypes);
					
$options[] = array( 'name' => __('Home Page Meta Title','crucible'),
                    'desc' => __('Enter a title for your site for search engines to see.','crucible'),
                    'id' => $shortname.'_home_meta_title',
                    'type' => 'text');

$options[] = array( 'name' => __('Home Page Meta Description','crucible'),
                    'desc' => __('Enter a keyword-rich description of your site for search engines to see.','crucible'),
                    'id' => $shortname.'_home_meta_desc',
                    'type' => 'textarea');

$options[] = array( 'name' => __('Home Page Meta Keywords','crucible'),
                    'desc' => __('Enter some keywords, seperated by commas, about your site for search engines to see.','crucible'),
                    'id' => $shortname.'_home_meta_key',
                    'type' => 'text');

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
                    'desc' => __( 'Where would you like to receive messages sent from the contact form? If blank, the default is the admin email set in `Settings -> General`', 'crucible' ),
                    'id' => $shortname.'_contactform_email',
					'std' => '',
                    'type' => 'text');
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
					'std' => '<div style="clear:both;">&nbsp;</div>',
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
$options[] = array( 'name' => __('Scripts','crucible'),'class' => 'scripts',
					'type' => 'heading');
					
$options[] = array( 'name' => __('Add Analytics Code','crucible'),
                    'desc' => __('Paste your analytics script here.','crucible'),
                    'id' => $shortname.'_script_analytics',
                    'std' => '',
                    'type' => 'textarea');

$options[] = array( 'name' => __('Additional Scripts To Load','crucible'),
                    'desc' => __('Paste any scripts here to be loaded into wp_head. Remember your script tags.','crucible'),
                    'id' => $shortname.'_scripts_head',
                    'std' => '',
                    'type' => 'textarea');

/* Advanced */
$options[] = array( 'name' => __('Advanced','crucible'),'class' => 'settings',
					'type' => 'heading');
$options[] = array( 'name' => __('Disable Automatic Smartest Theme Actions','crucible'),
						'type' => 'info',
						'std' => __('This Smartest Theme does things by default that regular themes don\'t do. You may decide that you don\'t need such performance.<br /><br />Here you can disable some of these actions.<br /><br /><strong>Caution: </strong>disabling these actions will break the smart functionality of this Smartest Theme, and thus make it function like a regular, plain WordPress theme. Do not proceed unless you know what you\'re doing.','crucible'));
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
$options[] = array( 'name' => sprintf( __('Disable Extra Items on wp_nav_menu','crucible'), '<code>wp_nav_menu</code>' ),
					'desc' => sprintf( __('Check this to stop inserting extra menu items, such as "Staff", "Services", and "News", into %s.', 'crucible'), '<code>wp_nav_menu</code>' ),
					'id' => $shortname.'_stop_menuitems',
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
$options[] = array( 'name' => __('Backwards Compatibility: Use Old Clock Icon on Contact Page','crucible'),
					'desc' => __('As of version 1.1.6, there is a new clock icon that is Retina ready for high resolution screens. Check this box to use the old clock icon instead.', 'crucible'),
					'id' => $shortname.'_old_clock',
					'std' => 'false',
					'type' => 'checkbox');
					
update_option('st_template',$options);
update_option('st_themename',$themename);
update_option('st_themeslug',$themeslug);
update_option('st_manual',$manualurl);
update_option('st_sshow_description','Images of size 980 (width) x 300 look best. However, the slider is responsive and will work with any size of images.');// @new update
}
add_action('init','smartestthemes_options');
?>