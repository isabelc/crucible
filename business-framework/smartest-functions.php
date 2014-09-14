<?php
/** 
 * Smartest Themes Framework Functions
 * @package    Smartest Themes Business Framework
*/

function smartestthemes_login_logo() {

	global $smartestthemes_options;
	$buslogo =  empty($smartestthemes_options['logo_setting']) ? '' : $smartestthemes_options['logo_setting'];
	
	// if there is a logo, show it, else do text
	if ($buslogo) {
		$small_logo = vt_resize( '', $buslogo, 326, 67, false );
	    echo '<style>.login h1 a { background: url('.$small_logo['url'].') 50% 50% no-repeat !important;width: 326px;height: 70px;}</style>';
	} else {
		// @new default next 2
		$col = empty($smartestthemes_options['logo_color']) ? '#000' : $smartestthemes_options['logo_color'];
		$font = empty($smartestthemes_options['logo_font']) ? 'Copperplate Bold, Copperplate Gothic Bold, serif' : $smartestthemes_options['logo_font'];
		$custom_fonts = get_option('crucible_login_font_css');
		echo '<style>' . $custom_fonts . '.login h1 a {background-position: center top;text-indent: 0px;text-align:center; background-image:none;text-decoration:none;font-family:'. $font . ';color:'.$col. ';padding-top: 3px;width: 326px;height: 70px;}</style>';
	}
}
add_action('login_head', 'smartestthemes_login_logo');
add_filter('login_headerurl',
    create_function(false,"return get_home_url();"));
function isacustom_wp_login_title() {
	return get_bloginfo('name');
}
add_filter('login_headertitle', 'isacustom_wp_login_title');

/**
 * Create a page, post, or custom post
 * @param string $potype post type to insert
 * @param mixed $slug Slug for the new page
 * @param mixed $option Option name to store the page's ID
 * @param string $page_title (default: '') Title for the new page
 * @param string $page_content (default: '') Content for the new page
 * @param int $post_parent (default: 0) Parent for the new page
 */
function smartestthemes_insert_post($potype, $slug, $option, $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;
	
	// If page already been created by theme, get out
	$option_value = get_option( $option );
	if ( $option_value > 0 && get_post( $option_value ) ) {
		return;
	}
	
	$page_data = array(
        'post_status' 		=> 'publish',
        'post_type' 		=> $potype,
        'post_author' 		=> 1,
        'post_name' 		=> $slug,
        'post_title' 		=> $page_title,
        'post_content' 		=> $page_content,
        'post_parent' 		=> $post_parent,
        'comment_status' 	=> 'closed'
    );
    $page_id = wp_insert_post( $page_data );
    update_option( $option, $page_id );
}
/**
 * Create About, Home pages and activate Reviews.
 * @uses smartestthemes_insert_post()
 */
function smartestthemes_after_setup() {
	$options = get_option('smartestthemes_options');
	$stop_about = empty($options['st_stop_about']) ? '' : $options['st_stop_about'];
	$bn = empty($options['st_business_name']) ? get_bloginfo('name') : stripslashes_deep(esc_attr($options['st_business_name']));
	$reviews = empty($options['st_add_reviews']) ? '' : $options['st_add_reviews'];
	$stop_home = empty($options['st_stop_home']) ? '' : $options['st_stop_home'];
	
	$atitle = sprintf(__('About %s','crucible'), $bn);
	// if not disabled in options 
	if($stop_about != 'true')
		smartestthemes_insert_post( 'page', esc_sql( _x('about', 'page_slug', 'crucible') ), 'smartestthemes_about_page_id', $atitle, '' );
	if($stop_home != 'true')
		smartestthemes_insert_post( 'page', esc_sql( _x('home', 'page_slug', 'crucible') ), 'smartestthemes_home_page_id', __('Home', 'crucible'), '' );
	// Activate Reviews
	if (!class_exists('SMARTESTReviewsBusiness') && ($reviews == 'true'))
		include_once get_template_directory() .'/business-framework/modules/reviews/reviews.php';

}
add_action('after_setup_theme','smartestthemes_after_setup');

$options = get_option( 'smartestthemes_options' );

/**
 * if about page is disabled, delete it
 */
 
if ( isset($options['st_stop_about']) ) {
	if($options['st_stop_about'] == 'true') {
		wp_delete_post(get_option('smartestthemes_about_page_id'), true);
	}
}

/**
 * if auto Home page is disabled, delete it
 */
$home_page_id = get_option('smartestthemes_home_page_id');

if ( isset($options['st_stop_home']) ) {
	if( $options['st_stop_home'] == 'true') {
		wp_delete_post($home_page_id, true);
	}
}
update_post_meta($home_page_id, '_wp_page_template', 'smar-home.php');

/**
 * set static front page, unless disabled
 */
if ( isset($options['st_stop_static']) ) {
	if( $options['st_stop_static'] != 'true' ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_page_id );
	}
}

// Set the blog page, unless disabled
if ( isset($options['st_stop_blog']) ) {

	if( $options['st_stop_blog'] != 'true') {
		$blog   = get_page_by_title(__('Blog', 'crucible') );
		if($blog) {
			update_option( 'page_for_posts', $blog->ID );
		}
	}
}

/*
 * Resize images dynamically using wp built in functions
 * Victor Teixeira
 * Modified by Isabel Castillo
 * php 5.2+
 * Example of use:
 * $thumb = get_post_thumbnail_id(); 
 * $image = vt_resize( $thumb, '', 140, 110, true );// or image url for 2nd param
 * echo $image['url']; ? >" width="< ? p h p  echo $image[width]; ? >" height=" < ? p h p echo $image[height]; ? >" />
 * @param int $attach_id
 * @param string $img_url
 * @param int $width
 * @param int $height
 * @param bool $crop
 * @return array
 */
function vt_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
	// this is an attachment, so we have the ID
	if ( $attach_id ) {
		$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
		$file_path = get_attached_file( $attach_id );
	// this is not an attachment, let's use the image url
	} else if ( $img_url ) {
		$file_path = parse_url( $img_url );
			$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];//isa use this path instead
			$orig_size = getimagesize( $file_path );
			$image_src[0] = $img_url;
			$image_src[1] = $orig_size[0];
			$image_src[2] = $orig_size[1];
	}
	$file_info = pathinfo( $file_path );
	$extension = !empty($file_info['extension']) ? '.'. $file_info['extension'] : '';
	// the image path without the extension
	$no_ext_path = !empty($file_info['dirname']) ? $file_info['dirname'].'/'.$file_info['filename'] : '';
	$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
	// checking if the file size is larger than the target size
	// if it is smaller or the same size, stop right here and return
	if ( $image_src[1] > $width || $image_src[2] > $height ) {
		// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
		if ( file_exists( $cropped_img_path ) ) {
			$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
			$vt_image = array (
				'url' => $cropped_img_url,
				'width' => $width,
				'height' => $height
			);
			
			return $vt_image;
		}
		if ( $crop == false ) {
			// calculate the size proportionaly
			$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
			$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;			
			// checking if the file already exists
			if ( file_exists( $resized_img_path ) ) {
				$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
				$vt_image = array (
					'url' => $resized_img_url,
					'width' => $proportional_size[0],
					'height' => $proportional_size[1]
				);
				return $vt_image;
			}
		}
		// no cache files - let's finally resize it
		$editor = wp_get_image_editor( $file_path );// replace image_resize
		if ( is_wp_error( $editor ) )
		    return $editor;
		$editor->set_quality( 100 );
		$resized = $editor->resize( $width, $height, $crop );
		$dest_file = $editor->generate_filename( NULL, NULL );
		$saved = $editor->save( $dest_file );
		if ( is_wp_error( $saved ) )
		    return $saved;
		$new_img_path=$dest_file;
		$new_img_size = getimagesize( $new_img_path );
		$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

		// resized output
		$vt_image = array (
			'url' => $new_img,
			'width' => $new_img_size[0],
			'height' => $new_img_size[1]
		);
		return $vt_image;
	}
	// default output - without resizing
	$vt_image = array (
		'url' => $image_src[0],
		'width' => $image_src[1],
		'height' => $image_src[2]
	);
	return $vt_image;
}
/** 
 * add CPTs conditionally, if enabled
 * adds smartest_staff, smartest_staff, smartest_staff, 
 */
function create_smartest_business_cpts() {
	$options = get_option('smartestthemes_options');
	$staff = empty($options['st_show_staff']) ? '' : $options['st_show_staff'];
	$news = empty($options['st_show_news']) ? '' : $options['st_show_news'];
	$services = empty($options['st_show_services']) ? '' : $options['st_show_services'];
	$slideshow =  empty($options['st_show_slider']) ? '' : $options['st_show_slider'];
	
	if( $staff == 'true'  ) {
    	$args = array(
        	'label' => __('Staff','crucible'),
        	'singular_label' => __('Staff','crucible'),
        	'public' => true,
        	'show_ui' => true,
        	'capability_type' => 'post',
        	'hierarchical' => false,
        	'rewrite' => array(
					'slug' => __('staff', 'crucible'),
					'with_front' => false,
			),
        	'exclude_from_search' => false,
       		'labels' => array(
				'name' => __( 'Staff','crucible' ),
				'singular_name' => __( 'Staff','crucible' ),
				'add_new' => __( 'Add New','crucible' ),
				'add_new_item' => __( 'Add New Staff','crucible' ),
				'all_items' => __( 'All Staff','crucible' ),
				'edit' => __( 'Edit','crucible' ),
				'edit_item' => __( 'Edit Staff','crucible' ),
				'new_item' => __( 'New Staff','crucible' ),
				'view' => __( 'View Staff','crucible' ),
				'view_item' => __( 'View Staff','crucible' ),
				'search_items' => __( 'Search Staff','crucible' ),
				'not_found' => __( 'No staff found','crucible' ),
				'not_found_in_trash' => __( 'No staff found in Trash','crucible' ),
				'parent' => __( 'Parent Staff','crucible' ),
			),
        	'supports' => array('title','editor','thumbnail'),
			'has_archive' => true,
			'menu_icon' => 'dashicons-groups',
        );
		register_post_type( 'smartest_staff' , $args );
	}// end if show staff enabled
	
	if($news == 'true') {
    	$args = array(
        	'label' => __('Announcements','crucible'),
        	'singular_label' => __('Announcement','crucible'),
        	'public' => true,
        	'show_ui' => true,
        	'capability_type' => 'post',
        	'hierarchical' => false,
        	'rewrite' => array(
					'slug' => __('news','crucible'),
					'with_front' => false,
			),
        	'exclude_from_search' => false,
       		'labels' => array(
				'name' => __( 'Announcements','crucible' ),
				'singular_name' => __( 'Announcement','crucible' ),
				'add_new' => __( 'Add New','crucible' ),
				'add_new_item' => __( 'Add New Announcement','crucible' ),
				'all_items' => __( 'All Announcements','crucible' ),
				'edit' => __( 'Edit','crucible' ),
				'edit_item' => __( 'Edit Announcement','crucible' ),
				'new_item' => __( 'New Announcement','crucible' ),
				'view' => __( 'View Announcement','crucible' ),
				'view_item' => __( 'View Announcement','crucible' ),
				'search_items' => __( 'Search Announcements','crucible' ),
				'not_found' => __( 'No announcement found','crucible' ),
				'not_found_in_trash' => __( 'No announcements found in Trash','crucible' ),
				'parent' => __( 'Parent Announcement','crucible' ),
			),
			'supports' => array('title','editor','thumbnail'),
			'has_archive' => true,
			'menu_icon' => 'dashicons-exerpt-view'
		);
		register_post_type( 'smartest_news' , $args );
	}// end if show news enabled
	
	if($services == 'true') {
    	$args = array(
        	'label' => __('Services','crucible'),
        	'singular_label' => __('Service','crucible'),
        	'public' => true,
        	'show_ui' => true,
        	'capability_type' => 'post',
        	'hierarchical' => false,
        	'rewrite' => array(
					'slug' => __('services','crucible'),
					'with_front' => false,
			),
        	'exclude_from_search' => false,
       		'labels' => array(
				'name' => __( 'Services','crucible' ),
				'singular_name' => __( 'Service','crucible' ),
				'add_new' => __( 'Add New','crucible' ),
				'all_items' => __( 'All Services','crucible' ),
				'add_new_item' => __( 'Add New Service','crucible' ),
				'edit' => __( 'Edit','crucible' ),
				'edit_item' => __( 'Edit Service','crucible' ),
				'new_item' => __( 'New Service','crucible' ),
				'view' => __( 'View Services','crucible' ),
				'view_item' => __( 'View Service','crucible' ),
				'search_items' => __( 'Search Services','crucible' ),
				'not_found' => __( 'No services found','crucible' ),
				'not_found_in_trash' => __( 'No services found in Trash','crucible' ),
				'parent' => __( 'Parent Service','crucible' ),
				),
			'supports' => array('title','editor','thumbnail'),
			'has_archive' => true,
			'menu_icon' => 'dashicons-portfolio'
		);
	   	register_post_type( 'smartest_services' , $args );
	}// end if show services enabled

	// @new if show homepage slideshow is enabled, do cpt
	if( $slideshow == 'true'  ) {
		$args = array(
			'label' => __('Slideshow','storefront'),
			'singular_label' => __('Slide','storefront'),
			'public' => true,
			'show_ui' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'rewrite' => true,
			'exclude_from_search' => true,
			'labels' => array(
				'name' => __( 'Slideshow','storefront' ),
				'singular_name' => __( 'Slide','storefront' ),
				'add_new' => __( 'Add New Slide','storefront' ),
				'all_items' => __( 'All Slides','crucible' ),
				'add_new_item' => __( 'Add New Slide','storefront' ),
				'edit' => __( 'Edit','storefront' ),
				'edit_item' => __( 'Edit Slide','storefront' ),
				'new_item' => __( 'New Slide','storefront' ),
				'view' => __( 'View Slide','storefront' ),
				'view_item' => __( 'View Slide','storefront' ),
				'search_items' => __( 'Search Slides','storefront' ),
				'not_found' => __( 'No slides found','storefront' ),
				'not_found_in_trash' => __( 'No slides found in Trash','storefront' ),
				'parent' => __( 'Parent Slide','storefront' ),
			),
			'menu_icon' => 'dashicons-format-image',
			'supports' => array('title', 'thumbnail')
		);
		register_post_type( 'smartest_slide' , $args );
	}	// end slideshow
}
add_action('init', 'create_smartest_business_cpts');
/**
 * Registers custom taxonomy for services
 * @return void
 */
function smartestthemes_taxonomies() {
	$category_labels = array(
		'name' => __( 'Service Categories', 'crucible' ),
		'singular_name' =>__( 'Service Category', 'crucible' ),
		'search_items' => __( 'Search Service Categories', 'crucible' ),
		'all_items' => __( 'All Service Categories', 'crucible' ),
		'parent_item' => __( 'Service Parent Category', 'crucible' ),
		'parent_item_colon' => __( 'Service Parent Category:', 'crucible' ),
		'edit_item' => __( 'Edit Service Category', 'crucible' ),
		'update_item' => __( 'Update Service Category', 'crucible' ),
		'add_new_item' => __( 'Add New Service Category', 'crucible' ),
		'new_item_name' => __( 'New Service Category Name', 'crucible' ),
		'menu_name' => __( 'Service Categories', 'crucible' ),
	);
	$category_args = apply_filters( 'smartestthemes_service_category_args', array(
		'hierarchical'		=> true,
		'labels'			=> apply_filters('smartestthemes_service_category_labels', $category_labels),
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'		=> true,
		'rewrite'			=> array(
							'slug'		=> 'services/category',
							'with_front'	=> false,
							'hierarchical'	=> true ),
	)
	);
	register_taxonomy( 'smartest_service_category', array('smartest_services'), $category_args );
	register_taxonomy_for_object_type( 'smartest_service_category', 'smartest_services' );
}
add_action( 'init', 'smartestthemes_taxonomies', 0 );
/**
 * upload slideshow images, attach to slide posts and set as featured image (the post thumbnail)
 *
 * Additional functionality: ability to pass $post_data to override values in wp_insert_attachment
 *
 * @param string $url (required) The URL of the image to download
 * @param int $post_id (required) The post ID the media is to be associated with
 * @param array $post_data (optional) Array of key => values for wp_posts table (ex: 'post_title' => 'foobar', 'post_status' => 'draft')
 * @return int|object The ID of the attachment or a WP_Error on failure
 */
function smart_attach_external_image( $url = null, $post_id = null, $post_data = array() ) {
	    if ( !$url || !$post_id ) return new WP_Error('missing', "Need a valid URL and post ID...");
	    require_once ABSPATH . 'wp-admin/includes/file.php';
	    // Download file to temp location, returns full server path to temp file, ex; /home/user/public_html/mysite/wp-content/26192277_640.tmp
	    $tmp = download_url( $url );
	
	    // If error storing temporarily, unlink
	    if ( is_wp_error( $tmp ) ) {
	        @unlink($file_array['tmp_name']);   // clean up
	        $file_array['tmp_name'] = '';
	        return $tmp; // output wp_error
	    }
	
	    preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $url, $matches);    // fix file filename for query strings
	    $url_filename = basename($matches[0]);                                                  // extract filename from url for title
	    $url_type = wp_check_filetype($url_filename);                                           // determine file type (ext and mime/type)
	
	    // assemble file data (should be built like $_FILES since wp_handle_sideload() will be using)
	    $file_array['tmp_name'] = $tmp;                                                         // full server path to temp file

	        $file_array['name'] = $url_filename;
	
	    // set additional wp_posts columns
	    if ( empty( $post_data['post_title'] ) ) {
	        $post_data['post_title'] = basename($url_filename, "." . $url_type['ext']);         // just use the original filename (no extension)
	    }
	
	    // make sure gets tied to parent
	    if ( empty( $post_data['post_parent'] ) ) {
	        $post_data['post_parent'] = $post_id;
	    }
	
	    // required libraries for media_handle_sideload
	    require_once ABSPATH . 'wp-admin/includes/file.php';
	    require_once ABSPATH . 'wp-admin/includes/media.php';
	    require_once ABSPATH . 'wp-admin/includes/image.php';
	
	    // do the validation and storage stuff
	    $att_id = media_handle_sideload( $file_array, $post_id, null, $post_data );             // $post_data can override the items saved to wp_posts table, like post_mime_type, guid, post_parent, post_title, post_content, post_status
	
	    // If error storing permanently, unlink
	    if ( is_wp_error($att_id) ) {
	        @unlink($file_array['tmp_name']);   // clean up
	        return $att_id; // output wp_error
	    }
	
	    // set as post thumbnail if desired
	        set_post_thumbnail($post_id, $att_id);
	
	    return $att_id;
}
/**
* Filter the custom menu labels to apply custom text for fallback menu items.
*/
// filter the Services menu label to apply custom text
function custom_smartestthemes_services_menu_label() {
	global $smartestthemes_options;
	$custom = empty($smartestthemes_options['st_business_servicesmenulabel']) ? __('Services', 'crucible') : stripslashes($smartestthemes_options['st_business_servicesmenulabel']);
	return $custom;
}
// filter the Staff menu label to apply custom text
function custom_smartestthemes_staff_menu_label() {
	global $smartestthemes_options;
	$custom = empty($smartestthemes_options['st_business_staffmenulabel']) ? __('Staff', 'crucible') : stripslashes($smartestthemes_options['st_business_staffmenulabel']);
	return $custom;
}
// filter the News menu label to apply custom text
function custom_smartestthemes_news_menu_label() {
	global $smartestthemes_options;
	$custom = empty($smartestthemes_options['st_business_newsmenulabel']) ? __('News', 'crucible') : stripslashes($smartestthemes_options['st_business_newsmenulabel']);
	return $custom;
}
add_filter( 'smartestthemes_services_menu_label', 'custom_smartestthemes_services_menu_label' );
add_filter( 'smartestthemes_staff_menu_label', 'custom_smartestthemes_staff_menu_label' );
add_filter( 'smartestthemes_news_menu_label', 'custom_smartestthemes_news_menu_label' );

/**
 * Custom metaboxes and fields
 * for staff cpt: occupational title & social links
 */
add_filter( 'cmb_meta_boxes', 'smartestthemes_metaboxes' );
/**
 * Define the metabox and field configurations.
 * @param  array $meta_boxes
 * @return array
 */
function smartestthemes_metaboxes( array $meta_boxes ) {
	$prefix = '_stmb_';
	global $smartestthemes_options;
	$meta_boxes[] = array(
		'id'         => 'staff_details',
		'title'      => __('Details', 'crucible'),
		'pages'      => array( 'smartest_staff', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Job Title', 'crucible'),
				'desc' => __('The staff member\'s job title. Optional', 'crucible'),
				'id'   => $prefix . 'staff_job_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __( 'Sort Order Number', 'crucible' ),
				'desc' => __( 'Give this person a number to order them on the list on the staff page and in the staff widget. Numbers do not have to be consecutive; for example, you could number them like, 10, 20, 35, 45, etc. This would leave room to insert new staff members later without having to change everyone\'s current number.', 'crucible' ),
				'id'   => $prefix . 'staff_order_number',
				'type' => 'text',
				'std' => 9999
			),
			array(
				'name' => __('Facebook Profile ID', 'crucible'),
				'desc' => __('The staff member\'s Facebook profile ID. Optional', 'crucible'),
				'id'   => $prefix . 'staff_facebook',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Twitter Username', 'crucible'),
				'desc' => __('The staff member\'s Twitter username. Optional', 'crucible'),
				'id'   => $prefix . 'staff_twitter',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Google Plus Profile ID', 'crucible'),
				'desc' => __('The staff member\'s Google Plus profile ID. Optional', 'crucible'),
				'id'   => $prefix . 'staff_gplus',
				'type' => 'text_medium',
			),
			 array(
				'name' => __('Linkedin Profile', 'crucible'),
				'desc' => __('The part of the profile address after "www.linkedin.com/". Optional', 'crucible'),
				'id' => $prefix . 'staff_linkedin',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Instagram Username', 'crucible'),
				'desc' => __('The part of the profile address after "www.instagram.com/". Optional', 'crucible'),
				'id' => $prefix . 'staff_instagram',
				'type' => 'text_medium',
			),			
		)
	);

	// services 'featured' meta box
	$meta_boxes[] = array(
		'id'         => 'featured_svcs',
		'title'      => __('Featured Services', 'crucible'),
		'pages'      => array( 'smartest_services', ),
		'context'    => 'side',
		'priority'   => 'default',//high, core, default, low
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Feature this?', 'crucible'),
				'desc' => __('Check this box to feature this service in the list of featured services on the home page and in the Featured Services widget.', 'crucible'),
				'id'   => $prefix . 'services_featured',
				'type' => 'checkbox',
			),
		)
	);

		
	$meta_boxes[] = array(
		'id'         => 'services-sort-order',
		'title'      => __( 'Set a Sort-Order', 'crucible' ),
		'pages'      => array( 'smartest_services' ),
		'context'    => 'normal',
		'priority'   => 'high',//high, core, default, low
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __( 'Sort Order Number', 'crucible' ),
				'desc' => __( 'Give this service a number to order it on the list on the service page and in the services widget. Numbers do not have to be consecutive; for example, you could number them like, 10, 20, 35, 45, etc. This would leave room to insert new services later without having to change all current numbers.', 'crucible' ),
				'id'   => $prefix . 'service_order_number',
				'type' => 'text',
				'std' => 9999
			),
		)
	);
	
	$meta_boxes[] = array(
		'id'         => 'featured_news',
		'title'      => __('Featured News', 'crucible'),
		'pages'      => array( 'smartest_news', ),
		'context'    => 'side',
		'priority'   => 'default',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __('Feature this?', 'crucible'),
				'desc' => __('Check this box to feature this announcement in the Featured Announcements widget.', 'crucible'),
				'id'   => $prefix . 'news_featured',
				'type' => 'checkbox',
			),
		)
	);
	$meta_boxes[] = array(
		'id'         => 'home_slideshow',
		'title'      => __('Slideshow', 'crucible'),
		'pages'      => array( 'smartest_slide' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Add A Picture', 'crucible'),
				'desc' => sprintf(__('Set a featured image for this slide by clicking "Set featured image", which is normally located on the right hand side of this page. %s', 'crucible'),
get_option('st_sshow_description')),
				'id'   => $prefix . 'slide_title',
				'type' => 'title',
			),
		)
	);

	return apply_filters( 'smartestthemes_cmb', $meta_boxes );
}

add_action( 'init', 'smar_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function smar_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'lib/metabox/init.php';
}

/**
 * 'Enter Staff member's name here' instead of 'Enter title here'
 * for smartest_staff cpt
 */
function smartest_change_enter_title( $title ){
	$screen = get_current_screen();
	if  ( 'smartest_staff' == $screen->post_type ) {
		$title = __('Enter staff member\'s name here', 'crucible');} return $title;
}
add_filter( 'enter_title_here', 'smartest_change_enter_title' );

/* Flush rewrite rules for custom post types but only once upon theme activation 
*/
function smartest_flush_rewrite_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
	update_option('st_stop_home', 'false');
}
add_action('after_switch_theme', 'smartest_flush_rewrite_rules', 10, 2);
/**
 * register widgets
 */
function smartestthemes_register_widgets() {
	$options = get_option('smartestthemes_options');
	$svcs = empty($options['st_show_services']) ? '' : $options['st_show_services'];
	$staff = empty($options['st_show_staff']) ? '' : $options['st_show_staff'];
	$news = empty($options['st_show_news']) ? '' : $options['st_show_news'];
	if( $news == 'true'  ) { 
		register_widget('SmartestAnnouncements');
		register_widget('SmartestFeaturedAnnounce');
	}
	if( $svcs == 'true'  ) { 
		register_widget('SmartestServices'); register_widget('SmartestFeaturedServices');
	}
	if( $staff == 'true' ) {
		register_widget('SmartestStaff');
	}
}
add_action( 'widgets_init', 'smartestthemes_register_widgets' );
/**
 * insert custom scripts from theme options into head
 */
function smartestthemes_add_customscripts() {
	global $smartestthemes_options;
	// get analytics script
	if ( ! empty($smartestthemes_options['st_script_analytics']) ) {
		echo stripslashes($smartestthemes_options['st_script_analytics'])."\r\n";
	}
	// get other scripts
	if ( ! empty($smartestthemes_options['st_scripts_head']) ) {
		echo stripslashes($smartestthemes_options['st_scripts_head'])."\r\n";
	}
}
add_action('wp_head','smartestthemes_add_customscripts', 12);

/* 
 * Filter archive page titles to allow custom heading
 * for staff, services, and news
 */
function custom_staff_heading() {
	global $smartestthemes_options;
	$staffpagetitle = empty($smartestthemes_options['st_business_staffpagetitle']) ? '' : stripslashes($smartestthemes_options['st_business_staffpagetitle']);
	
	if ( $staffpagetitle ) {
		echo $staffpagetitle;
	} else {
		_e('Meet The Staff', 'crucible');
	}
}
add_filter('smartestthemes_staff_heading', 'custom_staff_heading');
function custom_services_heading() {
	global $smartestthemes_options;
	$servicepagetitle = empty( $smartestthemes_options['st_business_servicespagetitle'] ) ? '' : stripslashes($smartestthemes_options['st_business_servicespagetitle']);
	
	if ( $servicepagetitle ) {
		echo $servicepagetitle;
	} else { 
		_e('Services', 'crucible');
	}
}
add_filter('smartestthemes_services_heading', 'custom_services_heading');

function custom_news_heading() {
	global $smartestthemes_options;
	$newspagetitle = empty( $smartestthemes_options['st_business_newspagetitle'] ) ? '' : stripslashes($smartestthemes_options['st_business_newspagetitle']);
	if ( $newspagetitle ) {
		echo $newspagetitle;
	} else {
		_e('Announcements', 'crucible');
	}
}
add_filter('smartestthemes_news_heading', 'custom_news_heading');
/**
 * Include the Smartest_MCE_Table_Buttons class.
 */
include dirname( __FILE__ ) . '/lib/mce-table/mce_table_buttons.php';

/**
 * Change WP tool bar
 * Add link to theme options
 */
function smartestthemes_tool_bar() {

	$themeobject = wp_get_theme();
	$themename = $themeobject->Name;
	$themeslug = $themeobject->Template;
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'appearance',
		'id'		=> 'smartestthemes-options',
		'title'	=> $themename. __(' Options', 'crucible'),
		'href'	=> admin_url( "admin.php?page=$themeslug" )
	));
}
add_action( 'wp_before_admin_bar_render', 'smartestthemes_tool_bar' );

/** 
 * Add job title column to staff admin
 */
add_filter( 'manage_edit-smartest_staff_columns', 'smar_manage_edit_staff_columns' );
function smar_manage_edit_staff_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Name', 'crucible'),
		'jobtitle' => __('Job Title', 'crucible'),
		'date' => __('Date', 'crucible')
	);

	return $columns;
}
/** 
 * Add data to job title column in staff admin
 */

add_action( 'manage_smartest_staff_posts_custom_column', 'smar_manage_staff_columns', 10, 2 );
function smar_manage_staff_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'jobtitle' :
			$jobtitle = get_post_meta( $post_id, '_stmb_staff_job_title', true );
			 echo $jobtitle;
			break;
		default :
			break;
	}
}

/** 
 * Add featured service column to services admin
 */
add_filter( 'manage_edit-smartest_services_columns', 'smar_manage_edit_services_columns' ) ;
function smar_manage_edit_services_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Title', 'crucible'),
		'taxonomy-smartest_service_category' => __('Categories', 'crucible'),
		'featureds' => __('Featured', 'crucible'),
		'date' => __('Date', 'crucible')
	);
	return $columns;
}

/** 
 * Add data to featured services column in services admin
 */
add_action( 'manage_smartest_services_posts_custom_column', 'smar_manage_services_columns', 10, 2 );
function smar_manage_services_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'featureds' :
			$sf = get_post_meta( $post_id, '_stmb_services_featured', true );
			if ( $sf )
				_e('Featured', 'crucible');
			break;
		default :
			break;
	}
}

/** 
 * Add featured news column to news admin
 */

add_filter( 'manage_edit-smartest_news_columns', 'smar_manage_edit_news_columns' ) ;
function smar_manage_edit_news_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Title', 'crucible'),
		'featuredn' => __('Featured', 'crucible'),
		'date' => __('Date', 'crucible')
	);
	return $columns;
}

/** 
 * Add data to featured news column in news admin
 */
add_action( 'manage_smartest_news_posts_custom_column', 'smar_manage_news_columns', 10, 2 );
function smar_manage_news_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'featuredn' :
			$sf = get_post_meta( $post_id, '_stmb_news_featured', true );
			if ( $sf )
				_e('Featured', 'crucible');
			break;
		default :
			break;
	}
}
/**
 * Add thumbnail column to smartest_slide admin
 */
function smar_manage_edit_slide_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Title', 'crucible'),
		'thumb' => __('Thumbnail', 'crucible'),
		'date' => __('Date', 'crucible')
	);
	return $columns;
}

/**
 * Add data to thumbnail column in smartest_slide
 */
function smar_manage_slide_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'thumb' :
		if ( has_post_thumbnail() ) {
			$imgid = get_post_thumbnail_id(); 
			$sthumb = vt_resize( $imgid, '', 60, 60, true);
			echo '<img src="'.$sthumb['url'].'" width="60" height="60" alt="'.the_title_attribute('echo=0').'" />';
		}
			break;
		default :
			break;
	}
}

// @new only need for slides
if ( isset($options['st_show_slider']) ) {
	if ( $options['st_show_slider'] == 'true') {
		add_filter( 'manage_edit-smartest_slide_columns', 'smar_manage_edit_slide_columns' ) ;
		add_action( 'manage_smartest_slide_posts_custom_column', 'smar_manage_slide_columns', 10, 2 );
	}
}

/**
 * Options Page Branding
 * use custom logo on theme options page header
 */
function st_custom_options_page_logo() {
	global $smartestthemes_options;
	$logo = empty($smartestthemes_options['st_backend_logo']) ? '' : $smartestthemes_options['st_backend_logo'];
	if($logo) {
		return '<img alt="logo" src="'.$logo.'" class="custom-bb-logo"/>';
	} else { 
		return '<img alt="Smartest Themes" src="'. get_template_directory_uri().'/business-framework/images/st_logo_admin.png" />';
	}
}
add_filter('smartestthemes_backend_branding', 'st_custom_options_page_logo');

// Replace WP admin footer with custom text
function st_remove_footer_admin () {
	global $smartestthemes_options;
	$admin_footer = empty($smartestthemes_options['st_admin_footer']) ? '' : $smartestthemes_options['st_admin_footer'];
	$remove_it = empty($smartestthemes_options['st_remove_adminfooter']) ? '' : $smartestthemes_options['st_remove_adminfooter'];

	if ( $admin_footer &&  ( 'true' != $remove_it ) ) {
		echo $admin_footer;
	} elseif ( 'true' == $remove_it ) {
		echo '';
	} else {
		_e( 'Thank you for creating with <a href="http://wordpress.org/">WordPress</a>.', 'crucible');
	}
}
add_filter('admin_footer_text', 'st_remove_footer_admin'); 

function smartestthemes_admin_bar() {
    global $wp_admin_bar, $smartestthemes_options;
	if ( isset($smartestthemes_options['st_remove_wplinks']) ) {
		if ( $smartestthemes_options['st_remove_wplinks'] == 'true' ) {
			$wp_admin_bar->remove_menu('wp-logo');
		}
	}
}
add_action( 'wp_before_admin_bar_render', 'smartestthemes_admin_bar' );

/**
 * Social Share Buttons
 */
function smartestthemes_share() { ?>
    <div id="smartshare"><a target="_blank" href="https://plus.google.com/share?url=<?php echo urlencode(get_permalink()); ?>" class="simple-share ss-gplus" title="<?php _e( 'Share on G+', 'crucible' ); ?>"><?php _e( 'G+ Share', 'crucible' ); ?></a>
 
<a target="_blank" href="https://twitter.com/share?text=<?php the_title_attribute(); ?>" class="simple-share ss-twitter" title="<?php _e( 'Tweet', 'crucible' ); ?>"><?php _e( 'Tweet', 'crucible' ); ?></a>
 
<a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" class="simple-share ss-facebook" title="<?php _e( 'Share on Facebook', 'crucible' ); ?>"><?php _e( 'Share', 'crucible' ); ?></a>
 
<a href="http://www.pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&media=<?php if(has_post_thumbnail()) echo wp_get_attachment_url(get_post_thumbnail_id()); ?>&description=<?php echo the_title_attribute('echo=0') . ' - ' . get_permalink(); ?>" class="simple-share ss-pinterest" target="_blank"><?php _e( 'Pin it', 'crucible' ); ?></a>
     </div>
<?php
 
}

if ( ! function_exists( 'smartestthemes_content_nav' ) ):
/** 
 * Display navigation to next/previous pages when applicable
 */
function smartestthemes_content_nav( $nav_id ) {
	global $wp_query, $post;

	// Don't print empty markup on single pages if there's nowhere to navigate.
	if ( is_single() ) {
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;

		// don't print on bbPress pages
		if ( class_exists('bbPress') ) {
			if ( is_bbpress() ) return;
		}
	}

	// Don't print empty markup in archives if there's only one page.
	if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) )
		return;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'crucible' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'crucible' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'crucible' ) . '</span>' ); ?>
	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages

if ( is_post_type_archive('smartest_staff') ) {
	$anchor = $anchorN = __('More Staff', 'crucible');
} elseif ( is_post_type_archive('smartest_services') ) { 
	$anchor = $anchorN = __('More Services', 'crucible');
} elseif ( is_post_type_archive('smartest_news') ) { 
	$anchor = __('Older News', 'crucible');
	$anchorN = __('Newer News', 'crucible');
} else {
	$anchor = __('Older posts', 'crucible');
	$anchorN = __('Newer posts', 'crucible');
}
		if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php
 next_posts_link( 
		sprintf(
					__( '<span class="meta-nav">&larr;</span> %s', 'crucible' ), $anchor
			)
 ); ?>
</div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( sprintf(__( '%s <span class="meta-nav">&rarr;</span>', 'crucible' ), $anchorN)); ?></div>
		<?php endif;
	endif; ?>
	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // smartestthemes_content_nav
/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function smartestthemes_wp_title( $title, $sep ) {
	global $paged, $page, $smartestthemes_options;

	if ( is_feed() )
		return $title;
	
	$bn = empty($smartestthemes_options['st_business_name']) ? get_bloginfo('name') : stripslashes(esc_attr($smartestthemes_options['st_business_name']));
	
	// seo Homepage title
	$ti = empty($smartestthemes_options['st_home_meta_title']) ? $bn : stripslashes(esc_attr($smartestthemes_options['st_home_meta_title']));
			
	if ( is_front_page() ) {
		$title = $ti;
	} else {
		$title .= $bn;
	}
	if ( $paged >= 2 || $page >= 2 )
		$title = sprintf( __( 'Page %s', 'crucible' ), max( $paged, $page ) ) . " $title";
	return $title;
}
add_filter( 'wp_title', 'smartestthemes_wp_title', 10, 2 );

/**
* Add meta tags to head
*/

function smartestthemes_head_meta() {
	global $smartestthemes_options;
	if (isset($smartestthemes_options['st_disable_seo']) ) {
		if( $smartestthemes_options['st_disable_seo'] == 'true' ) {
			return;
		}
	}
	global $paged, $page;
	$des = '';
	if ( $paged >= 2 || $page >= 2 )
		$des .= sprintf( __('Page %s - ', 'crucible'), max( $paged, $page ) );

	if ( is_category() )
		$des .= strip_tags(category_description());

	if ( is_tag() )
		$des .= strip_tags(tag_description());

	if (is_front_page()) {
		$des .= stripslashes( esc_attr( $smartestthemes_options['st_home_meta_desc'] ) );
		if(empty($des)) $des .= get_bloginfo('description');
		$keys = stripslashes( esc_attr( $smartestthemes_options['st_home_meta_key'] ) );
	}
	
	// if single get the excerpt
	if ( is_single() && $post_id = get_queried_object_id() ) {
		if ( get_post_field( 'post_excerpt', $post_id ) ) {
			$description = get_post_field( 'post_excerpt', $post_id );
		} else {
            $description = get_post_field( 'post_content', $post_id );
		}
		$description =   esc_attr( trim( wp_strip_all_tags( $description, true ) ) );
		$description =   str_replace('"', '', $description);

		$des .= substr( $description, 0, 150 );
	}
	if( !empty($des) ) {
	
		?><meta name="description" content="<?php echo $des;?>" /><?php
	}
	if( !empty($keys) ) {
		?><meta name="keywords" content="<?php echo $keys;?>" /><?php 
	}
	// Tell searchbots to not index pages 2+ of paged archives. Improves ranking.
	if ( $paged >= 2 ) {
		echo '<meta name="robots" content="noindex, follow, noarchive" />';
	} 
}
add_action('wp_head', 'smartestthemes_head_meta');
function smartest_custom_style() {
	get_template_part( 'inc/custom', 'style' );
}
add_action('wp_head', 'smartest_custom_style', 9999);
/**
 * Sort staff archive by staff order number key
 *
 * @uses is_admin()
 * @uses is_post_type_archive()
 * @uses is_main_query()
 */
function smartestthemes_sort_staff($query) {
	if( !is_admin() && is_post_type_archive('smartest_staff') && $query->is_main_query() && isset( $query->query_vars['meta_key'] ) ) {
	$query->query_vars['orderby'] = 'meta_value_num';
	$query->query_vars['meta_key'] = '_stmb_staff_order_number';
	$query->query_vars['order'] = 'ASC';
	}
	return $query;
}
add_filter( 'parse_query', 'smartestthemes_sort_staff' );

/**
 * Sort services archive by service order number key
 *
 * @uses is_admin()
 * @uses is_post_type_archive()
 * @uses is_main_query()
 */
function smartestthemes_sort_services($query) {
	if( !is_admin()
	&&	(
		( is_post_type_archive('smartest_services') || is_tax( 'smartest_service_category' ) )
		&& $query->is_main_query()
		)
	&& isset( $query->query_vars['meta_key'] ) ) {
		$query->query_vars['orderby'] = 'meta_value_num';
		$query->query_vars['meta_key'] = '_stmb_service_order_number';
		$query->query_vars['order'] = 'ASC';
	}
	return $query;
}
add_filter( 'parse_query', 'smartestthemes_sort_services' );

/**
 * Check if the uploaded file is an image. If it is, then it processes it using the retina_support_create_images()
 * @uses smartestthemes_retina_create_images()
 */
function smartestthemes_retina_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    smartestthemes_retina_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'smartestthemes_retina_attachment_meta', 10, 2 );

/**
 * Create retina-ready images
 */
function smartestthemes_retina_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
 
            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );
 
            $info = $resized_file->get_size();
 
            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}

/**
 * Delete retina-ready images
 */
function smartestthemes_delete_retina_images( $attachment_id ) {
	$meta = wp_get_attachment_metadata( $attachment_id );
	// Avoid error when uploading plugins, which appears to run 'delete_attachement'
	if( ! is_array( $meta ) ) {
		return;
	}
	$upload_dir = wp_upload_dir();
	$path = pathinfo( $meta['file'] );
	foreach ( $meta as $key => $value ) {
		if ( 'sizes' === $key ) {
			foreach ( $value as $sizes => $size ) {
				$original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
				$retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
				if ( file_exists( $retina_filename ) )
					unlink( $retina_filename );
			}
		}
	}
}
add_filter( 'delete_attachment', 'smartestthemes_delete_retina_images' );
/**
* get the attachment id by filename
*/
function st_get_attachment_id_from_url( $attachment_url = '' ) {
	global $wpdb;
	$attachment_id = false;
	if ( '' == $attachment_url )
		return;
	$upload_dir_paths = wp_upload_dir();
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	}
	return $attachment_id;
}

/**
 * @return array of images for About page
 */

function smartestthemes_about_page_images() {
	$img_url = '';
	$full_featUrl = '';
	$width = '';
	$height = '';
	$topImg = '';
	if ( has_post_thumbnail() ) {
		$img = get_post_thumbnail_id(); 
		$full_featUrl = wp_get_attachment_image_src( $img, 'full');
		$width = $full_featUrl[1];
		$height = $full_featUrl[2];
	}
	
	// if there is an about page option picture, do it at top
	global $smartestthemes_options;
	$about_pic = empty($smartestthemes_options['st_about_picture']) ? '' : $smartestthemes_options['st_about_picture'];

	if ( $about_pic ) {
		$img_url = $about_pic;
		$topImg = $img_url;
		$top_width = '';
		$top_height = '';
		
	} elseif ( ! empty($full_featUrl) ) {
	
		// there's a featured image but no about page option picture
		
		$img_url = '';
		$topImg	= $full_featUrl[0];
		$top_width = $width;
		$top_height = $height;
		
	}
	$out = array();

	if( !empty($topImg) ) {
		$out[] = '<figure><a href="' . $topImg . '" title="' . the_title_attribute('echo=0') . '" ><img itemprop="primaryImageOfPage" src="' . $topImg . '" alt="' . the_title_attribute('echo=0') . '" width="' . $top_width . '" height="' . $top_height . '" /></a></figure>';
	}

	// if there's both an about page option picture and a featured image, do feat.image at bottom
	
	if ( !empty($img_url) && !empty($full_featUrl) ) {
			
		$out[] = '<figure><a href="' . $full_featUrl[0] . '" title="' . the_title_attribute('echo=0') . '" ><img src="' . $full_featUrl[0] . '" alt="' . the_title_attribute('echo=0') . '" width="' . $width . '" height="' . $height . '" /></a></figure>';
	}

	return $out;
}

/**
 * Show the Top Image for About page
 */

function smartestthemes_about_top_image() {
	$out = '';
	$imgs = smartestthemes_about_page_images();
	if ( isset($imgs[0]) && !empty($imgs[0]) ) {
		$out .= $imgs[0];
	}
	echo $out;
}

/**
 * Show the Bottom Image for About page
 */

function smartestthemes_about_bottom_image() {
	$out = '';
	$imgs = smartestthemes_about_page_images();
	if ( isset($imgs[1]) && !empty($imgs[1]) ) {
		$out .= $imgs[1];
	}
	echo $out;
}
/* Info message box shortcode */
function smartestthemes_info_shortcode( $atts, $content = null ) {
	return '<div class="st-info"><i class="fa fa-info-circle"></i>' . $content . '</div>';
}
add_shortcode( 'info', 'smartestthemes_info_shortcode' );
/* Success message box shortcode */
function smartestthemes_success_shortcode( $atts, $content = null ) {
	return '<div class="st-success"> <i class="fa fa-check"></i>' . $content . '</div>';
}
add_shortcode( 'success', 'smartestthemes_success_shortcode' );
/* Warning message box shortcode */
function smartestthemes_warning_shortcode( $atts, $content = null ) {
	return '<div class="st-warning"><i class="fa fa-warning"></i>' . $content . '</div>';
}
add_shortcode( 'warning', 'smartestthemes_warning_shortcode' );
/* Error message box shortcode */
function smartestthemes_error_shortcode( $atts, $content = null ) {
	return '<div class="st-error"><i class="fa fa-times-circle"></i>' . $content . '</div>';
}
add_shortcode( 'error', 'smartestthemes_error_shortcode' ); 

/**
* Add CPT Archives to Menus screen
* @todo remove these 2 functions when they get added to WP core
*/
function smartestthemes_archive_menu_meta_box() {
	add_meta_box( 'add-cpt', __( 'Custom Archives', 'crucible' ), 'smartestthemes_archive_menu_meta_box_render', 'nav-menus', 'side', 'default' );
}
add_action( 'admin_head-nav-menus.php', 'smartestthemes_archive_menu_meta_box' );
/* render custom post type archives meta box */
function smartestthemes_archive_menu_meta_box_render() {
	global $nav_menu_selected_id;
	/* get custom post types with archive support */
	$post_types = get_post_types( array( 'show_in_nav_menus' => true, 'has_archive' => true ), 'object' );
	 
	/* hydrate the necessary object properties for the walker */
	foreach ( $post_types as &$post_type ) {
		$post_type->classes = array();
		$post_type->type = 'custom';// use custom to avoid PHP notices
		$post_type->object_id = $post_type->name;
		$post_type->title = $post_type->labels->name;
		$post_type->object = 'cpt-archive';
		$post_type->menu_item_parent = 0;
		$post_type->url = get_post_type_archive_link($post_type->query_var);
		$post_type->target = 0;
		$post_type->attr_title = 0;
		$post_type->xfn = 0;
		$post_type->db_id = 0;
	}
	$walker = new Walker_Nav_Menu_Checklist( array() );
	?>
	<div id="archive" class="posttypediv">
	<div id="tabs-panel-cpt-archive" class="tabs-panel tabs-panel-active">
	<ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">
	<?php
	echo walk_nav_menu_tree( array_map('wp_setup_nav_menu_item', $post_types), 0, (object) array( 'walker' => $walker) );
	?>
	</ul>
	</div>
	<p class="button-controls">
	<span class="add-to-menu">
	<input type="submit"<?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary right submit-add-to-menu" value="<?php esc_attr_e('Add to Menu', 'crucible'); ?>" name="add-ctp-archive-menu-item" id="submit-cpt-archive" />
	<span class="spinner"></span>
	</span>
	</p>
	</div>
	<?php
}

/**
* Prints the HTML for the schema.org microdata depending on the page.
* @param string $position, the position which this call is made from. Accepts 'archive', 'single', 'header'.
*/

function schema_type($position) {

	$out = '';
	$schema = '';
	$blogpost_archive = '';
	
	if ( $position == 'archive' ) {
	
		if ( is_post_type_archive('smartest_news') ) {
			$schema = 'Article';
		} elseif ( is_post_type_archive('smartest_services') || is_tax('smartest_service_category') ) {
			$schema = 'Service';
			
		} elseif ( is_post_type_archive('smartest_staff') ) {
		
			$schema = 'Person';
		
		} elseif (is_home()) {
			$blogpost_archive = ' itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting"';
		}

	} elseif ( $position == 'single' ) {

		$post_type = get_post_type();
		if ( 'smartest_staff' == $post_type ) {
			$schema = 'Person';
		} elseif ( 'smartest_services' == $post_type ) {
			$schema = 'Service';
		} elseif ( 'post' == $post_type ) {
			$schema = 'BlogPosting';
		} else {
			$schema = 'Article';
		}
	
	} elseif ($position = 'header') {
	
		if ( is_front_page() ) {
		
			global $smartestthemes_options;
			$schema = empty( $smartestthemes_options['st_business_itemtype'] ) ? 'LocalBusiness' : $smartestthemes_options['st_business_itemtype'];
		
		} elseif ( is_home() ) {
			$schema = 'Blog';
		}
	}

	if ( $schema ) {
		echo ' itemscope itemtype="http://schema.org/'.$schema.'"';
	}

	if ($blogpost_archive) {
		echo $blogpost_archive;
	}

}
?>