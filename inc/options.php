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
$user_info = get_userdata(1);
if ($user_info == true) {
	$admin_name = $user_info->user_login;
} else {
	$admin_name = __( 'Site Administrator', 'crucible' );
}
$currtime = date("l, F jS, Y @ g:i a");
$shortname = 'st';

// Globalize theme options variable for use in theme
global $smartestthemes_options;
$smartestthemes_options = array();
$smartestthemes_options = get_option('smartestthemes_options');

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
$options[] = array(
	'name' => __('Welcome','crucible'),
	'type' => 'heading');
$options[] = array(
	'name' => sprintf( __('Welcome to %s by Smartest Themes!','crucible'), $themename ),
	'type' => 'info',
	'std' => __('Your business website is up and running. On the left are tabs to customize your site, but everything is optional.<br /><br />To make your website more complete, enter the <strong>Business Info</strong> tab on the left. <br /><br />Then, take a moment to browse all the tabs so you can see what options are available. You can upload your logo in <strong>Appearance -> Customize</strong>.<br /><br />To get started, first click the \'<strong>Save all Changes</strong>\' button to save the theme defaults.','crucible') );

/* Business */
$options[] = array(
	'name' => __('Business Info','crucible'),
	'class' => 'money',
	'type' => 'heading');
$options[] = array(
	'name' => __('Business Name','crucible'),
	'desc' => __('Enter the name of your business or organization.','crucible'),
	'id' => $shortname.'_business_name',
	'type' => 'text');
$options[] = array(
	'name' => __('Attention Grabber For Homepage','crucible'),
	'desc' => __('The large tag line shown on the home page. For example, "How can we help you?"','crucible'),
	'id' => $shortname.'_attention_grabber',
	'std' => __( 'How can we help you?', 'crucible' ),
	'type' => 'text');
$options[] = array(
	'name' => __('Business Street Address','crucible'),
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
$options[] = array(
	'name' => __('Business Phone Number','crucible'),
	'desc' => __('Optional. Your business phone number to be displayed on your Contact page. Example: 555-555-5555.','crucible'),
	'id' => $shortname.'_phone_number',
	'type' => 'text');
$options[] = array(
	'name' => __('Business Fax Number','crucible'),
	'desc' => __('Optional. Your business fax number to be displayed on your Contact page. Example: 555-555-5555.','crucible'),
	'id' => $shortname.'_fax_numb',
	'type' => 'text');
$options[] = array(
	'name' => __('Display Business Email Address?','crucible'),
	'desc' => sprintf(__('Check this to show your business email address on your Contact Page. You can change your email address in %s.', 'crucible'), $slink ),
	'id' => $shortname.'_show_contactemail',
	'std' => 'false',
	'type' => 'checkbox');
$options[] = array(
	'name' => __('Google Map','crucible'),
	'desc' => sprintf(__('If you want to show a Google Map for your business address, paste here your HTML embed code from %s.','crucible'), '<a href="http://maps.google.com" target="_blank">Google Maps</a>' ),
	'id' => $shortname.'_google_map',
	'std' => '',
	'type' => 'textarea');
$options[] = array(
	'name' => __('Business Hours','crucible'),
	'desc' => __('Optional. Enter your hours here if you want to display them. Example:<br /><br />Monday - Friday: 7:30 am - 6:00<br />Saturday: 7:30 am - Noon<br /><br />', 'crucible'),
	'id' => $shortname.'_hours',
	'std' => '',
	'type' => 'textarea');
/* Preferences */
$options[] = array(
	'name' => __('Preferences','crucible'),
	'class' => 'preferences',
	'type' => 'heading');
$options[] = array(
	'name' => __('Add Staff section?','crucible'),
	'desc' => __('Check this to show your staff members.','crucible'),
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
$options[] = array(
	'name' => __('Add Announcements section?','crucible'),
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
$options[] = array(
	'name' => __('Add Services?','crucible'),
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
					
/* Custom CSS */
$options[] = array(
	'name' => __('Custom CSS','crucible'),'class' => 'custom-css',
	'type' => 'heading'
);
$options[] = array(
	'name' => __('Custom CSS','crucible'),
	'desc' => __('Quickly add CSS to your theme by pasting it here. Paste only CSS, no HTML style tags.','crucible'),
	'id' => $shortname.'_custom_css',
	'std' => '',
	'type' => 'textarea'
);

/* About Page */
$options[] = array(
	'name' => __('About Page','crucible'),
	'class' => 'about-at',
	'type' => 'heading');
$options[] = array(
	'name' => __('About Your Business','crucible'),
	'desc' => __('The \'About Page\' is a page about your business. Type what you want your visitors to read here. It may be a history, a sales pitch, or anything you like. To enlarge the text area, drag the lower right corner down.', 'crucible'),
	'id' => $shortname.'_about_page',
	'std' => '',
	'type' => 'textarea');
$options[] = array(
	'name' => __('About Page Picture','crucible'),
	'desc' => __('Upload a picture for your About page, or specify the image address of an online picture, like http://yoursite.com/picture.png','crucible'),
	'id' => $shortname.'_about_picture',
	'std' => '',
	'type' => 'upload');
$options[] = array(
	'name' => __('Disable About Page','crucible'),
	'desc' => __('Check this to disable the About page altogether. This will delete the automatically-created About page.', 'crucible'),
	'id' => $shortname.'_stop_about',
	'std' => 'false',
	'type' => 'checkbox');
/* Social Media */
$options[] = array(
	'name' => __('Social Media','crucible'),
	'class' => 'social',
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
$options[] = array(
	'name' => __('Display Social Follow Buttons','crucible'),
	'desc' => __('Check where you would like to display the social links.', 'crucible'),
	'id' => $shortname.'_social_follow_show',
	'std' => 'footer',
	'type' => 'multicheck',
	'options' => array(
			'header'	=> __('on the site header', 'crucible'),
			'footer'	=> __('on the site footer', 'crucible'))
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
/* Branding */
$options[] = array(
	'name' => __('Backend Branding','crucible'),
	'class' => 'branding',
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
$options[] = array(
	'name' => __( 'Contact Form','crucible' ),
	'class' => 'mail',
	'type' => 'heading');
	
$options[] = array(
	'name' => __( 'Send Email To', 'crucible' ),
	'desc' => __( 'Where would you like to receive messages sent from the contact form? You can enter multiple email addresses separated by commas. If blank, the default is the admin email set in Settings -> General.', 'crucible' ),
	'id' => $shortname.'_contactform_email',
	'std' => '',
	'type' => 'text');
	
$options[] = array(
	'name' => __( 'Send Email From', 'crucible' ),
	'desc' => __( 'Enter the email address that will show in the "From:" email header. If blank, the default is the admin email set in Settings -> General. You can override this with the next option.', 'crucible' ),
	'id' => $shortname.'_contactform_email_from',
	'std' => '',
	'type' => 'text');
	
$options[] = array( 'name' => __( 'Send Email From Your Visitor', 'crucible' ),
	'desc' => sprintf(__( 'Check this box if you want the %1$s"From:"%2$s in the email header to be the name and email of the visitor. Please note that some web hosts, such as DreamHost and BlueHost, will not send these messages. They may consider it "spoofing" since the messages are not really coming from your visitor, but rather are coming from your own website server. (GoDaddy hosting is okay with this option.) Test this option after you enable it. If left unchecked, the "Send Email From" setting from above will be used.','crucible' ), '<strong>', '</strong>'),
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
$options[] = array(
	'name' => __( 'Custom content before the form', 'crucible' ),
	'desc' => __( 'Add some text/markup to appear <em>before</em> the contact form (optional).', 'crucible' ),
	'id' => $shortname.'_contactform_preform',
	'std' => '',
	'type' => 'textarea');
$options[] = array(
	'name' => __( 'Custom content after the form', 'crucible' ),
	'desc' => __( 'Add some text/markup to appear <em>after</em> the contact form (optional).', 'crucible' ),
	'id' => $shortname.'_contactform_appform',
	'std' => '',
	'type' => 'textarea');
$options[] = array(
	'name' => __( 'Custom content before results', 'crucible' ),
	'desc' => __( 'Add some text/markup to appear <em>before</em> the success message (optional).', 'crucible' ),
	'id' => $shortname.'_contactform_prepend',
	'std' => '',
	'type' => 'textarea');
$options[] = array(
	'name' => __( 'Custom content after results', 'crucible' ),
	'desc' => __( 'Add some text/markup to appear <em>after</em> the success message (optional).', 'crucible' ),
	'id' => $shortname.'_contactform_append',
	'std' => '',
	'type' => 'textarea');
	
/* Reviews */		
$options[] = array(
	'name' => __( 'Reviews','crucible' ),
	'class' => 'reviews',
	'type' => 'heading');
	
$options[] = array(
	'name' => __('About Reviews','crucible'),
	'type' => 'info',
	'std' => __('Aggregate ratings data from your Reviews will be used to create rich snippets for search engines on your home page and your Reviews page. Reviews are marked up with Schema.org microdata, as recommended by Google.','crucible'));
$options[] = array(
	'name' => __('Enable Reviews?','crucible'),
	'desc' => __('Check this to add a page to let visitors submit reviews for your approval. Reviews are not public unless you approve them.','crucible'),
	'id' => $shortname.'_add_reviews',
	'std' => 'true',
	'type' => 'checkbox');
$options[] = array(
	'name' => __('Reviews shown per page:', 'crucible'),
	'desc' => __('Enter a number. If blank, the default is 10.','crucible'),
	'id' => $shortname.'_reviews_per_page',
	'std' => '10',
	'type' => 'text');
$options[] = array(
	'name'	=> __('Location of Review Form','crucible'),
	'desc'	=> '',
	'id'	=> $shortname.'_reviews_form_location',
	'type'	=> 'select2',
	'std'	=> 'above',
	'options' => array(
			'above' => __('Above Reviews', 'crucible'),
			'below' => __('Below Reviews', 'crucible')));
$options[] = array(
	'name'	=> __('Fields to ask for on Review Form','crucible'),
	'desc'	=> '',
	'id'	=> $shortname.'_reviews_ask_fields',
	'type'	=> 'multicheck',
	'std'	=> 'ask_femail',
	'options' => array(
			'ask_fname'		=> __('Name', 'crucible'),
			'ask_femail'	=> __('Email', 'crucible'),
			'ask_fwebsite'	=> __('Website', 'crucible'),
			'ask_ftitle' 	=> __('Review Title', 'crucible'))
			);
$options[] = array(
	'name' => __('Fields to require on Review Form','crucible'),
	'desc' => '',
	'id' => $shortname.'_reviews_require_fields',
	'type' => 'multicheck',
	'std' => 'require_femail',
	'options' => array(
			'require_fname'		=> __('Name', 'crucible'),
			'require_femail'	=> __('Email', 'crucible'),
			'require_fwebsite'	=> __('Website', 'crucible'),
			'require_ftitle' 	=> __('Review Title', 'crucible'))
			);
$options[] = array(
	'name' => __('Fields to show on each approved review','crucible'),
	'desc' => __('It is usually NOT a good idea to show email addresses publicly.', 'crucible'),
	'id' => $shortname.'_reviews_show_fields',
	'type' => 'multicheck',
	'std' => 'show_fname',
	'options' => array(
			'show_fname'	=> __('Name', 'crucible'),
			'show_femail'	=> __('Email', 'crucible'),
			'show_fwebsite'	=> __('Website', 'crucible'),
			'show_ftitle' 	=> __('Review Title', 'crucible'))
			);
$options[] = array( 
	'type' => 'info',
	'std' => __('Custom Fields on Review Form','crucible'),
	'class'	=> 'plain-title',
	);
$options[] = array( 
	'type' => 'info',
	'std' => __('Enter the names of any additional fields you would like.','crucible'),
	'class'	=> 'intro',
	);
/* 6 custom fields */
for ($i = 0; $i < 6; $i++) {
	$options[] = array(
		'desc'	=> '',
		'id'	=> $shortname.'_reviews_custom_field_' . $i,
		'std'	=> '',
		'class'	=> 'half-multi',
		'type'	=> 'text');
				
	$options[] = array(
		'desc'	=> '',
		'id'	=> $shortname.'_reviews_custom' . $i,
		'type'	=> 'multicheck',
		'std'	=> '',
		'class'	=> 'multi',
		'options'	=> array(
				'ask'		=> __('Ask', 'crucible'),
				'require'	=> __('Require', 'crucible'),
				'show'		=> __('Show', 'crucible')));

}			
$options[] = array(
	'name' => __('Heading tag to use for Review Titles','crucible'),
	'desc' => __('Select an HTML heading tag for the individual review titles.','crucible'),
	'id' => $shortname.'_reviews_title_tag',
	'std' => 'h2',
	'type' => 'select',
	'options' => array('h2','h3','h4','h5','h6'));			
$options[] = array(
		'name' => __('Button text for showing the Review form','crucible'),
		'desc'	=> __('Clicking this button will show the review submission form. What do you want this button to say?','crucible'),
		'id'	=> $shortname.'_reviews_show_form_button',
		'std'	=> __('Click here to submit your review','crucible'),
		'type'	=> 'text');
$options[] = array(
		'name' => __('Heading to be displayed above the Review form','crucible'),
		'desc'	=> __('This will be shown as a heading immediately above the review form.','crucible'),
		'id'	=> $shortname.'_review_form_heading',
		'std'	=> __('Submit Your Review','crucible'),
		'type'	=> 'text');
$options[] = array(
		'name' => __('Text to use for Review Form Submit Button','crucible'),
		'desc'	=> __('This is the Submit button to submit a review. What do you want this button to say?','crucible'),
		'id'	=> $shortname.'_review_submit_button_text',
		'std'	=> __('Submit Your Review','crucible'),
		'type'	=> 'text');

/* Scripts */
$options[] = array(
	'name' => __('Scripts','crucible'),
	'class' => 'scripts',
	'type' => 'heading');
$options[] = array(
	'name' => __('Analytics Code','crucible'),
	'desc' => __('Paste your analytics script here.','crucible'),
	'id' => $shortname.'_script_analytics',
	'std' => '',
	'type' => 'textarea');
$options[] = array(
	'name' => __('Additional Scripts To Load','crucible'),
	'desc' => __('Paste any scripts here to be loaded into wp_head. Remember your script tags.','crucible'),
	'id' => $shortname.'_scripts_head',
	'std' => '',
	'type' => 'textarea');
/* Advanced */
$options[] = array(
	'name' => __('Advanced','crucible'),
	'class' => 'advanced',
	'type' => 'heading');
$options[] = array(
	'name' => __('Disable Automatic Smartest Theme Actions','crucible'),
	'type' => 'info',
	'std' => __('This Smartest Theme does things by default that regular themes don\'t do. You may decide that you don\'t need such performance.<br /><br />Here you can disable some of these actions.','crucible'));
$options[] = array(
	'name' => __('Front Page and Posts Page Settings','crucible'),
	'desc' => sprintf(__('Check this to stop forcing \'Posts Page\' setting to a page titled \'Blog\'. Checking this will allow you to choose your own Posts Page in %s.', 'crucible'), $rlink),
	'id' => $shortname.'_stop_blog',
	'std' => 'false',
	'type' => 'checkbox');
$options[] = array(
	'desc' => sprintf(__('Check this to stop forcing \'Static Front Page\' setting to \'Home\'. Checking this will allow you to choose your own static Front Page in %s.', 'crucible'), $rlink),
	'id' => $shortname.'_stop_static',
	'std' => 'false',
	'type' => 'checkbox');
$options[] = array( 'name' => __('Disable Automatic Home Page Creation','crucible'),
	'desc' => __('Check this to stop the page titled "Home" to be automatically created every time you delete it. This will permanently delete the automatically-created Home page, and allow you to create your own page titled "Home".', 'crucible'),
	'id' => $shortname.'_stop_home',
	'std' => 'false',
	'type' => 'checkbox');
$options[] = array( 'name' => __('Disable Contact Page','crucible'),
	'desc' => sprintf( __( 'Check this to disable the Contact page. This will delete the automatically-created Contact page. You will still be able to use the shortcode to add a contact form: %s', 'crucible' ), '<code>[smartestthemes_contact_form]</code>' ),
	'id' => $shortname.'_stop_contact',
	'std' => 'false',
	'type' => 'checkbox');
$options[] = array( 'name' => __('Disable Social Share Buttons','crucible'),
	'desc' => __('Check this to stop inserting  Google+ Share, Tweet, Facebook Share, and Pinterest Pin it buttons at the bottom of single posts.', 'crucible'),
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
}
add_action('init','smartestthemes_options');
?>