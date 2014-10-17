<?php
/** 
 * Admin Interface
 * @package    Smartest Themes Business Framework
*/

// Used to alert the clash in the QBW plugin 
if ( !defined('SMARTEST_FRAMEWORK') )
	define('SMARTEST_FRAMEWORK','Business Framework');
	
/** 
* Setup the admin options
*/
function smartestthemes_option_setup(){
	//Update EMPTY options
	$smartestthemes_array = array();
	add_option('smartestthemes_options',$smartestthemes_array);
	$template = get_option('smartestthemes_template');

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

// Load static framework options pages 
function smartestthemes_add_admin() {
	global $query_string;
	$themeobject = wp_get_theme();
	$themename = $themeobject->Name;
	$themeslug = $themeobject->Template;

	if ( isset($_REQUEST['page']) && $themeslug == $_REQUEST['page'] ) {
		if (isset($_REQUEST['smartestthemes_save']) && 'reset' == $_REQUEST['smartestthemes_save']) {
			$options =  get_option('smartestthemes_template');
			smartestthemes_reset_options($options,$themeslug);
			header("Location: admin.php?page=$themeslug&reset=true");
			die;
		}
	}
	$icon = get_template_directory_uri(). '/business-framework/images/smartestthemes-icon.png';
	$sto=add_menu_page(sprintf(__('%s Options', 'crucible'), $themename), sprintf(__('%s Options', 'crucible'), $themename), 'activate_plugins', $themeslug, 'smartestthemes_options_page', $icon, 45.9);
	add_action( 'admin_head-'. $sto, 'smartestthemes_frame_load' );
	add_smar_admin_menu_separator(44);
} 
add_action('admin_menu', 'smartestthemes_add_admin');
/* Reset options */
function smartestthemes_reset_options($options,$page = ''){
	global $wpdb;
	$query_inner = '';
	$count = 0;
	$excludes = array( 'blogname' , 'blogdescription' );
	foreach($options as $option){
		if(isset($option['id'])){ 
			$count++;
			$option_id = $option['id'];
			$option_type = $option['type'];
			
			//Skip assigned id's
			if(in_array($option_id,$excludes)) { continue; }
			
			if($count > 1){ $query_inner .= ' OR '; }
			if($option_type == 'multicheck'){
				$multicount = 0;
				foreach($option['options'] as $option_key => $option_option){
					$multicount++;
					if($multicount > 1){ $query_inner .= ' OR '; }
					$query_inner .= "option_name = '" . $option_id . "_" . $option_key . "'";
					
				}
				
			} else if(is_array($option_type)) {
				$type_array_count = 0;
				foreach($option_type as $inner_option){
					$type_array_count++;
					$option_id = $inner_option['id'];
					if($type_array_count > 1){ $query_inner .= ' OR '; }
					$query_inner .= "option_name = '$option_id'";
				}
				
			} else {
				$query_inner .= "option_name = '$option_id'";
			}
		}
	}
	
	//When Theme Options page is reset - Add the smartestthemes_options option
	$themeobject = wp_get_theme();
	$themeslug = $themeobject->Template;

	if ( $page == $themeslug ) {
		$query_inner .= " OR option_name = 'smartestthemes_options'";
	}
	$query = "DELETE FROM $wpdb->options WHERE $query_inner";
	$wpdb->query($query);
}
/* Framework options panel */
function smartestthemes_options_page(){
	$options = get_option('smartestthemes_template');
	$manualurl = get_option('st_manual');
	$themedata = wp_get_theme();
	$themename = $themedata->Name;
	$local_version = $themedata->Version;
	$fDIR = get_template_directory_uri().'/business-framework/'; ?>
<div class="wrap" id="smartestthemes-container">
<div id="smartestthemes-popup-save" class="smartestthemes-save-popup"><div class="smartestthemes-save-save"><?php _e('Options Updated', 'crucible'); ?></div></div>
<div id="smartestthemes-popup-reset" class="smartestthemes-save-popup"><div class="smartestthemes-save-reset"><?php _e('Options Reset', 'crucible'); ?></div></div>
    <form action="" enctype="multipart/form-data" id="smartestform">
        <div id="header">
           <div class="logo">
		<?php echo apply_filters('smartestthemes_backend_branding', '<img alt="Smartest Themes" src="'. $fDIR. 'images/st_logo_admin.png" />'); ?>
          </div>
             <div class="theme-info">
				<span class="theme" style="margin-top:10px;"><?php printf(__('%s', 'crucible'), $themename); ?>
						<span class="ver"> <?php printf(__('version %s', 'crucible'), $local_version); ?>
</span>						
				</span>
				
			</div>
			<div class="clear"></div>
		</div>
        <?php 
		// Rev up the Options Machine
        $return = smartestthemes_machine($options);
        ?>
		<div id="support-links">
<!--[if IE]>
<div class="ie">
<![endif]-->
			<ul>
            <li class="right"><img style="display:none" src="<?php echo $fDIR; ?>images/loading-top.gif" class="ajax-loading-img ajax-loading-img-top" alt="Working..." />
<input type="submit" value="<?php _e('Save All Changes', 'crucible'); ?>" class="button submit-button" /></li>
			</ul> 
<!--[if IE]>
</div>
<![endif]-->
		</div>
        <div id="main">
	        <div id="smartestthemes-nav">
				<ul>
					<?php echo $return[1] ?>
					<li><a class="theme-support" title="<?php _e( 'Theme Support', 'crucible' ); ?>" href="#smartestthemes-option-themesupport"><span class="smartestthemes-nav-icon"></span><?php _e('Theme Support', 'crucible'); ?></a></li>
					
				</ul>		
			</div>
			<div id="content">
	         <?php echo $return[0]; /* Settings */ ?>
	         <!-- THEME SUPPORT SECTION -->
	         <div class="group" id="smartestthemes-option-themesupport" style="display:block;">
	         <h2><?php _e('Theme Support', 'crucible'); ?></h2>
	         <div class="section support-section">
	         </div>
	         <div class="section support-section">
	         <p class="support-content"><?php _e('Need help?  We have a variety of support materials that is growing all the time to help you get up and running with Smartest Themes.', 'crucible'); ?></p>
	         </div>
	         <div class="support-divider"></div>
	         <div class="section support-section">
	         <div class="support-section-icon info_75"></div>
	         <h4 class="support-section-title"><?php _e('Instruction Guides', 'crucible'); ?></h4>
	         <p class="support-content"><?php _e('The Instruction Guides help you set up your theme.', 'crucible'); ?></p><div class="section support-section">
	         <a class="support-button" target="_blank" title="<?php _e( 'Instruction Guides', 'crucible' ); ?>" href="<?php echo $manualurl; ?>"><?php _e('Go To Instruction Guides', 'crucible'); echo ' &raquo;'; ?></a>
	         </div><div class="clear"></div></div>
	         <div class="support-divider"></div>
	         <div class="section support-section">
	         <div class="support-section-icon comments_blue_75"></div>
	         <h4 class="support-section-title"><?php _e('Support Forums', 'crucible'); ?></h4>
	         <p class="support-content"><?php _e('Get help or report a bug at the forums. Post your question as a new topic, and we will focus on answering your questions and helping you to use the default functionality of your theme.', 'crucible'); ?></p>
<div class="section support-section">
	         <a class="support-button" target="_blank" title="<?php _e( 'Support Forums', 'crucible' ); ?>" href="http://smartestthemes.com/support/"><?php _e('Go To Support Forums', 'crucible'); echo ' &raquo;'; ?> </a>
	         </div>
	         <div class="clear"></div>
	         </div>
	         <div class="support-divider"></div>
</div><!-- END THEME SUPPORT SECTION -->
	        </div><div class="clear"></div>
        </div>
        <!--[if IE]>
		<div class="ie">
		<![endif]-->
        <div class="save_bar_top">
        <img style="display:none" src="<?php echo $fDIR; ?>images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
        <input type="submit" value="<?php _e('Save All Changes', 'crucible'); ?>" class="button submit-button" />        
        </form>
        <form action="<?php echo esc_html( $_SERVER['REQUEST_URI'] ) ?>" method="post" style="display:inline" id="smartestform-reset">
            <span class="submit-footer-reset">
            <input name="reset" type="submit" value="<?php _e('Reset Options', 'crucible'); ?>" class="button submit-button reset-button" onclick="return confirm(localized_label.reset);" />
            <input type="hidden" name="smartestthemes_save" value="reset" /> 
            </span></form></div><!--[if IE 6]></div><![endif]--><div style="clear:both;"></div>    
</div><!--wrap-->
 <?php
}
function smartestthemes_frame_load() {
	$fr = get_template_directory_uri(). '/business-framework/';
	add_action('admin_head', 'smartestthemes_admin_head');
	wp_enqueue_script('jquery-ui-core');
	wp_register_script('jquery-input-mask', $fr. 'js/jquery.maskedinput-1.3.1.min.js', array( 'jquery' ));
	wp_enqueue_script('jquery-input-mask');
	
	function smartestthemes_admin_head() {
	
		$fr = get_template_directory_uri(). '/business-framework/';
		
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo $fr; ?>css/admin-style.css" media="screen" />
		<?php //AJAX Upload
		// Localize vars for js
		$upl = __('Uploading', 'crucible');
		$upi = __('Upload Image', 'crucible');
		$okr = __('Click OK to reset back to default settings. All custom theme settings will be lost!', 'crucible');
		?>
		<script>
			var localized_label = {
				uploading : "<?php echo $upl ?>",
				uploadimage : "<?php echo $upi ?>",
				reset : "<?php echo $okr ?>"
			}
		</script>
		<script type="text/javascript" src="<?php echo $fr; ?>js/ajaxupload.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.group').hide();
				jQuery('.group:first').fadeIn();
				jQuery('.group .collapsed').each(function(){
					jQuery(this).find('input:checked').parent().parent().parent().nextAll().each( 
						function(){
           					if (jQuery(this).hasClass('last')) {
           						jQuery(this).removeClass('hidden');
           						return false;
           					}
           					jQuery(this).filter('.hidden').removeClass('hidden');
           				});
           		});
				jQuery('.group .collapsed input:checkbox').click(unhideHidden);
				function unhideHidden(){
					if (jQuery(this).attr('checked')) {
						jQuery(this).parent().parent().parent().nextAll().removeClass('hidden');
					}
					else {
						jQuery(this).parent().parent().parent().nextAll().each( 
							function(){
           						if (jQuery(this).filter('.last').length) {
           							jQuery(this).addClass('hidden');
									return false;
           						}
           						jQuery(this).addClass('hidden');
           					});
           					
					}
				}
				jQuery('.smartestthemes-radio-img-img').click(function(){
					jQuery(this).parent().parent().find('.smartestthemes-radio-img-img').removeClass('smartestthemes-radio-img-selected');
					jQuery(this).addClass('smartestthemes-radio-img-selected');
					
				});
				jQuery('.smartestthemes-radio-img-label').hide();
				jQuery('.smartestthemes-radio-img-img').show();
				jQuery('.smartestthemes-radio-img-radio').hide();
				jQuery('#smartestthemes-nav li:first').addClass('current');
				jQuery('#smartestthemes-nav li a').click(function(evt){
						jQuery('#smartestthemes-nav li').removeClass('current');
						jQuery(this).parent().addClass('current');
						var clicked_group = jQuery(this).attr('href');
						jQuery('.group').hide();
							jQuery(clicked_group).fadeIn();
						evt.preventDefault();
					});
				if('<?php if(isset($_REQUEST['reset'])) { echo $_REQUEST['reset'];} else { echo 'false';} ?>' == 'true'){
					var reset_popup = jQuery('#smartestthemes-popup-reset');
					reset_popup.fadeIn();
					window.setTimeout(function(){
						   reset_popup.fadeOut();                        
						}, 2000);
				}
			//Update Message popup
			jQuery.fn.center = function () {
				this.animate({"top":( jQuery(window).height() - this.height() - 200 ) / 2+jQuery(window).scrollTop() + "px"},100);
				this.css("left", 250 );
				return this;
			}
			jQuery('#smartestthemes-popup-save').center();
			jQuery('#smartestthemes-popup-reset').center();
			jQuery(window).scroll(function() { 
				jQuery('#smartestthemes-popup-save').center();
				jQuery('#smartestthemes-popup-reset').center();
			
			});
			//AJAX Upload
			jQuery('.image_upload_button').each(function(){
			var clickedObject = jQuery(this);
			var clickedID = jQuery(this).attr('id');	
			new AjaxUpload(clickedID, {
				  action: '<?php echo admin_url("admin-ajax.php"); ?>',
				  name: clickedID, // File upload name
				  data: { // Additional data to send
						action: 'smartestthemes_ajax_post_action',
						type: 'upload',
						data: clickedID },
				  autoSubmit: true, // Submit file after selection
				  responseType: false,
				  onChange: function(file, extension){},
				  onSubmit: function(file, extension){
						clickedObject.text(localized_label.uploading); // change button text, when user selects file	
						this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
						interval = window.setInterval(function(){
							var text = clickedObject.text();
							if (text.length < 13){	clickedObject.text(text + '.'); }
							else { clickedObject.text(localized_label.uploading); } 
						}, 200);
				  },
				  onComplete: function(file, response) {
				   
					window.clearInterval(interval);
					clickedObject.text(localized_label.uploadimage);
					this.enable(); // enable upload button
					
					// If there was an error
					if(response.search('Upload Error') > -1){
						var buildReturn = '<span class="upload-error">' + response + '</span>';
						jQuery(".upload-error").remove();
						clickedObject.parent().after(buildReturn);
					
					}
					else{
						var buildReturn = '<img class="hide smartestthemes-option-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';

						jQuery(".upload-error").remove();
						jQuery("#image_" + clickedID).remove();	
						clickedObject.parent().after(buildReturn);
						jQuery('img#image_'+clickedID).fadeIn();
						clickedObject.next('span').fadeIn();
						clickedObject.parent().prev('input').val(response);
					}
				  }
				});
			
			});
			
			//AJAX Remove (clear option value)
			jQuery('.image_reset_button').click(function(){
			
					var clickedObject = jQuery(this);
					var clickedID = jQuery(this).attr('id');
					var theID = jQuery(this).attr('title');	
	
					var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
				
					var data = {
						action: 'smartestthemes_ajax_post_action',
						type: 'image_reset',
						data: theID
					};
					jQuery.post(ajax_url, data, function(response) {
						var image_to_remove = jQuery('#image_' + theID);
						var button_to_hide = jQuery('#reset_' + theID);
						image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
						button_to_hide.fadeOut();
						clickedObject.parent().prev('input').val('');
					});
					return false; 
				});   	 	
			//Save everything else
			jQuery('#smartestform').submit(function(){
					function newValues() {
					  var serializedValues = jQuery("#smartestform").serialize();
					  return serializedValues;
					}
					jQuery(":checkbox, :radio").click(newValues);
					jQuery("select").change(newValues);
					jQuery('.ajax-loading-img').fadeIn();
					var serializedReturn = newValues();
					var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
					var data = {
						<?php 
						$themeobject = wp_get_theme();
						$themeslug = $themeobject->Template;
						if(isset($_REQUEST['page']) && $_REQUEST['page'] == $themeslug ){ ?>
						type: 'options',
						<?php } ?>
						action: 'smartestthemes_ajax_post_action',
						data: serializedReturn
					};
					jQuery.post(ajax_url, data, function(response) {
						var success = jQuery('#smartestthemes-popup-save');
						var loading = jQuery('.ajax-loading-img');
						loading.fadeOut();  
						success.fadeIn();
						window.setTimeout(function(){
						   success.fadeOut(); 
						}, 2000);
					});
					return false; 
				});   	 	
			});
		</script>
	<?php }
}
/**
 * Ajax Save Action
 */
function smartestthemes_ajax_callback() {
	global $wpdb;
	$save_type = $_POST['type'];
	if($save_type == 'upload'){
		$clickedID = $_POST['data']; // Acts as the name
		$filename = $_FILES[$clickedID];
       	$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';    
		$uploaded_file = wp_handle_upload($filename,$override);
		$upload_tracking[] = $clickedID;
		update_option( $clickedID , $uploaded_file['url'] );

		 if(!empty($uploaded_file['error'])) {echo __('Upload Error: ', 'crucible') . $uploaded_file['error']; }

		 else {
			$attachment = array(
			'post_title' => $filename['name'],
			'post_content' => '',
			'post_type' => 'attachment',
			'post_parent' => '',
			'post_mime_type' => $filename['type'],
			'guid' => $uploaded_file['url']
			);
			// Create Attachment
			$id = wp_insert_attachment( $attachment,$uploaded_file[ 'file' ] );
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $uploaded_file['file'] ) );
			echo $uploaded_file['url']; 
		}
	} elseif($save_type == 'image_reset'){
			
			$id = $_POST['data']; // Acts as the name
			global $wpdb;
			$query = "DELETE FROM $wpdb->options WHERE option_name LIKE '$id'";
			$wpdb->query($query);
	
	} elseif ($save_type == 'options') {

		$data = $_POST['data'];
		parse_str($data,$output);

        $options = get_option('smartestthemes_template');
				
		foreach($options as $option_array){
			$id = isset($option_array['id']) ? $option_array['id'] : '';
			$old_value = get_option($id);
			$new_value = '';
			if(isset($output[$id])){
				$new_value = $output[$option_array['id']];
			}
			if(isset($option_array['id'])) { // Non - Headings...
				
				//Import of prior saved options
				if($id == 'framework_smartestthemes_import_options'){
					//Decode and over write options.
					$new_import = $new_value;
					$new_import = unserialize($new_import);
					if(!empty($new_import)) {
						foreach($new_import as $id2 => $value2){
							if(is_serialized($value2)) {
								update_option($id2,unserialize($value2));
							} else {
								update_option($id2,$value2);
							}
						}
					}
					
				} else {
				
			
					$type = $option_array['type'];
					
					if ( is_array($type)){
						foreach($type as $array){
							if($array['type'] == 'text'){
								$id = $array['id'];
								$new_value = $output[$id];
								update_option( $id, stripslashes($new_value));// isa, may conflict w url inputs that need slashes
							}
						}                 
					}
					elseif($new_value == '' && $type == 'checkbox'){ // Checkbox Save
					
						update_option($id,'false');

					}
					elseif ($new_value == 'true' && $type == 'checkbox'){ // Checkbox Save
					
					
						update_option($id,'true');
						
						
								
								
					}
					elseif($type == 'multicheck'){ // Multi Check Save
						$option_options = $option_array['options'];
						foreach ($option_options as $options_id => $options_value){
							$multicheck_id = $id . "_" . $options_id;
							if(!isset($output[$multicheck_id])){
								update_option($multicheck_id,'false');
							}
							else{
								update_option($multicheck_id,'true'); 
							}
						}
					} 
					elseif($type != 'upload_min'){
						update_option($id,stripslashes($new_value));
					}
				
				}
			}	
		}
	}
	
	if( $save_type == 'options'){
		/* Create, Encrypt and Update the Saved Settings */
		global $wpdb;
		$smartestthemes_options = array();
		$query_inner = '';
		$count = 0;

		print_r($options);
		foreach($options as $option){
			
			if(isset($option['id'])){ 
				$count++;
				$option_id = $option['id'];
				$option_type = $option['type'];
				
				if($count > 1){ $query_inner .= ' OR '; }
				
				if(is_array($option_type)) {
				$type_array_count = 0;
				foreach($option_type as $inner_option){
					$type_array_count++;
					$option_id = $inner_option['id'];
					if($type_array_count > 1){ $query_inner .= ' OR '; }
					$query_inner .= "option_name = '$option_id'";
					}
				}
				else {
				
					$query_inner .= "option_name = '$option_id'";
					
				}
			}
			
		}
		
		$query = "SELECT * FROM $wpdb->options WHERE $query_inner";
				
		$results = $wpdb->get_results($query);
		
		$output = "<ul>";
		
		foreach ($results as $result){
				$name = $result->option_name;
				$value = $result->option_value;
				
				if(is_serialized($value)) {
					
					$value = unserialize($value);
					$smartestthemes_array_option = $value;
					$temp_options = '';
					foreach($value as $v){
						if(isset($v))
							$temp_options .= $v . ',';
						
					}	
					$value = $temp_options;
					$smartestthemes_array[$name] = $smartestthemes_array_option;
				} else {
					$smartestthemes_array[$name] = $value;
				}
				
				$output .= '<li><strong>' . $name . '</strong> - ' . $value . '</li>';
		}
		$output .= "</ul>";
		update_option('smartestthemes_options',$smartestthemes_array);
		
		flush_rewrite_rules();
	}
	die();
}
add_action('wp_ajax_smartestthemes_ajax_post_action', 'smartestthemes_ajax_callback');

/**
 * Generate Options
 */
function smartestthemes_machine($options) {
        
    $counter = 0;
	$menu = '';
	$output = '';
	foreach ($options as $value) {
	   
		$counter++;
		$val = '';
		//Start Heading
		 if ( $value['type'] != "heading" )
		 {
		 	$class = ''; if(isset( $value['class'] )) { $class = $value['class']; }
			$output .= '<div class="section section-'.$value['type'].' '. $class .'">'."\n";
			if ( !empty($value['name']) ) {
				$output .= '<h3 class="heading">'. $value['name'] .'</h3>'."\n";
			}
			$output .= '<div class="option">'."\n" . '<div class="controls">'."\n";
		 } 
		 //End Heading
		$select_value = '';                                   
		switch ( $value['type'] ) {
		
		case 'text':
			if( !empty($value['std']) ) {
				$val = esc_attr($value['std']);
			}
			$std = esc_attr(get_option($value['id']));
			if ( $std != "") { $val = $std; }
			$output .= '<input class="smartestthemes-input" name="'. $value['id'] .'" id="'. $value['id'] .'" type="'. $value['type'] .'" value="'. stripslashes($val) .'" />';
		break;
		case 'select':
			$output .= '<select class="smartestthemes-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';
			$select_value = get_option($value['id']);
			foreach ($value['options'] as $option) {
				$selected = '';
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';} 
			     } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				  
				 $output .= '<option'. $selected .'>';
				 $output .= $option;
				 $output .= '</option>';
			 
			 } 
			 $output .= '</select>';
		break;
		case 'select2':
			$output .= '<select class="smartestthemes-input" name="'. $value['id'] .'" id="'. $value['id'] .'">';
			$select_value = get_option($value['id']);
			foreach ($value['options'] as $option => $name) {
				$selected = '';
				 if($select_value != '') {
					 if ( $select_value == $option) { $selected = ' selected="selected"';} 
			     } else {
					 if ( isset($value['std']) )
						 if ($value['std'] == $option) { $selected = ' selected="selected"'; }
				 }
				  
				 $output .= '<option'. $selected .' value="'.$option.'">';
				 $output .= $name;
				 $output .= '</option>';
			 
			 } 
			 $output .= '</select>';
			
		break;
		case 'calendar':
		
			$val = $value['std'];
			$std = get_option($value['id']);
			if ( $std != "") { $val = $std; }
            $output .= '<input class="smartestthemes-input-calendar" type="text" name="'.$value['id'].'" id="'.$value['id'].'" value="'.$val.'">';
		
		break;
		case 'time':
			$val = $value['std'];
			$std = get_option($value['id']);
			if ( $std != "") { $val = $std; }
			$output .= '<input class="smartestthemes-input-time" name="'. $value['id'] .'" id="'. $value['id'] .'" type="text" value="'. $val .'" />';
		break;
		case 'textarea':
			
			$cols = '8';
			$ta_value = '';
			
			if(isset($value['std'])) {
				
				$ta_value = $value['std']; 
				
				if(isset($value['options'])){
					$ta_options = $value['options'];
					if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
					} else { $cols = '8'; }
				}
				
			}
				$std = esc_attr(get_option($value['id']));
				if( $std != "") { $ta_value = esc_attr( $std ); }
				$output .= '<textarea class="smartestthemes-input" name="'. $value['id'] .'" id="'. $value['id'] .'" cols="'. $cols .'" rows="8">'.stripslashes($ta_value).'</textarea>';
		break;
		case "radio":
			 $select_value = get_option( $value['id']);
			 foreach ($value['options'] as $key => $option) 
			 { 
				 $checked = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; } 
				   } else {
					if ($value['std'] == $key) { $checked = ' checked'; }
				   }
				$output .= '<input class="smartestthemes-input smartestthemes-radio" type="radio" name="'. $value['id'] .'" value="'. $key .'" '. $checked .' />' . $option .'<br />';
			}
		break;
		case "radio2":
			 $select_value = get_option( $value['id']);
			 foreach ($value['options'] as $key => $option) 
			 { 
				 $checked = '';
				   if($select_value != '') {
						if ( $select_value == $option[2]) { $checked = ' checked'; } 
				   } else {
						$std_radio2 = isset($value['std']) ? $value['std'] : '';
			
					if ($option[2] == $std_radio2 ) { $checked = ' checked'; }
				   }
				$output .= '<input class="smartestthemes-input smartestthemes-radio" type="radio" name="'. $value['id'] .'" value="'. $option[2] .'" '. $checked .' />' . $option[0];

				// image
				$output .= '<img alt="demo" class="demoimg" src="' . $option[1] . '" />';
				$output .= '<br />';
			}
		break;
		case "checkbox": 
	if( !empty($value['std']) ) {
			$std = $value['std'];
	}
		   $saved_std = get_option($value['id']);
		   $checked = '';
			if(!empty($saved_std)) {
				if($saved_std == 'true') {
				$checked = 'checked="checked"';
				}
				else{
				   $checked = '';
				}
			}
			elseif( $std == 'true') {
			   $checked = 'checked="checked"';
			}
			else {
				$checked = '';
			}
			$output .= '<input type="checkbox" class="checkbox smartestthemes-input" name="'.  $value['id'] .'" id="'. $value['id'] .'" value="true" '. $checked .' />';

		break;
		case "multicheck":
		
			$std = ! empty($value['std']) ? $value['std'] : '';
			$multiclass = ! empty($value['class']) ? $value['class'] : '';
			foreach ($value['options'] as $key => $option) {
											 
				$smartestthemes_key = $value['id'] . '_' . $key;
				$saved_std = get_option($smartestthemes_key);
						
				if(!empty($saved_std)) { 
					  if($saved_std == 'true'){
						 $checked = 'checked="checked"';  
					  } 
					  else{
						  $checked = '';     
					  }
				}
				elseif( $std == $key) {
				   $checked = 'checked="checked"';
				}
				else {
					$checked = '';
				}

				$output .= '<input type="checkbox" class="checkbox smartestthemes-input" name="'. $smartestthemes_key .'" id="'. $smartestthemes_key .'" value="true" '. $checked .' /><label for="'. $smartestthemes_key .'">'. $option .'</label>';
				
				if ( ! $multiclass ) {
					$output .= '<br />';
				}
										
			}
		break;
		case "upload":
			$output .= smartestthemes_uploader_function($value['id'],$value['std'],null);
		break;
		case "upload_min":
			$output .= smartestthemes_uploader_function($value['id'],$value['std'],'min');
		break;
		case "images":
			$i = 0;
			$select_value = get_option( $value['id']);
			foreach ($value['options'] as $key => $option) 
			 { 
			 $i++;
				 $checked = '';
				 $selected = '';
				   if($select_value != '') {
						if ( $select_value == $key) { $checked = ' checked'; $selected = 'smartestthemes-radio-img-selected'; } 
				    } else {
						if ($value['std'] == $key) { $checked = ' checked'; $selected = 'smartestthemes-radio-img-selected'; }
						elseif ($i == 1  && !isset($select_value)) { $checked = ' checked'; $selected = 'smartestthemes-radio-img-selected'; }
						elseif ($i == 1  && $value['std'] == '') { $checked = ' checked'; $selected = 'smartestthemes-radio-img-selected'; }
						else { $checked = ''; }
					}	
				
				$output .= '<span>';
				$output .= '<input type="radio" id="smartestthemes-radio-img-' . $value['id'] . $i . '" class="checkbox smartestthemes-radio-img-radio" value="'.$key.'" name="'. $value['id'].'" '.$checked.' />';
				$output .= '<div class="smartestthemes-radio-img-label">'. $key .'</div>';
				$output .= '<img src="'.$option.'" alt="" class="smartestthemes-radio-img-img '. $selected .'" onClick="document.getElementById(\'smartestthemes-radio-img-'. $value['id'] . $i.'\').checked = true;" />';
				$output .= '</span>';
				
			}
		break; 
		case "info":
			$default = $value['std'];
			$output .= $default;
		break;                                   
		case "heading":
			if($counter >= 2){
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = preg_replace('#[^A-Za-z0-9]#', '', strtolower($value['name']) );
			$jquery_click_hook = "smartestthemes-option-" . $jquery_click_hook;
					$menu .= '<li><a ';
					if ( !empty( $value['class'] ) ) {
						$menu .= 'class="'.  $value['class'] .'" ';
					}
					$menu .= 'title="'.  $value['name'] .'" href="#'.  $jquery_click_hook  .'"><span class="smartestthemes-nav-icon"></span>'.  $value['name'] .'</a></li>';
			$output .= '<div class="group" id="'. $jquery_click_hook  .'"><h2>'.$value['name'].'</h2>'."\n";
		break;                                  
		} 
		// if TYPE is an array, formatted into smaller inputs... ie smaller values
		if ( is_array($value['type'])) {
			foreach($value['type'] as $array){
			
				$id =   $array['id']; 
				$std =   $array['std'];
				$saved_std = get_option($id);
				if($saved_std != $std && !empty($saved_std) ){$std = $saved_std;} 
				$meta =   $array['meta'];
					
				if($array['type'] == 'text') { // Only text at this point
						 
						 $output .= '<input class="input-text-small smartestthemes-input" name="'. $id .'" id="'. $id .'" type="text" value="'. $std .'" />';  
						 $output .= '<span class="meta-two">'.$meta.'</span>';
					}
				}
		}
		if ( $value['type'] != "heading" ) { 
			if ( $value['type'] != "checkbox" ) 
				{ 
				$output .= '<br/>';
				}
			if(!isset($value['desc'])){ $explain_value = ''; } else{ $explain_value = $value['desc']; } 
			$output .= '</div><div class="explain">'. $explain_value .'</div>'."\n";
			$output .= '<div class="clear"> </div></div></div>'."\n";
			}
	   
	}
    $output .= '</div>';
    return array($output,$menu);

}
/* Smartest Themes Uploader */
function smartestthemes_uploader_function($id,$std,$mod){
	$uploader = '';
	$upload = get_option($id);
	if($mod != 'min') { 
			$val = $std;
            if ( get_option( $id ) != "") { $val = get_option($id); }
            $uploader .= '<input class="smartestthemes-input" name="'. $id .'" id="'. $id .'_upload" type="text" value="'. $val .'" />';
	}
	$uploader .= '<div class="upload_button_div"><span class="button image_upload_button" id="'.$id.'">'. __('Upload Image', 'crucible'). '</span>';
	if(!empty($upload)) {$hide = '';} else { $hide = 'hide';}
	$uploader .= '<span class="button image_reset_button '. $hide.'" id="reset_'. $id .'" title="' . $id . '">'. __('Remove', 'crucible'). '</span>';
	$uploader .='</div>' . "\n";
    $uploader .= '<div class="clear"></div>' . "\n";
	if(!empty($upload)){
    	$uploader .= '<a class="smartestthemes-uploaded-image" href="'. $upload . '">';
    	$uploader .= '<img class="smartestthemes-option-image" id="image_'.$id.'" src="'.$upload.'" alt="" />';
    	$uploader .= '</a>';
		}
	$uploader .= '<div class="clear"></div>' . "\n"; 
return $uploader;
}
/**
 * Create Admin Menu Separator
 **/
function add_smar_admin_menu_separator($position) {
	global $menu;
	$index = 0;
	foreach($menu as $offset => $section) {
		if (substr($section[2],0,9)=='separator')
		    $index++;
		if ($offset>=$position) {
			$menu[$position] = array('','read',"separator{$index}",'','wp-menu-separator');
			break;
	    }
	}
	ksort( $menu );
}
?>