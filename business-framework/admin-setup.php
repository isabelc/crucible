<?php
/** 
* Admin Setup
* @package Smartest Business Framework
*/
define('THEME_FRAMEWORK','Smartest Business Framework');// @new edit name per diff frame
/* Add default options and show Options Panel after activate  */
if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	add_action('admin_head','smartestb_option_setup');
	// Do redirect. @new edit page if needed
	header( 'Location: '.admin_url().'admin.php?page=smartestbthemes' ) ;
}
function smartestb_option_setup(){
	//Update EMPTY options
	$smartestb_array = array();
	add_option('smartestthemes_options',$smartestb_array);
	$template = get_option('smartestthemes_template');
	$saved_options = get_option('smartestthemes_options');
	foreach($template as $option) {
		if($option['type'] != 'heading'){
			$id = isset($option['id']) ? $option['id'] : '';
			$std = isset($option['std']) ? $option['std'] : '';
			$db_option = get_option($id);
			if(empty($db_option)){
				if(is_array($option['type'])) {
					foreach($option['type'] as $child){
						$c_id = $child['id'];
						$c_std = $child['std'];
						update_option($c_id,$c_std);
						$smartestb_array[$c_id] = $c_std; 
					}
				} else {
					update_option($id,$std);
					$smartestb_array[$id] = $std;
				}
			}
			else { //So just store the old values over again.
				$smartestb_array[$id] = $db_option;
			}
		}
	}
	update_option('smartestthemes_options',$smartestb_array);
}

function smartest_activate_msg( ) {
	wp_register_script('act', get_template_directory_uri().'/business-framework/js/act.js', array('jquery'));wp_enqueue_script('act');
	// @new edit page=smartestbthemes below if needed
	$li1 = '<a href="'. admin_url('admin.php?page=smartestbthemes').'">'. __('comprehensive options panel', 'crucible'). '</a>';
	$li2 = '<a href="'. admin_url('widgets.php'). '">'. __('widgets settings page', 'crucible'). '</a>';
	$cue = array('intro' => '<p>'.sprintf(__('This Smartest Theme comes with a %s. This theme also supports widgets, please visit the %s to configure them.', 'crucible'), $li1, $li2 ). '</p>');
	wp_localize_script( 'act', 'smartact', $cue);
}
add_action( 'admin_enqueue_scripts', 'smartest_activate_msg' ); ?>