<?php
/** 
* Admin Setup
* @package Smartest Themes Business Framework
*/
function smartestthemes_option_setup(){
	//Update EMPTY options
	$smartestthemes_array = array();
	add_option('smartestthemes_options',$smartestthemes_array);
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
						$smartestthemes_array[$c_id] = $c_std; 
					}
				} else {
					update_option($id,$std);
					$smartestthemes_array[$id] = $std;
				}
			}
			else { //So just store the old values over again.
				$smartestthemes_array[$id] = $db_option;
			}
		}
	}
	update_option('smartestthemes_options',$smartestthemes_array);
}
?>