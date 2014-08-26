<?php
/**
 * Crucible functions
 * @package Crucible
 */
/*-----------------------------------------------------------------------------------*/
/* Please refrain from editing this file. 
 * Place your custom functions in a child theme. See http://smartestthemes.com/docs/how-to-customize-without-losing-my-customizations-when-i-update-5/
 *
 */
include dirname( __FILE__ ) . '/inc/updater.php';
// Smartest Themes Business Framework
require_once TEMPLATEPATH . '/business-framework/admin-init.php'; // @test debug 
// Theme specific functionality
$incpath = TEMPLATEPATH . '/inc/';
require_once $incpath . 'options.php';
require_once $incpath . 'enqueue.php';
require_once $incpath . 'fontface.php';

/* Add default options and show Options Panel after activating  */
if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	add_action('admin_head','smartestthemes_option_setup');
	header( 'Location: '.admin_url() . "admin.php?page=crucible" );// @new
}
/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */ // @todo @new decide on this!!
}

if ( ! function_exists( 'crucible_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function crucible_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Crucible, use a find and replace
	 * to change 'crucible' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'crucible', get_template_directory() . '/languages' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'crucible-logo', 9999, 150 );// @new @todo prefix. @new @todo max-height.
	add_image_size( 'newswidget', 40, 65, true );// @new size
	add_image_size( 'featservices', 152, 96, true );// @new size
	add_image_size( 'staffwidget', 48, 72 );// @new size
	add_image_size( 'single', 400, 400 );// @new size

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Main Navigation', 'crucible' ),
	) );

	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'link', 'status', 'video' ) );
	add_theme_support( 'custom-background' );
	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );

	// Add theme support for Infinite Scroll. See: http://jetpack.me/support/infinite-scroll/
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
endif; // crucible_setup
add_action( 'after_setup_theme', 'crucible_setup' );

/**
 * Register 4 sidebar widget areas and 3 footer widget areas
 */
function crucible_widgets_init() {
	$options = get_option('smartestthemes_options');
	register_sidebar(array(
		'id' => 'regularsidebar',
		'name' => __('Regular Sidebar', 'crucible'),
		'description' => __('Default blog sidebar', 'crucible'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
 
	if( isset($options['st_show_services']) ) {
		if ( $options['st_show_services'] == 'true' ) { 
			register_sidebar(array(
				'id' => 'servicesidebar',
				'name' => __('Services Sidebar', 'crucible'),
				'description' => __('Sidebar used on single service pages', 'crucible'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));
		}
	}
	if( isset($options['st_show_staff']) ) {
	
		if ( $options['st_show_staff'] == 'true' ) {
			register_sidebar(array(
				'id' => 'staffsidebar',
				'name' => __('Staff Sidebar', 'crucible'),
				'description' => __('Sidebar used on single staff pages', 'crucible'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));
		}
	}
	if( isset($options['st_show_news']) ) {
		if ( $options['st_show_news'] == 'true' ) {
			register_sidebar(array(
				'id' => 'announcementsidebar',
				'name' => __('Announcement Sidebar', 'crucible'),
				'description' => __('Sidebar used on single announcement pages', 'crucible'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			));
		}
	}
		
	register_sidebar(array(
        'id' => 'foot1',
        'name' => __('Home Footer 1', 'crucible'),
        'description' => __('Home page footer widget area 1.', 'crucible'),
		'before_widget' => '<aside id="%1$s" class="footer-widget widget %2$s">',
		'after_widget'  => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
	register_sidebar(array(
        'id' => 'foot2',
        'name' => __('Home Footer 2', 'crucible'),
        'description' => __('Home page footer widget area 2.', 'crucible'),
		'before_widget' => '<aside id="%1$s" class="footer-widget widget %2$s">',
		'after_widget'  => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
	register_sidebar(array(
        'id' => 'foot3',
        'name' => __('Home Footer 3', 'crucible'),
        'description' => __('Home page footer widget area 3.', 'crucible'),
		'before_widget' => '<aside id="%1$s" class="footer-widget widget %2$s">',
		'after_widget'  => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
   ));
}
add_action( 'widgets_init', 'crucible_widgets_init' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Nav Menu Fallback
 */
function crucible_nav_fallback() {
	global $smartestthemes_options;// @test this works
	$bname = isset($smartestthemes_options['st_business_name']) ? $smartestthemes_options['st_business_name'] : '';
	$sbn = esc_attr(stripslashes_deep($bname));
	$about_page = isset($smartestthemes_options['st_about_page']) ? $smartestthemes_options['st_about_page'] : '';
	$about_picture = isset($smartestthemes_options['st_about_picture']) ? $smartestthemes_options['st_about_picture'] : '';
	$stop_about = isset($smartestthemes_options['st_stop_about']) ? $smartestthemes_options['st_stop_about'] : '';
	$stop_contact = isset($smartestthemes_options['st_stop_contact']) ? $smartestthemes_options['st_stop_contact'] : '';
	$svcs = isset($smartestthemes_options['st_show_services']) ? $smartestthemes_options['st_show_services'] : '';
	$staff = isset($smartestthemes_options['st_show_staff']) ? $smartestthemes_options['st_show_staff'] : '';
	$news = isset($smartestthemes_options['st_show_news']) ? $smartestthemes_options['st_show_news'] : '';
	$reviews = isset($smartestthemes_options['st_add_reviews']) ? $smartestthemes_options['st_add_reviews'] : '';

	echo '<ul class="menu">'; ?>
	<li class="home"><a title="<?php echo $sbn; ?>" href="<?php echo site_url('/'); ?>"><?php _e('Home', 'crucible'); ?></a></li>
	<?php if(($about_page || $about_picture) && ( $stop_about != 'true' ) ) { ?>
		<li class="about"><a title="<?php _e('About', 'crucible'); echo ' ' . $sbn; ?>" href="<?php echo get_page_link(get_option('smartestthemes_about_page_id')); ?>">
		<?php _e('About', 'crucible'); ?></a></li>
	<?php } if($svcs == 'true') { 
	
				// @test apply_filters only once
				$service_label = apply_filters( 'smartestthemes_services_menu_label', __( 'Services', 'crucible' ) );
	?>
		<li class="services"><a title="<?php echo esc_attr( $service_label ); ?>" href="<?php echo get_post_type_archive_link( 'smartest_services' ); ?>">
		<?php echo $service_label; ?>
		</a>
		<?php // if service cat tax terms exist, do sub-menu
		$service_cats = get_terms('smartest_service_category');
		$count = count($service_cats);
		if ( $count > 0 ){
			$sub = '<ul class="sub-menu">';
			foreach ( $service_cats as $service_cat ) {
				$sub .= '<li><a title="' . esc_attr( $service_cat->name ) . '" href="'. get_term_link( $service_cat ) .'">' . $service_cat->name . '</a></li>';	
			}
			$sub .= '</ul>';
			echo $sub;
		} ?>
		</li>
	<?php } if($staff == 'true') { 
	
				// @test apply_filters only once
				$staff_label = apply_filters( 'smartestthemes_staff_menu_label', __( 'Staff', 'crucible' ) );
				?>
		<li class="staff"><a title="<?php echo esc_attr( $staff_label ); ?>" href="<?php echo get_post_type_archive_link( 'smartest_staff' ); ?>">
		<?php echo $staff_label; ?>
		</a></li>
	<?php } if($news == 'true') { 
	
				// @test apply_filters only once
				$news_label = apply_filters( 'smartestthemes_news_menu_label', __( 'News', 'crucible' ) );
				?>
		<li class="news"><a title="<?php echo esc_attr( $news_label ); ?>" href="<?php echo get_post_type_archive_link( 'smartest_news' ); ?>">
		<?php echo $news_label; ?>
		</a></li>
	<?php } if($stop_contact != 'true') {	// @test logic
	?><li class="contact"><a title="<?php _e('Contact', 'crucible'); echo ' ' . $sbn; ?>" href="<?php echo get_page_link(get_option('smartestthemes_contact_page_id')); ?>">
		<?php _e('Contact', 'crucible'); ?>
		</a></li>
	<?php }
	if ($reviews == 'true') {
		$smartest_reviewspage_uri = get_page_link(get_option('smartestthemes_reviews_page_id'));
		echo '<li class="reviews"><a title="' . __('Reviews', 'crucible') . '" href="'. $smartest_reviewspage_uri .'">'. __('Reviews', 'crucible'). '</a></li>';
	}
   	echo '</ul>';
}


// CHANGE EXCERPT LENGTH FOR custom post types

function crucible_excerpt_length($length) {
    global $post;
	if (in_array($post->post_type, array('smartest_staff', 'smartest_services')))
		return 12;
	else
	    return 23;
}
// @new @todo if needed add_filter('excerpt_length', 'crucible_excerpt_length');

/**
 * Control the number of posts per page in taxonomy or cat archives
 * make it 9 instead of 10 for grid style layouts
 */
function crucible_numberposts( $query ) {
    if ( $query->is_post_type_archive(array('smartest_services')) ) {
        set_query_var('posts_per_page', 9);
    }
}
// @new @todo if needed add_action( 'pre_get_posts', 'crucible_numberposts' );
add_filter('widget_text', 'do_shortcode');

/**
* Add custom texture CSS class to body element
*/
function crucible_texture_class( $classes ) {
	// only if there is no bg image do we check for texture
	if ( ! get_theme_mod( 'background_image' ) ) {
		global $smartestthemes_options;// @test this one works
		$bg_texture = isset($smartestthemes_options['bg_texture']) ? $smartestthemes_options['bg_texture'] : '';
		if ($bg_texture) {
			// add 'texture_' to the $classes array
			$classes[] = 'texture_' . $bg_texture;
		}
	}
	return $classes;
}
add_filter('body_class','crucible_texture_class');

/**
* Remove hentry from post_class
*/
function crucible_remove_hentry_class( $classes ) {
    $classes = array_diff( $classes, array( 'hentry' ) );
    return $classes;
}
add_filter( 'post_class', 'crucible_remove_hentry_class' );

/** @test remove this function
 * Log my own debug messages
 */
function isa_log( $message ) {
    if (WP_DEBUG === true) {
        if ( is_array( $message) || is_object( $message ) ) {
            error_log( print_r( $message, true ) );
        } else {
            error_log( $message );
        }
    }
}
	