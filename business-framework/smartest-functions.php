<?php
/** 
 * Smartest Functions
 * @package    Smartest Business Framework
*/
function smartestb_login_logo() {
	$buslogo =  get_option('smartestb_logo');
	// if there is a logo, show it, else do text
	if ($buslogo) {
		$small_logo = vt_resize( '', $buslogo, 326, 67, false );
	    echo '<style type="text/css">.login h1 a { background: url('.$small_logo['url'].') 50% 50% no-repeat !important;width: 326px;height: 70px;}</style>';
	} else {
		$col = get_option('smartestb_logo_color'); if (empty($col)) {$col = '#000000';}
		echo'<style type="text/css">.login h1 a {background-position: center top;text-indent: 0px;text-align:center; background-image:none;text-decoration:none;font-family:'. get_option('smartestb_logo_font'). ';color:'.$col. ';padding-top: 3px;width: 326px;height: 70px;}.login h1 a:hover {color:'.get_option('smartestb_logo_hover_color') . ';}</style>';
	}
}
add_action('login_head', 'smartestb_login_logo');
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
 * @since Smartest Business Framework 2.0.1
 */
function smartestbusiness_insert_post($potype, $slug, $option, $page_title = '', $page_content = '', $post_parent = 0 ) {
	global $wpdb;
	$option_value = get_option( $option );
	if ( $option_value > 0 && get_post( $option_value ) )
		return;
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
 * Create pages: about, home, storing page id's in variables.
 * Uses smartestbusiness_insert_post()
 * 
 * Activate Smartest Reviews
 */
function smartestbusiness_after_setup() {
	$bn = stripslashes_deep(esc_attr(get_option('smartestb_business_name')));if(!$bn) {$bn = get_bloginfo('name'); }
	$atitle = sprintf(__('About %s','smartestb'), $bn);
	// if not disabled in options 
	if(get_option('smartestb_stop_about') == 'false')
		smartestbusiness_insert_post( 'page', esc_sql( _x('about', 'page_slug', 'smartestb') ), 'smartest_about_page_id', $atitle, '' );
	if(get_option('smartestb_stop_home') == 'false')
		smartestbusiness_insert_post( 'page', esc_sql( _x('home', 'page_slug', 'smartestb') ), 'smartest_home_page_id', __('Home', 'smartestb'), '' );
	// Activate Smartest Reviews
	if (!class_exists('SMARTESTReviewsBusiness') && (get_option('smartestb_add_reviews') == 'true'))
		include_once(get_template_directory() .'/business-framework/modules/smartest-reviews/smartest-reviews.php');

}
add_action('after_setup_theme','smartestbusiness_after_setup');

/**
 * if about page is disabled, delete it
 */
if(get_option('smartestb_stop_about') == 'true') {
	wp_delete_post(get_option('smartest_about_page_id'), true);
}
/**
 * if auto Home page is disabled, delete it
 */
if(get_option('smartestb_stop_home') == 'true') {
	wp_delete_post(get_option('smartest_home_page_id'), true);
}
update_post_meta(get_option('smartest_home_page_id'), '_wp_page_template', 'smar-home.php');
/**
 * set static front page, unless disabled
 */

if( get_option('smartestb_stop_static') == 'false') {
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', get_option('smartest_home_page_id') );
}

// Set the blog page, unless disabled
if( get_option('smartestb_stop_blog') == 'false') {
	$blog   = get_page_by_title(__('Blog', 'smartestb') );
	if($blog) {
		update_option( 'page_for_posts', $blog->ID );
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
add_action('init', 'create_smartest_business_cpts');
function create_smartest_business_cpts() {
	$staff = get_option('smartestb_show_staff');
	$news = get_option('smartestb_show_news');
	$services = get_option('smartestb_show_services');
			if( $staff == 'true'  ) { 
		    	$args = array(
		        	'label' => __('Staff','smartestb'),
		        	'singular_label' => __('Staff','smartestb'),
		        	'public' => true,
		        	'show_ui' => true,
		        	'capability_type' => 'post',
		        	'hierarchical' => false,
		        	'rewrite' => array(
							'slug' => __('staff', 'smartestb'),
							'with_front' => false,

					),
		        	'exclude_from_search' => false,
	        		'labels' => array(
						'name' => __( 'Staff','smartestb' ),
						'singular_name' => __( 'Staff','smartestb' ),
						'add_new' => __( 'Add New','smartestb' ),
						'add_new_item' => __( 'Add New Staff','smartestb' ),
						'all_items' => __( 'All Staff','smartestb' ),
						'edit' => __( 'Edit','smartestb' ),
						'edit_item' => __( 'Edit Staff','smartestb' ),
						'new_item' => __( 'New Staff','smartestb' ),
						'view' => __( 'View Staff','smartestb' ),
						'view_item' => __( 'View Staff','smartestb' ),
						'search_items' => __( 'Search Staff','smartestb' ),
						'not_found' => __( 'No staff found','smartestb' ),
						'not_found_in_trash' => __( 'No staff found in Trash','smartestb' ),
						'parent' => __( 'Parent Staff','smartestb' ),
					),
		        	'supports' => array('title','editor','thumbnail'),
				'has_archive' => true,
				'menu_icon' => 'dashicons-groups',

		        );

	    	register_post_type( 'smartest_staff' , $args );

			}// end if show staff enabled
			if($news == 'true') { 
		    	$args = array(
		        	'label' => __('Announcements','smartestb'),
		        	'singular_label' => __('Announcement','smartestb'),
		        	'public' => true,
		        	'show_ui' => true,
		        	'capability_type' => 'post',
		        	'hierarchical' => false,
		        	'rewrite' => array(
							'slug' => __('news','smartestb'),
							'with_front' => false,

					),
		        	'exclude_from_search' => false,
	        		'labels' => array(
						'name' => __( 'Announcements','smartestb' ),
						'singular_name' => __( 'Announcement','smartestb' ),
						'add_new' => __( 'Add New','smartestb' ),
						'add_new_item' => __( 'Add New Announcement','smartestb' ),
						'all_items' => __( 'All Announcements','smartestb' ),
						'edit' => __( 'Edit','smartestb' ),
						'edit_item' => __( 'Edit Announcement','smartestb' ),
						'new_item' => __( 'New Announcement','smartestb' ),
						'view' => __( 'View Announcement','smartestb' ),
						'view_item' => __( 'View Announcement','smartestb' ),
						'search_items' => __( 'Search Announcements','smartestb' ),
						'not_found' => __( 'No announcement found','smartestb' ),
						'not_found_in_trash' => __( 'No announcements found in Trash','smartestb' ),
						'parent' => __( 'Parent Announcement','smartestb' ),
					),
		        	'supports' => array('title','editor','thumbnail'),
				'has_archive' => true,
				'menu_icon' => 'dashicons-exerpt-view'
		        );

	    	register_post_type( 'smartest_news' , $args );

			}// end if show news enabled
			if($services == 'true') { 
		    	$args = array(
		        	'label' => __('Services','smartestb'),
		        	'singular_label' => __('Service','smartestb'),
		        	'public' => true,
		        	'show_ui' => true,
		        	'capability_type' => 'post',
		        	'hierarchical' => false,
		        	'rewrite' => array(
							'slug' => __('services','smartestb'),
							'with_front' => false,

					),
		        	'exclude_from_search' => false,
	        		'labels' => array(
						'name' => __( 'Services','smartestb' ),
						'singular_name' => __( 'Service','smartestb' ),
						'add_new' => __( 'Add New','smartestb' ),
						'all_items' => __( 'All Services','smartestb' ),
						'add_new_item' => __( 'Add New Service','smartestb' ),
						'edit' => __( 'Edit','smartestb' ),
						'edit_item' => __( 'Edit Service','smartestb' ),
						'new_item' => __( 'New Service','smartestb' ),
						'view' => __( 'View Services','smartestb' ),
						'view_item' => __( 'View Service','smartestb' ),
						'search_items' => __( 'Search Services','smartestb' ),
						'not_found' => __( 'No services found','smartestb' ),
						'not_found_in_trash' => __( 'No services found in Trash','smartestb' ),
						'parent' => __( 'Parent Service','smartestb' ),
					),
		        	'supports' => array('title','editor','thumbnail'),
				'has_archive' => true,
				'menu_icon' => 'dashicons-portfolio'
		        );
	    	register_post_type( 'smartest_services' , $args );
			}// end if show services enabled

			// if show homepage slideshow is enabled, do cpt
			if(get_option('smartestb_show_slider') == 'true') {

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
						'all_items' => __( 'All Slides','smartestb' ),
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
				'menu_icon' => get_template_directory_uri(). '/images/slider-menu-icon.png',
		        	'supports' => array('title', 'thumbnail')
				);

		    	register_post_type( 'smartest_slide' , $args );

			}// end if home slideshow enabled
}

/**
 * Registers custom taxonomy for services
 * @since 2.2.1
 * @return void
 */
function smartestb_set_taxonomies() {
	$category_labels = array(
		'name' => __( 'Categories', 'smartestb' ),
		'singular_name' =>__( 'Category', 'smartestb' ),
		'search_items' => __( 'Search Categories', 'smartestb' ),
		'all_items' => __( 'All Categories', 'smartestb' ),
		'parent_item' => __( 'Parent Category', 'smartestb' ),
		'parent_item_colon' => __( 'Parent Category:', 'smartestb' ),
		'edit_item' => __( 'Edit Category', 'smartestb' ),
		'update_item' => __( 'Update Category', 'smartestb' ),
		'add_new_item' => __( 'Add New Category', 'smartestb' ),
		'new_item_name' => __( 'New Category Name', 'smartestb' ),
		'menu_name' => __( 'Categories', 'smartestb' ),
	);
	
	$category_args = apply_filters( 'smartestb_service_category_args', array(
		'hierarchical'		=> true,
		'labels'			=> apply_filters('smartestb_service_category_labels', $category_labels),
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite'			=> array(
							'slug'		=> 'services/category',
							'with_front'	=> false,
							'hierarchical'	=> true ),
	)
	);
	register_taxonomy( 'smartest_service_category', array('smartest_services'), $category_args );
	register_taxonomy_for_object_type( 'smartest_service_category', 'smartest_services' );
}
add_action( 'init', 'smartestb_set_taxonomies', 0 );
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
	    require_once( ABSPATH . 'wp-admin/includes/file.php' );
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
	    require_once(ABSPATH . 'wp-admin/includes/file.php');
	    require_once(ABSPATH . 'wp-admin/includes/media.php');
	    require_once(ABSPATH . 'wp-admin/includes/image.php');
	
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
* adds CPT archives menu items to wp_nav_menu, also choose which menu to add to. priority matters. 
*/
function smartestb_cpts_menu_links($items, $args) {
		/**
		 * @new themes must use theme_loc = 'primary-menu'
		 */
		$newitems = $items;
		if(($args->theme_location == 'primary-menu') && ( get_option('smartestb_show_staff') == 'true' )) {
		        $newitems .= '<li class="staff"><a title="' . __( apply_filters( 'smartestb_staff_menu_label', 'Staff' ), 'smartestb' ) . '" href="'. get_post_type_archive_link( 'smartest_staff' ) .'">' . __( apply_filters( 'smartestb_staff_menu_label', 'Staff' ), 'smartestb' ) . '</a></li>';
	    }
		if(($args->theme_location == 'primary-menu') && ( get_option('smartestb_show_services') == 'true' )) {
			$newitems .= '<li class="services"><a title="' . __( apply_filters( 'smartestb_services_menu_label', 'Services' ), 'smartestb' ) . '" href="'. get_post_type_archive_link( 'smartest_services' ) .'">' . __( apply_filters( 'smartestb_services_menu_label', 'Services' ), 'smartestb' ) . '</a>';

			// if service cat tax terms exist, do sub-menu
			$service_cats = get_terms('smartest_service_category');
			$count = count($service_cats);
			if ( $count > 0 ){
				$newitems .= '<ul class="sub-menu">';
				foreach ( $service_cats as $service_cat ) {
					$newitems .= '<li><a title="' . esc_attr( $service_cat->name ) . '" href="'. get_term_link( $service_cat ) .'">' . $service_cat->name . '</a></li>';	
				}
				$newitems .= '</ul>';
			}

			$newitems .= '</li>';
	    }

	    if( ($args->theme_location == 'primary-menu') && (get_option('smartestb_show_news') == 'true')) {
	        $newitems .= '<li class="news"><a title="' . __( apply_filters( 'smartestb_news_menu_label', 'News' ), 'smartestb' ) . '" href="'. get_post_type_archive_link( 'smartest_news' ) .'">' . __( apply_filters( 'smartestb_news_menu_label', 'News' ), 'smartestb' ) . '</a></li>';
		 }
	    return $newitems;
}
if(get_option('smartestb_stop_menuitems') == 'false') {
		add_filter('wp_nav_menu_items', 'smartestb_cpts_menu_links', 30, 2);
}

/**
 * Apply custom title labels to menu
 * @since Smartest Business Framework 2.1.0
 */
function custom_smartestb_services_menu_label() {
	// if custom title entered
	if ( get_option('smartestb_business_servicesmenulabel') != '' ) {
		$custom = stripslashes(get_option( 'smartestb_business_servicesmenulabel' ));
	} else { 
		$custom = __( 'Services', 'smartestb' );
	}
	return $custom;
}
function custom_smartestb_staff_menu_label() {
	// if custom title entered
	if (get_option('smartestb_business_staffmenulabel') != '') {
		$custom = stripslashes(get_option('smartestb_business_staffmenulabel'));
	} else { 
		$custom = __('Staff', 'smartestb');
	}
	return $custom;
}
function custom_smartestb_news_menu_label() {
	// if custom title entered
	if (get_option('smartestb_business_newsmenulabel') != '') {
		$custom = stripslashes(get_option('smartestb_business_newsmenulabel'));
	} else { 
		$custom = __('News', 'smartestb');
	}
	return $custom;
}

add_filter( 'smartestb_services_menu_label', 'custom_smartestb_services_menu_label' );
add_filter( 'smartestb_staff_menu_label', 'custom_smartestb_staff_menu_label' );
add_filter( 'smartestb_news_menu_label', 'custom_smartestb_news_menu_label' );

/**
 * Custom metaboxes and fields
 * for staff cpt: occupational title & social links
 */
add_filter( 'cmb_meta_boxes', 'smartestb_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function smartestb_metaboxes( array $meta_boxes ) {

	$prefix = '_smab_';

	$meta_boxes[] = array(
		'id'         => 'staff_details',
		'title'      => __('Details', 'smartestb'),
		'pages'      => array( 'smartest_staff', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __('Job Title', 'smartestb'),
				'desc' => __('The staff member\'s job title. Optional', 'smartestb'),
				'id'   => $prefix . 'staff_job_title',
				'type' => 'text_medium',
			),
			array(
				'name' => __( 'Sort Order Number', 'smartestb' ),
				'desc' => __( 'Give this person a number to order them on the list on the staff page and in the staff widget. Number 1 appears 1st on the list, while greater numbers appear lower. Numbers do not have to be consecutive; for example, you could number them like, 10, 20, 35, 45, etc. This would help to leave room in between to insert new staff members later without having to change everyone\'s current number.', 'smartestb' ),
				'id'   => $prefix . 'staff-order-number',
				'type' => 'text',
				'std' => 9999
			),
			array(
				'name' => __('Facebook Profile ID', 'smartestb'),
				'desc' => __('The staff member\'s Facebook profile ID. Optional', 'smartestb'),
				'id'   => $prefix . 'staff_facebook',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Twitter Username', 'smartestb'),
				'desc' => __('The staff member\'s Twitter username. Optional', 'smartestb'),
				'id'   => $prefix . 'staff_twitter',
				'type' => 'text_medium',
			),
			array(
				'name' => __('Google Plus Profile ID', 'smartestb'),
				'desc' => __('The staff member\'s Google Plus profile ID. Optional', 'smartestb'),
				'id'   => $prefix . 'staff_gplus',
				'type' => 'text_medium',
			),
			 array(
				'name' => __('Linkedin Profile', 'smartestb'),
				'desc' => __('The part of the profile address after "www.linkedin.com/". Optional', 'smartestb'),
				'id' => $prefix . 'staff_linkedin',
				'type' => 'text_medium',
			),
		)
	);

	// services 'featured' meta box
	$meta_boxes[] = array(
		'id'         => 'featured_svcs',
		'title'      => __('Featured Services', 'smartestb'),
		'pages'      => array( 'smartest_services', ),
		'context'    => 'side',
		'priority'   => 'default',//high, core, default, low
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Feature this?', 'smartestb'),
				'desc' => __('Check this box to feature this service in the list of featured services on the home page and in the Featured Services widget.', 'smartestb'),
				'id'   => $prefix . 'services_featured',
				'type' => 'checkbox',
			),
		)
	);

	if( get_option('smartestb_enable_service_sort') == 'true'  ) { 
	
		$meta_boxes[] = array(
			'id'         => 'services-sort-order',
			'title'      => __( 'Set a Sort-Order', 'smartestb' ),
			'pages'      => array( 'smartest_services' ),
			'context'    => 'normal',
			'priority'   => 'high',//high, core, default, low
			'show_names' => true,
			'fields'     => array(
				array(
					'name' => __( 'Sort Order Number', 'smartestb' ),
					'desc' => __( 'Give this service a number to order them on the list on the service page and in the services widget. Number 1 appears 1st on the list, while greater numbers appear lower. Numbers do not have to be consecutive; for example, you could number them like, 10, 20, 35, 45, etc. This would help to leave room in between to insert new staff members later without having to change all current numbers.', 'smartestb' ),
					'id'   => $prefix . 'service-order-number',
					'type' => 'text',
					'std' => 9999
				),
			)
		);
	}

	$meta_boxes[] = array(
		'id'         => 'featured_news',
		'title'      => __('Featured News', 'smartestb'),
		'pages'      => array( 'smartest_news', ),
		'context'    => 'side',
		'priority'   => 'default',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name' => __('Feature this?', 'smartestb'),
				'desc' => __('Check this box to feature this announcement in the Featured Announcements widget.', 'smartestb'),
				'id'   => $prefix . 'news_featured',
				'type' => 'checkbox',
			),
		)
	);
	$meta_boxes[] = array(
		'id'         => 'home_slideshow',
		'title'      => __('Slideshow', 'smartestb'),
		'pages'      => array( 'smartest_slide' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Add A Picture', 'smartestb'),
				'desc' => sprintf(__('Set a featured image for this slide by clicking "Set featured image", which is normally located on the right hand side of this page. %s', 'smartestb'),
get_option('smartestb_sshow_description')),
				'id'   => $prefix . 'slide_title',
				'type' => 'title',
			),
		)
	);

	return $meta_boxes;
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
		$title = __('Enter staff member\'s name here', 'smartestb');} return $title;
}
add_filter( 'enter_title_here', 'smartest_change_enter_title' );

/* Flush rewrite rules for custom post types but only once upon theme activation 
*/

add_action('after_switch_theme', 'smartest_flush_rewrite_rules', 10, 2);
function smartest_flush_rewrite_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
	update_option('smartestb_stop_home', 'false');
}
/**
 * call widgets
 */
add_action( 'widgets_init', 'smartestb_register_widgets' );

/**
 * register widgets
 */
function smartestb_register_widgets() {

	if( get_option('smartestb_show_news') == 'true'  ) { 
			register_widget('SmartestAnnouncements');
			register_widget('SmartestFeaturedAnnounce');
	}
	if( get_option('smartestb_show_services') == 'true'  ) { 
			register_widget('SmartestServices'); register_widget('SmartestFeaturedServices'); }
	if( get_option('smartestb_show_staff') == 'true'  ) { register_widget('SmartestStaff'); }

}

/**
 * insert custom scripts from theme options
 */
function smartestb_add_customscripts() {

	// get analytics script
	$gascript =  get_option('smartestb_script_analytics');
	// get other scripts
	$oscripts =  get_option('smartestb_scripts_head');
	
	if (isset($gascript) && $gascript != '') {
		echo stripslashes($gascript)."\r\n";
	}
	if (isset($oscripts) && $oscripts != '') {
		echo stripslashes($oscripts)."\r\n";
	}

}
add_action('wp_head','smartestb_add_customscripts', 12);

/* 
 * Filter archive page titles to allow custom heading
 * for staff, services, and news
 */

add_filter('smartestb_staff_heading', 'custom_staff_heading');
function custom_staff_heading() {
	if (get_option('smartestb_business_staffpagetitle') != '') {
		echo stripslashes(get_option('smartestb_business_staffpagetitle'));
	} else { 
		_e('Meet The Staff', 'smartestb');
	}
}

add_filter('smartestb_services_heading', 'custom_services_heading');
function custom_services_heading() {
	if (get_option('smartestb_business_servicespagetitle') != '') {
		echo stripslashes(get_option('smartestb_business_servicespagetitle'));
	} else { 
		_e('Services', 'smartestb');
	}
}

add_filter('smartestb_news_heading', 'custom_news_heading');
function custom_news_heading() {
	if (get_option('smartestb_business_newspagetitle') != '') {
		echo stripslashes(get_option('smartestb_business_newspagetitle'));
	} else { 
		_e('Announcements', 'smartestb');
	}
}

/**
 * Include the Smartest_MCE_Table_Buttons class.
 */
include dirname( __FILE__ ) . '/lib/mce-table/mce_table_buttons.php';

/**
 * Change wp admin bar
 * Add link to theme options, remove customize link
 */
function smartestb_admin_bar_render() {
	$themename =  get_option('smartestb_themename');
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('customize');
    $wp_admin_bar->add_menu( array(
        'parent' => 'appearance',
        'id' => 'smartestt-options',
        'title' => $themename. __(' Options', 'smartestb'),
        'href' => admin_url( 'admin.php?page=smartestbthemes')
    ) );
}
add_action( 'wp_before_admin_bar_render', 'smartestb_admin_bar_render' );

/** 
 * Add job title column to staff admin
 */
add_filter( 'manage_edit-smartest_staff_columns', 'smar_manage_edit_staff_columns' ) ;
function smar_manage_edit_staff_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Name', 'smartestb'),
		'jobtitle' => __('Job Title', 'smartestb'),
		'date' => __('Date', 'smartestb')
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
			$jobtitle = get_post_meta( $post_id, '_smab_staff_job_title', true );
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
		'title' => __('Title', 'smartestb'),
		'taxonomy-smartest_service_category' => __('Categories', 'smartestb'),
		'featureds' => __('Featured', 'smartestb'),
		'date' => __('Date', 'smartestb')
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
			$sf = get_post_meta( $post_id, '_smab_services_featured', true );
			if ( $sf )
				_e('Featured', 'smartestb');
			break;
		default :
			break;
	}
}

/** 
 * Add featured news column to news admin
 * @since Smartest Business Framework 2.0.1
 */

add_filter( 'manage_edit-smartest_news_columns', 'smar_manage_edit_news_columns' ) ;
function smar_manage_edit_news_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Title', 'smartestb'),
		'featuredn' => __('Featured', 'smartestb'),
		'date' => __('Date', 'smartestb')
	);
	return $columns;
}

/** 
 * Add data to featured news column in news admin
 * @since Smartest Business Framework 2.0.1
 */
add_action( 'manage_smartest_news_posts_custom_column', 'smar_manage_news_columns', 10, 2 );
function smar_manage_news_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'featuredn' :
			$sf = get_post_meta( $post_id, '_smab_news_featured', true );
			if ( $sf )
				_e('Featured', 'smartestb');
			break;
		default :
			break;
	}
}
/** 
 * Add thumbnail column to smartest_slide backend
 */
function smar_manage_edit_slide_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __('Title', 'smartestb'),
		'thumb' => __('Thumbnail', 'smartestb'),
		'date' => __('Date', 'smartestb')
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

if(get_option('smartestb_show_slider') == 'true') {
	add_filter( 'manage_edit-smartest_slide_columns', 'smar_manage_edit_slide_columns' ) ;
	add_action( 'manage_smartest_slide_posts_custom_column', 'smar_manage_slide_columns', 10, 2 );
}

/**
 * Options Page Branding
 * use custom logo on theme options page header
 */
function custom_options_page_logo() {
	// backwards compat. if option = 'false' string, update it to ''.
	$backlogo = get_option('smartestb_options_logo');
	if( 'false' == $backlogo ) {
		delete_option( 'smartestb_options_logo' );
	}

	if(get_option('smartestb_options_logo')) {
		return '<img alt="logo" src="'.get_option('smartestb_options_logo').'" class="custom-bb-logo"/>';
	} else { 
		return '<img alt="Smartest Themes" src="'. get_template_directory_uri().'/business-framework/images/st_logo_admin.png" />';
	}
}
add_filter('smartestb_options_branding', 'custom_options_page_logo');

// Replace WP footer with own custom text
function smb_remove_footer_admin () {

	if ( (get_option('smartestb_admin_footer') != '') &&  (get_option('smartestb_remove_adminfooter') == 'false')) {
		echo get_option('smartestb_admin_footer');
	} elseif ( get_option('smartestb_remove_adminfooter') == 'true' ) {
		echo '';
	} else {
		echo 'Thank you for creating with <a href="http://wordpress.org/">WordPress</a>.';
	}
}
add_filter('admin_footer_text', 'smb_remove_footer_admin'); 

function smartestb_admin_bar() {
    global $wp_admin_bar;

	if ( get_option('smartestb_remove_wplinks') == 'true' ) {
		$wp_admin_bar->remove_menu('wp-logo');
	}
}
add_action( 'wp_before_admin_bar_render', 'smartestb_admin_bar' );

function smartest_framework_enq() {
	wp_register_style( 'frame', get_template_directory_uri().'/business-framework/css/frame.css');
	wp_enqueue_style( 'frame' );
	wp_register_script('responsive', get_template_directory_uri().'/business-framework/js/responsive.js', array('jquery'));
	// not on reviews page
	if( !is_page( get_option('smartest_reviews_page_id') ) ) {	
		wp_enqueue_script('responsive');
	}
	wp_enqueue_script('retina', get_template_directory_uri().'/business-framework/js/retina-1.1.0.min.js', array(), false, true );

}
add_action('wp_enqueue_scripts', 'smartest_framework_enq');
/**
 * Social Share Buttons
 * @since Smartest Business Framework 2.0.1
 */
function smartest_share() { ?>
    <div id="smartshare">
       <div id="fb-root"></div><script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
    <div class="fb-like" data-send="false" data-layout="button_count"
       data-width="90" data-show-faces="false"></div>
    <div id="isa_gt">
       <a href="https://twitter.com/share" class="twitter-share-button"
data-dnt="true"><?php _e('Tweet', 'smartestb'); ?></a>
    <script>!function(d,s,id){
       var js,fjs=d.getElementsByTagName(s)[0];
       if(!d.getElementById(id)){js=d.createElement(s);
       js.id=id;js.src="//platform.twitter.com/widgets.js";
      fjs.parentNode.insertBefore(js,fjs);}}
      (document,"script","twitter-wjs");</script>
     </div>
    <script type="text/javascript">
      (function() {
        var po = document.createElement('script');
           po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0];
           s.parentNode.insertBefore(po, s);
      })();
    </script>
    <div id="isa_g"><div class="g-plusone" data-size="medium"
       data-annotation="inline" data-width="120"></div></div></div>
<?php
 
}

if ( ! function_exists( 'smartestblankie_content_nav' ) ):
/** 
 * Display navigation to next/previous pages when applicable
 *
 */
function smartestblankie_content_nav( $nav_id ) {
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
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'smartestb' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'smartestb' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'smartestb' ) . '</span>' ); ?>
	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages

if ( is_post_type_archive('smartest_staff') ) {
	$anchor = $anchorN = __('More Staff', 'smartestb');
} elseif ( is_post_type_archive('smartest_services') ) { 
	$anchor = $anchorN = __('More Services', 'smartestb');
} elseif ( is_post_type_archive('smartest_news') ) { 
	$anchor = __('Older News', 'smartestb');
	$anchorN = __('Newer News', 'smartestb');
} else {
	$anchor = __('Older posts', 'smartestb');
	$anchorN = __('Newer posts', 'smartestb');
}
		if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php
 next_posts_link( 
		sprintf(
					__( '<span class="meta-nav">&larr;</span> %s', 'smartestb' ), $anchor
			)
 ); ?>
</div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( sprintf(__( '%s <span class="meta-nav">&rarr;</span>', 'smartestb' ), $anchorN)); ?></div>
		<?php endif;
	endif; ?>
	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // smartestblankie_content_nav
/**
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Smartest Business Framework 2.1.3
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function smartestblankie_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;
	$bn = stripslashes(esc_attr(get_option('smartestb_business_name')));if(!$bn) { $bn = get_bloginfo('name'); }
	//seo title
	$ti = stripslashes(esc_attr(get_option('smartestb_home_meta_title')));
			if(empty($ti)) $ti = $bn;
	if ( is_front_page() ) {
		$title = $ti;
	} else {
		$title .= $bn;
	}
	if ( $paged >= 2 || $page >= 2 )
		$title = sprintf( __( 'Page %s', 'smartestb' ), max( $paged, $page ) ) . " $title";
	return $title;
}
add_filter( 'wp_title', 'smartestblankie_wp_title', 10, 2 );

/**
* Add meta tags to head
* @since Smartest Business Framework 2.1.3
*/

function smartestblankie_head_meta() {

	global $paged, $page;
	$des = '';
	if ( $paged >= 2 || $page >= 2 )
		$des .= sprintf( 'Page %s - ', max( $paged, $page ) );

	if ( is_category() )
		$des .= strip_tags(category_description());

	if ( is_tag() )
		$des .= strip_tags(tag_description());

	if (is_front_page()) {
		$des .= stripslashes(esc_attr(get_option('smartestb_home_meta_desc')));
		if(empty($des)) $des .= get_bloginfo('description');
		$keys = stripslashes(esc_attr(get_option('smartestb_home_meta_key')));
	}
	
	// if single get the excerpt
	if ( is_single() ) {
		if ( have_posts() ) : while(have_posts()) : the_post();
		$des .= strip_tags(get_the_excerpt());
		endwhile;
		endif;
	}

	if( !empty($des) ) {
	?>
		<meta name="description" content="<?php echo $des;?>" />
	<?php

	}

	if( !empty($keys) ) {
	?>
		<meta name="keywords" content="<?php echo $keys;?>" />
	<?php 
	}

	// Tell searchbots to not index duplicate pages or pages 2 and up of paged archives. Improves ranking.
	if ( $paged >= 2 ) {echo '<meta name="robots" content="noindex, follow, noarchive" />';} 
}
add_action('wp_head', 'smartestblankie_head_meta');
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
 * @since Smartest Business Framework 2.1.5
 */
function smartestb_sort_staff($query) {
	if( !is_admin() && is_post_type_archive('smartest_staff') && $query->is_main_query() && isset( $query->query_vars['meta_key'] ) ) {
	$query->query_vars['orderby'] = 'meta_value_num';
	$query->query_vars['meta_key'] = '_smab_staff-order-number';
	$query->query_vars['order'] = 'ASC';
	}
	return $query;
}
add_filter( 'parse_query', 'smartestb_sort_staff' );

/**
 * Sort services archive by service order number key
 *
 * @uses is_admin()
 * @uses is_post_type_archive()
 * @uses is_main_query()
 * @since Smartest Business Framework 2.2.6
 */
function smartestb_sort_services($query) {
	if( !is_admin() &&
	( 
	( is_post_type_archive('smartest_services') || is_tax( 'smartest_service_category' ) ) &&
	$query->is_main_query()
	)
	&& isset( $query->query_vars['meta_key'] ) ) {
	$query->query_vars['orderby'] = 'meta_value_num';
	$query->query_vars['meta_key'] = '_smab_service-order-number';
	$query->query_vars['order'] = 'ASC';
	}
	return $query;
}
if( get_option('smartestb_enable_service_sort') == 'true'  ) 
	add_filter( 'parse_query', 'smartestb_sort_services' );

/**
 * Check if the uploaded file is an image. If it is, then it processes it using the retina_support_create_images()
 * @uses smartestb_retina_create_images()
 * @since Smartest Business Framework 2.3.0
 */
function smartestb_retina_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    smartestb_retina_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'smartestb_retina_attachment_meta', 10, 2 );

/**
 * Create retina-ready images
 * @since Smartest Business Framework 2.3.0
 */
function smartestb_retina_create_images( $file, $width, $height, $crop = false ) {
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
 * @since Smartest Business Framework 2.3.0
 */
function smartestb_delete_retina_images( $attachment_id ) {
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
add_filter( 'delete_attachment', 'smartestb_delete_retina_images' );
/**
* get the attachment id by filename
 * @since Smartest Business Framework 2.3.0
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
?>