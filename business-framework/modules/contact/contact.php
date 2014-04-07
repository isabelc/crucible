<?php 
/**
 * Contact form shortcode that can be inserted on any page or post
 * with both jquery client-side and php server-side validation 
 * @package Smartest Business Framework
 * @subpackage Contact Module
 */

$value_name		= isset($_POST['smartestb_sbfc_name']) ? htmlentities($_POST['smartestb_sbfc_name']) : '';
$value_email		= isset( $_POST['smartestb_sbfc_email'] ) ? htmlentities( $_POST['smartestb_sbfc_email'] ) : '';
$value_response	= isset($_POST['sbfc_response']) ? htmlentities($_POST['sbfc_response']) : '';
$value_message		= isset($_POST['sbfc_message']) ? htmlentities($_POST['sbfc_message']) : '';
$value_phone		= isset($_POST['sbfc_phone']) ? htmlentities($_POST['sbfc_phone']) : '';

if ( get_option('smartestthemes_sbfc_required_phone') == 'true' ) {
	$require_phone = ' class="required"';
} else {
	$require_phone = '';
}

$sbfc_strings = array(
	'name' 	 => '<input name="smartestb_sbfc_name" id="smartestb_sbfc_name" type="text" class="required" size="33" maxlength="99" value="'. $value_name .'" placeholder="Your name" />',
	'email'    => '<input name="smartestb_sbfc_email" id="smartestb_sbfc_email" type="text" class="required email" size="33" maxlength="99" value="'. $value_email .'" placeholder="Your email" />',
	'response' => '<input name="sbfc_response" id="sbfc_response" type="text" size="33" class="required number" maxlength="99" value="'. $value_response .'" />',
	'message'  => '<textarea name="sbfc_message" id="sbfc_message" class="required" minlength="4" maxlength="99" cols="33" rows="7" placeholder="Your message">'. $value_message .'</textarea>',
	'phone'	=> '<input name="sbfc_phone" id="sbfc_phone" type="text" size="33" ' . $require_phone . 'maxlength="99" value="'. $value_phone.'" placeholder="Your phone" />',
	'error'    => ''
	);

/**
 * check for malicious input
 */
function sbfc_malicious_input($input) {
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
function sbfc_spam_question($input) {
	global $smartestthemes_options;
	$response = '2';
	$response = stripslashes(trim($response));
	return ($input == $response);
}
/**
 * Get ip address
 */
function sbfc_get_ip_address() {
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
function sbfc_input_filter() {

	if(!(isset($_POST['sbfc_key']))) { 
		return false;
	}
	$_POST['smartestb_sbfc_name']     = stripslashes(trim($_POST['smartestb_sbfc_name']));
	$_POST['smartestb_sbfc_email']    = stripslashes(trim($_POST['smartestb_sbfc_email']));
	$_POST['sbfc_message']  = stripslashes(trim($_POST['sbfc_message']));
	$_POST['sbfc_response'] = stripslashes(trim($_POST['sbfc_response']));
	$_POST['sbfc_phone'] = isset($_POST['sbfc_phone']) ? stripslashes(trim($_POST['sbfc_phone'])) : '';

	global $smartestthemes_options, $sbfc_strings;
	$pass  = true;

	if(empty($_POST['smartestb_sbfc_name'])) {
		$pass = FALSE;
		$fail = 'empty';
		$sbfc_strings['name'] = '<input class="smartestb_sbfc_error" name="smartestb_sbfc_name" id="smartestb_sbfc_name" type="text" size="33" maxlength="99" value="'. htmlentities($_POST['smartestb_sbfc_name']) .'" placeholder="Your name" />';
	}
	if(!is_email($_POST['smartestb_sbfc_email'])) {
		$pass = FALSE; 
		$fail = 'empty';
		$sbfc_strings['email'] = '<input class="smartestb_sbfc_error" name="smartestb_sbfc_email" id="smartestb_sbfc_email" type="text" size="33" maxlength="99" value="'. htmlentities($_POST['smartestb_sbfc_email']) .'" placeholder="Your email" />';
	}
	if ($smartestthemes_options['smartestb_sbfc_captcha'] == 'true') {
		if (empty($_POST['sbfc_response'])) {
			$pass = FALSE; 
			$fail = 'empty';
			$sbfc_strings['response'] = '<input class="smartestb_sbfc_error" name="sbfc_response" id="sbfc_response" type="text" size="33" maxlength="99" value="'. htmlentities($_POST['sbfc_response']) .'" placeholder="1 + 1 =" />';
		}
		if (!sbfc_spam_question($_POST['sbfc_response'])) {
			$pass = FALSE;
			$fail = 'wrong';
			$sbfc_strings['response'] = '<input class="smartestb_sbfc_error" name="sbfc_response" id="sbfc_response" type="text" size="33" maxlength="99" value="'. htmlentities($_POST['sbfc_response']) .'" placeholder="1 + 1 =" />';
		}
	}
	if(empty($_POST['sbfc_message'])) {
		$pass = FALSE; 
		$fail = 'empty';
		$sbfc_strings['message'] = '<textarea class="smartestb_sbfc_error" name="sbfc_message" id="sbfc_message" cols="33" rows="7" placeholder="Your message">'. $_POST['sbfc_message'] .'</textarea>';
	}
	if ($smartestthemes_options['smartestb_sbfc_required_phone'] == 'true') {
		if (empty($_POST['sbfc_phone'])) {
			$pass = FALSE; 
			$fail = 'empty';
			$sbfc_strings['phone'] = '<input class="smartestb_sbfc_error" name="sbfc_phone" id="sbfc_phone" type="text" size="33" maxlength="99" value="'. $_POST['sbfc_phone'] .'" />';
		}
	}
	if(sbfc_malicious_input($_POST['smartestb_sbfc_name']) || sbfc_malicious_input($_POST['smartestb_sbfc_email'])) {
		$pass = false; 
		$fail = 'malicious';
	}
	if($pass == true) {
		return true;
	} else {
		if($fail == 'malicious') {
			$sbfc_strings['error'] = '<p id="sbfc_isa_error">' . __( 'Please do not include any of the following in the Name or Email fields: linebreaks, or the phrases "mime-version", "content-type", "cc:" or "to:"', 'crucible' ) . '</p>';
		} elseif($fail == 'empty') {

			$posted_msg = stripslashes($smartestthemes_options['smartestb_sbfc_error']);
			// in case they erase the default in admin
			$msg = ($posted_msg) ? $posted_msg : __( 'Please complete the required fields.', 'crucible' );
			$sbfc_strings['error'] = '<p id="sbfc_isa_error">' . $msg . '</p>';
		} elseif($fail == 'wrong') {
			$sbfc_strings['error'] = '<p id="sbfc_isa_error">' . __( 'Oops. Incorrect answer for the security question. Please try again.', 'crucible' ) . '<br />' . __( 'Hint: 1 + 1 = 2', 'crucible' ) . '</p>';
		}
		return false;
	}
}
/**
 * shortcode to display contact form
 */
function sbfc_shortcode() {
	if (sbfc_input_filter()) {
		return sbfc_process_contact_form();
	} else {
		return sbfc_display_contact_form();
	}
}
add_shortcode('smartest_themes_contact_form','sbfc_shortcode');
/**
 * template tag to display contact form
 */
function smartest_themes_contact_form() {
	if (sbfc_input_filter()) {
		echo sbfc_process_contact_form();
	} else {
		echo sbfc_display_contact_form();
	}
}
/**
 * create contact page with working contact form
 */
function sbf_create_contact_page() {
	if(get_option('smartestthemes_stop_contact') == 'false') {
		// CONTACT form is not disabled so do it	
		$bn = stripslashes_deep(esc_attr(get_option('smartestthemes_business_name')));
		$contitle = sprintf(__('Contact %s','crucible'), $bn);
		smartestbusiness_insert_post( 'page', esc_sql( _x('contact', 'page_slug', 'crucible') ), 'smartestthemes_contact_page_id', $contitle );

	}
}
add_action('after_setup_theme', 'sbf_create_contact_page');
// if contact page is disabled, delete the page
if(get_option('smartestthemes_stop_contact') == 'true') {
	wp_delete_post(get_option('smartestthemes_contact_page_id'), true);
}
/**
* enqueue CSS and validation script
*/
function sbfc_enqueue_scripts() {
	wp_register_script('sbfc-validate', get_template_directory_uri().'/business-framework/modules/contact/sbfc-validate.js', array('jquery'));
	wp_register_style('contactstyle', get_template_directory_uri().'/business-framework/modules/contact/contact.css');
	if (is_page(get_option('smartestthemes_contact_page_id'))){
		wp_enqueue_script('sbfc-validate');
		wp_enqueue_style('contactstyle');
	}
}
add_action('wp_enqueue_scripts', 'sbfc_enqueue_scripts');
/**
* process contact form
*/
function sbfc_process_contact_form($content='') {
	global $smartestthemes_options, $sbfc_strings;

	$topic     = $smartestthemes_options['smartestb_sbfc_subject'];
	$recipient = $smartestthemes_options['smartestb_sbfc_email'];
	$recipname = $smartestthemes_options['smartestb_sbfc_name'];
	$success   = $smartestthemes_options['smartestb_sbfc_success'];

	$topic     = ! empty($topic) ? stripslashes($topic) : __( 'Message sent from your contact form', 'crucible' );
	$recipient = ! empty($recipient) ? stripslashes($recipient) : get_bloginfo('admin_email');
	$recipname = ! empty($recipname) ? stripslashes($recipname) : __( 'Site Administrator', 'crucible' );
	$success   = ! empty($success) ? stripslashes($success) : '<strong>' . __( 'Success! ', 'crucible' ) . '</strong> ' . __( 'Your message has been sent.', 'crucible');

	$name      = $_POST['smartestb_sbfc_name'];
	$email     = $_POST['smartestb_sbfc_email'];
	$recipsite = get_bloginfo('url');
	$senderip  = sbfc_get_ip_address();
	$offset    = $smartestthemes_options['smartestb_sbfc_offset'];
	$agent     = $_SERVER['HTTP_USER_AGENT'];
	$form      = getenv("HTTP_REFERER");
	$host      = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$date      = date("l, F jS, Y @ g:i a", time() + $offset * 60 * 60);

	$prepend = stripslashes($smartestthemes_options['smartestb_sbfc_prepend']);
	$append  = stripslashes($smartestthemes_options['smartestb_sbfc_append']);

	$headers   = "MIME-Version: 1.0\n";
	$headers .= "From: " . get_bloginfo('name') . " <$recipient>\n";
	$headers .= "Reply-To: $email\n";
	$headers  .= "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"";

	$phone	= $_POST['sbfc_phone'];
	$message	= $_POST['sbfc_message'];

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

$fullmsg   = ("$local_hello $recipname,

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
	wp_mail($recipient, $topic, $fullmsg, $headers);
	$results = ($prepend . '<div id="sbfc_isa_success"><div id="isa_success">' . $success . '</div>
<pre>' . $local_name . ' ' . $name    . '
 ' . $local_email . ' ' . $email   . '
 ' . $local_date . ' ' . $date . ' 
 ' . $local_msg . ' ' . $message .'</pre><p class="sbfc_reset">[ <a href="'. $form .'">'. __( 'Click here to reset form', 'crucible' ) .'</a> ]</p></div>' . $append);
	echo $results;
}
/**
 * display contact form
 */
function sbfc_display_contact_form() {
	global $smartestthemes_options, $sbfc_strings;

	$captcha  = isset($smartestthemes_options['smartestb_sbfc_captcha']) ? $smartestthemes_options['smartestb_sbfc_captcha'] : '';
	$offset   = isset($smartestthemes_options['smartestb_sbfc_offset']) ? $smartestthemes_options['smartestb_sbfc_offset'] : '';
	$include_phone   = isset($smartestthemes_options['smartestb_sbfc_include_phone']) ? $smartestthemes_options['smartestb_sbfc_include_phone'] : '';
		
	if ($smartestthemes_options['smartestb_sbfc_preform'] !== '') {
		$smartestb_sbfc_preform = $smartestthemes_options['smartestb_sbfc_preform'];
	} else { $smartestb_sbfc_preform = ''; }

	if ($smartestthemes_options['smartestb_sbfc_appform'] !== '') {
		$smartestb_sbfc_appform = $smartestthemes_options['smartestb_sbfc_appform'];
	} else { $smartestb_sbfc_appform = ''; }

	if ($captcha == 'true') {
		$captcha_box = '
				<fieldset class="sbfc-response">
					<label for="sbfc_response"> 1 + 1 = </label>
					'. $sbfc_strings['response'] .'
				</fieldset>';
	} else { $captcha_box = ''; }
	if ( 'true' == $include_phone ) {
		$phone_field = '<fieldset class="sbfc-phone">
			<label for="smartestb_sbfc_phone">'. __( 'Phone', 'crucible' ) .'</label>
			'. $sbfc_strings['phone'] .
			'</fieldset>';
	} else {
		$phone_field = '';
	}
	$sbfc_form = ($smartestb_sbfc_preform . $sbfc_strings['error'] . '
		<div id="sbfc-contactform-wrap">
			<form action="'. get_permalink() .'" method="post" id="sbfc-contactform">
				<fieldset class="sbfc-name">
					<label for="smartestb_sbfc_name">'. __( 'Name (Required)', 'crucible' ) .'</label>
					'. $sbfc_strings['name'] .'
				</fieldset>
				<fieldset class="sbfc-email">
					<label for="smartestb_sbfc_email">'. __( 'Email (Required)', 'crucible' ) .'</label>
					'. $sbfc_strings['email'] .'
				</fieldset>
					' . $captcha_box . $phone_field . '
				<fieldset class="sbfc-message">
					<label for="sbfc_message">'. __( 'Message (Required)', 'crucible' ) .'</label>
					'. $sbfc_strings['message'] .'
				</fieldset>
				<div class="sbfc-submit">
					<input type="submit" name="Submit" id="sbfc_contact" value="' . __('Send email', 'crucible') . '">
					<input type="hidden" name="sbfc_key" value="process">
				</div>
			</form>
		</div>
' . $smartestb_sbfc_appform);
	return $sbfc_form;
}
?>