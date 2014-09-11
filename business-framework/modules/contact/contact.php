<?php 
/**
 * Contact form shortcode that can be inserted on any page or post
 * with both jquery client-side and php server-side validation 
 * @package Smartest Themes Business Framework
 * @subpackage Contact Module
 */

$value_name		= isset($_POST['smartestthemes_contactform_name']) ? htmlentities($_POST['smartestthemes_contactform_name']) : '';
$value_email		= isset( $_POST['smartestthemes_contactform_email'] ) ? htmlentities( $_POST['smartestthemes_contactform_email'] ) : '';
$value_response	= isset($_POST['stcf_response']) ? htmlentities($_POST['stcf_response']) : '';
$value_message		= isset($_POST['stcf_message']) ? htmlentities($_POST['stcf_message']) : '';
$value_phone		= isset($_POST['stcf_phone']) ? htmlentities($_POST['stcf_phone']) : '';

if ( get_option('st_contactform_required_phone') == 'true' ) {
	$require_phone = ' class="required"';
} else {
	$require_phone = '';
}

$stcf_strings = array(
	'name' 	 => '<input name="smartestthemes_contactform_name" id="smartestthemes_contactform_name" type="text" class="required" size="33" maxlength="99" value="'. $value_name .'" placeholder="Your name" />',
	'email'    => '<input name="smartestthemes_contactform_email" id="smartestthemes_contactform_email" type="text" class="required email" size="33" maxlength="99" value="'. $value_email .'" placeholder="Your email" />',
	'response' => '<input name="stcf_response" id="stcf_response" type="text" size="33" class="required number" maxlength="99" value="'. $value_response .'" />',
	'message'  => '<textarea name="stcf_message" id="stcf_message" class="required" minlength="4" maxlength="99" cols="33" rows="7" placeholder="Your message">'. $value_message .'</textarea>',
	'phone'	=> '<input name="stcf_phone" id="stcf_phone" type="text" size="33" ' . $require_phone . 'maxlength="99" value="'. $value_phone.'" placeholder="Your phone" />',
	'error'    => ''
	);

/**
 * check for malicious input
 */
function stcf_malicious_input($input) {
	$maliciousness = false;
	$denied_inputs = array("\r", "\n", "mime-version", "content-type", "cc:", "to:");
	foreach($denied_inputs as $denied_input) {
		if(strpos(strtolower($input), strtolower($denied_input)) !== false) {
			$maliciousness = true;
			break;
		}
	}
	return $maliciousness;
}
/**
 * check for spam
 */
function stcf_spam_question($input) {
	$response = '2';
	$response = stripslashes(trim($response));
	return ($input == $response);
}
/**
 * Get ip address
 */
function stcf_get_ip_address() {
	if(isset($_SERVER)) {
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$ip_address = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif(isset($_SERVER["HTTP_CLIENT_IP"])) {
			$ip_address = $_SERVER["HTTP_CLIENT_IP"];
		} else {
			$ip_address = $_SERVER["REMOTE_ADDR"];
		}
	} else {
		if(getenv('HTTP_X_FORWARDED_FOR')) {
			$ip_address = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('HTTP_CLIENT_IP')) {
			$ip_address = getenv('HTTP_CLIENT_IP');
		} else {
			$ip_address = getenv('REMOTE_ADDR');
		}
	}
	return $ip_address;
}
/**
 * filter input
 */
function stcf_input_filter() {

	if(!(isset($_POST['stcf_key']))) { 
		return false;
	}
	$_POST['smartestthemes_contactform_name']     = stripslashes(trim($_POST['smartestthemes_contactform_name']));
	$_POST['smartestthemes_contactform_email']    = stripslashes(trim($_POST['smartestthemes_contactform_email']));
	$_POST['stcf_message']  = stripslashes(trim($_POST['stcf_message']));
	$_POST['stcf_response'] = stripslashes(trim($_POST['stcf_response']));
	$_POST['stcf_phone'] = isset($_POST['stcf_phone']) ? stripslashes(trim($_POST['stcf_phone'])) : '';

	global $smartestthemes_options, $stcf_strings;
	$pass  = true;
	
	if(empty($_POST['smartestthemes_contactform_name'])) {
		$pass = FALSE;
		$fail = 'empty';
		$stcf_strings['name'] = '<input class="smartestthemes_contactform_error" name="smartestthemes_contactform_name" id="smartestthemes_contactform_name" type="text" size="33" maxlength="99" value="'. htmlentities($_POST['smartestthemes_contactform_name']) .'" placeholder="Your name" />';
	}
	if(!is_email($_POST['smartestthemes_contactform_email'])) {
		$pass = FALSE; 
		$fail = 'empty';
		$stcf_strings['email'] = '<input class="smartestthemes_contactform_error" name="smartestthemes_contactform_email" id="smartestthemes_contactform_email" type="text" size="33" maxlength="99" value="'. htmlentities($_POST['smartestthemes_contactform_email']) .'" placeholder="Your email" />';
	}
	
	$captcha = empty($smartestthemes_options['st_contactform_captcha']) ? '' : $smartestthemes_options['st_contactform_captcha'];
		
	if ( $captcha == 'true') {
		if (empty($_POST['stcf_response'])) {
			$pass = FALSE; 
			$fail = 'empty';
			$stcf_strings['response'] = '<input class="smartestthemes_contactform_error" name="stcf_response" id="stcf_response" type="text" size="33" maxlength="99" value="'. htmlentities($_POST['stcf_response']) .'" placeholder="1 + 1 =" />';
		}
		if (!stcf_spam_question($_POST['stcf_response'])) {
			$pass = FALSE;
			$fail = 'wrong';
			$stcf_strings['response'] = '<input class="smartestthemes_contactform_error" name="stcf_response" id="stcf_response" type="text" size="33" maxlength="99" value="'. htmlentities($_POST['stcf_response']) .'" placeholder="1 + 1 =" />';
		}
	}
	if(empty($_POST['stcf_message'])) {
		$pass = FALSE; 
		$fail = 'empty';
		$stcf_strings['message'] = '<textarea class="smartestthemes_contactform_error" name="stcf_message" id="stcf_message" cols="33" rows="7" placeholder="Your message">'. $_POST['stcf_message'] .'</textarea>';
	}
	
	$require_phone	= empty($smartestthemes_options['st_contactform_required_phone']) ? '' : $smartestthemes_options['st_contactform_required_phone'];
		
	if ($require_phone == 'true') {
		if (empty($_POST['stcf_phone'])) {
			$pass = FALSE; 
			$fail = 'empty';
			$stcf_strings['phone'] = '<input class="smartestthemes_contactform_error" name="stcf_phone" id="stcf_phone" type="text" size="33" maxlength="99" value="'. $_POST['stcf_phone'] .'" />';
		}
	}
	if(stcf_malicious_input($_POST['smartestthemes_contactform_name']) || stcf_malicious_input($_POST['smartestthemes_contactform_email'])) {
		$pass = false; 
		$fail = 'malicious';
	}
	if($pass == true) {
		return true;
	} else {
		if($fail == 'malicious') {
			$stcf_strings['error'] = '<p class="st-error">' . __( 'Please do not include any of the following in the Name or Email fields: linebreaks, or the phrases "mime-version", "content-type", "cc:" or "to:"', 'crucible' ) . '</p>';
		} elseif($fail == 'empty') {
			$msg = empty($smartestthemes_options['st_contactform_error']) ? __( 'Please complete the required fields.', 'crucible' ) : stripslashes($smartestthemes_options['st_contactform_error']);
			
			$stcf_strings['error'] = '<p class="st-error">' . $msg . '</p>';
		} elseif($fail == 'wrong') {
			$stcf_strings['error'] = '<p class="st-error">' . __( 'Oops. Incorrect answer for the security question. Please try again.', 'crucible' ) . '<br />' . __( 'Hint: 1 + 1 = 2', 'crucible' ) . '</p>';
		}
		return false;
	}
}
/**
 * shortcode to display contact form
 */
function stcf_shortcode() {
	if (stcf_input_filter()) {
		return stcf_process_contact_form();
	} else {
		return stcf_display_contact_form();
	}
}
add_shortcode('smartestthemes_contact_form','stcf_shortcode');
/**
 * template tag to display contact form
 */
function smartestthemes_contact_form() {
	if (stcf_input_filter()) {
		echo stcf_process_contact_form();
	} else {
		echo stcf_display_contact_form();
	}
}
/**
 * create contact page with working contact form
 */
function sbf_create_contact_page() {
	if(get_option('st_stop_contact') == 'false') {
		// CONTACT form is not disabled so do it	
		$bn = stripslashes_deep(esc_attr(get_option('st_business_name')));
		$contitle = sprintf(__('Contact %s','crucible'), $bn);
		smartestthemes_insert_post( 'page', esc_sql( _x('contact', 'page_slug', 'crucible') ), 'smartestthemes_contact_page_id', $contitle );
	}
}
add_action('after_setup_theme', 'sbf_create_contact_page');
// if contact page is disabled, delete it
if(get_option('st_stop_contact') == 'true') {
	wp_delete_post(get_option('smartestthemes_contact_page_id'), true);
}
/**
* enqueue CSS and validation script
*/
function stcf_enqueue_scripts() {
	wp_register_script('stcf-validate', get_template_directory_uri().'/business-framework/modules/contact/stcf-validate.js', array('jquery'), false, true);
	wp_register_style('contactstyle', get_template_directory_uri().'/business-framework/modules/contact/contact.css');
	if (is_page(get_option('smartestthemes_contact_page_id'))){
		wp_enqueue_script('stcf-validate');
		wp_enqueue_style('contactstyle');
	}
}
add_action('wp_enqueue_scripts', 'stcf_enqueue_scripts');
/**
* process contact form
*/
function stcf_process_contact_form($content='') {
	global $smartestthemes_options, $stcf_strings;
	
	$admin_email = get_bloginfo('admin_email');
	
	$topic     = empty($smartestthemes_options['st_contactform_subject']) ? __( 'Message sent from your contact form', 'crucible' ) : stripslashes($smartestthemes_options['st_contactform_subject']);
	
	$recipient = empty($smartestthemes_options['st_contactform_email']) ? $admin_email : stripslashes($smartestthemes_options['st_contactform_email']); /* the Send Email To from backend */	
	
	$multiple_recipients = explode(",", $recipient); 
	// remove empty elements from array
	$multiple_recipients_remove_empty = array_filter($multiple_recipients);
	// trim whitespace from array elements
	$trim_multiple_recipients = array();
	foreach ( $multiple_recipients_remove_empty as $key => $value ) {
		$trim_multiple_recipients[] = trim($value);
	}	
	
	$success   = empty($smartestthemes_options['st_contactform_success']) ? '<strong>' . __( 'Success! ', 'crucible' ) . '</strong> ' . __( 'Your message has been sent.', 'crucible') : stripslashes($smartestthemes_options['st_contactform_success']);
	$name      = $_POST['smartestthemes_contactform_name'];
	$email     = $_POST['smartestthemes_contactform_email'];
	$recipsite = home_url();
	$senderip  = stcf_get_ip_address();
	$offset    = empty($smartestthemes_options['st_contactform_offset']) ? '' : $smartestthemes_options['st_contactform_offset'];
	$agent     = $_SERVER['HTTP_USER_AGENT'];
	$form      = getenv("HTTP_REFERER");
	$host      = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$date      = date("l, F jS, Y @ g:i a", time() + $offset * 60 * 60);
	$prepend = empty($smartestthemes_options['st_contactform_prepend']) ? '' : stripslashes($smartestthemes_options['st_contactform_prepend']);
	$append  = empty($smartestthemes_options['st_contactform_append']) ? '' : stripslashes($smartestthemes_options['st_contactform_append']);
	$header_from = isset($smartestthemes_options['st_contactform_header_from']) ? $smartestthemes_options['st_contactform_header_from'] : '';
	
	$headers   = "MIME-Version: 1.0\n";
	if ($header_from == 'true') {
		$headers .= "From: $name <$email>\n";
	} else {
		
		// check if new option  is blank
		$sbfc_email_from  = empty($smartestthemes_options['st_contactform_email_from']) ? $admin_email : stripslashes($smartestthemes_options['st_contactform_email_from']);
		
		$headers .= $headers .= "From: " . get_bloginfo('name') . " <$sbfc_email_from>\n";
	}
	$headers .= "Reply-To: $email\n";
	$headers  .= "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"";

	$phone	= $_POST['stcf_phone'];
	$message	= $_POST['stcf_message'];

	// localize
	$local_hello = __( 'Hello', 'crucible' );
	$local_intro = sprintf( __( 'You are being contacted via %s:', 'crucible' ), $recipsite ); 
	$local_name = __( 'Name:', 'crucible' );
	$local_email = __( 'Email:', 'crucible' );
	$local_phone = __( 'Phone:', 'crucible' );
	$local_msg = __( 'Message:', 'crucible' );
	$local_addtl_info = __( 'Additional Information:', 'crucible' );
	$local_site = __( 'Site:', 'crucible' );
	$local_url = __( 'URL:', 'crucible' );
	$local_date = __( 'Date:', 'crucible' );
	$local_ip = __( 'IP:', 'crucible' );
	$local_host = __( 'Host:', 'crucible' );
	$local_agent = __( 'Agent:', 'crucible' );

$fullmsg   = ("$local_hello,

$local_intro

$local_name     $name
$local_email    $email
$local_phone	$phone
$local_msg

$message

-----------------------

$local_addtl_info

$local_site   $recipsite
$local_url    $form
$local_date   $date
$local_ip     $senderip
$local_host   $host
$local_agent  $agent
");
	$fullmsg = stripslashes(strip_tags(trim($fullmsg)));
	wp_mail($trim_multiple_recipients, $topic, $fullmsg, $headers);
	$results = ($prepend . '<div id="stcf-success"><div class="st-success">' . $success . '</div>
<pre>' . $local_name . ' ' . $name    . '
 ' . $local_email . ' ' . $email   . '
 ' . $local_date . ' ' . $date . ' 
 ' . $local_msg . ' ' . $message .'</pre><p class="stcf_reset">[ <a href="'. $form .'">'. __( 'Click here to reset form', 'crucible' ) .'</a> ]</p></div>' . $append);
	echo $results;
}
/**
 * display contact form
 */
function stcf_display_contact_form() {
	global $smartestthemes_options, $stcf_strings;
	$captcha  = isset($smartestthemes_options['st_contactform_captcha']) ? $smartestthemes_options['st_contactform_captcha'] : '';
	$offset   = isset($smartestthemes_options['st_contactform_offset']) ? $smartestthemes_options['st_contactform_offset'] : '';
	$include_phone   = isset($smartestthemes_options['st_contactform_include_phone']) ? $smartestthemes_options['st_contactform_include_phone'] : '';
	$smartestthemes_contact_preform = empty($smartestthemes_options['st_contactform_preform']) ? '' : $smartestthemes_options['st_contactform_preform'];
	$smartestthemesc_contact_appform = empty($smartestthemes_options['st_contactform_appform']) ? '' : $smartestthemes_options['st_contactform_appform'];
	
	if ($captcha == 'true') {
		$captcha_box = '
				<fieldset class="stcf-response">
					<label for="stcf_response"> 1 + 1 = </label>
					'. $stcf_strings['response'] .'
				</fieldset>';
	} else { $captcha_box = ''; }
	if ( 'true' == $include_phone ) {
		$phone_field = '<fieldset class="stcf-phone">
			<label for="stcf_phone">'. __( 'Phone', 'crucible' ) .'</label>
			'. $stcf_strings['phone'] .
			'</fieldset>';
	} else {
		$phone_field = '';
	}
	$stcf_form = ($smartestthemes_contact_preform . $stcf_strings['error'] . '
		<div id="stcf-contactform-wrap">
			<form action="'. get_permalink() .'" method="post" id="stcf-contactform">
				<fieldset class="stcf-name">
					<label for="smartestthemes_contactform_name">'. __( 'Name (Required)', 'crucible' ) .'</label>
					'. $stcf_strings['name'] .'
				</fieldset>
				<fieldset class="stcf-email">
					<label for="smartestthemes_contactform_email">'. __( 'Email (Required)', 'crucible' ) .'</label>
					'. $stcf_strings['email'] .'
				</fieldset>
					' . $captcha_box . $phone_field . '
				<fieldset class="stcf-message">
					<label for="stcf_message">'. __( 'Message (Required)', 'crucible' ) .'</label>
					'. $stcf_strings['message'] .'
				</fieldset>
				<div class="stcf-submit">
					<input type="submit" name="Submit" id="stcf_contact" value="' . __('Send email', 'crucible') . '">
					<input type="hidden" name="stcf_key" value="process">
				</div>
			</form>
		</div>
' . $smartestthemesc_contact_appform);
	return $stcf_form;
}
?>