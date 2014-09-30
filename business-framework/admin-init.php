<?php 
/**
 * Initialize the Smartest Themes Business Framework 
 * License: GNU General Public License
 * @package    Smartest Themes Business Framework
 * @author     Smartest Themes <isa@smartestthemes.com>
 * @version    1.0-beta-2
 */
 
 if( class_exists( 'Quick_Business_Website' ) ) {
	require_once ABSPATH .'/wp-admin/includes/plugin.php';
	deactivate_plugins( '/quick-business-website/quick-business-website.php' );
	$msg =  '<strong>' . __( 'You cannot activate a "Smartest Theme"', 'crucible') . '</strong> ' . __( 'when using the <strong>Quick Business Website</strong> plugin because they clash. The Quick Business Website plugin is being deactivated now so you may use your <strong>Smartest Theme</strong>. Please go back and activate the theme again.', 'crucible');
	wp_die($msg, 'Theme Clashes With Plugin', array('back_link' => true));
} else {
	$path = get_template_directory() . '/business-framework/';
	require_once $path . 'admin-interface.php';
	require_once $path . 'smartest-functions.php';
	require_once $path . 'modules/contact/contact.php';
	include_once $path . 'widgets/all-services.php';
	include_once $path . 'widgets/featured-services.php';
	include_once $path . 'widgets/announcements.php';
	include_once $path . 'widgets/featured-announcements.php';
	include_once $path . 'widgets/staff.php';
}
?>