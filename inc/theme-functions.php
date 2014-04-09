<?php
/* Add default options and show Options Panel after activate  */
if (is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
	add_action('admin_head','smartestthemes_option_setup');
	header( 'Location: '.admin_url() . "admin.php?page=crucible" );
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

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Main Navigation', 'crucible' ),
	) ); // @new always use location=primary since that's what i use as a condition in framework to insert cpt menu links

	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'link', 'status', 'video' ) );

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
	register_sidebar(array(
		'id' => 'regularsidebar',
		'name' => __('Regular Sidebar', 'crucible'),
		'description' => __('Default blog sidebar', 'crucible'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	));
 
	if ( ( get_option('smartestthemes_show_services') == 'true' ) ) { 
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

	if ( ( get_option('smartestthemes_show_staff') == 'true' ) ) {
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
	if ( ( get_option('smartestthemes_show_news') == 'true' ) ) {
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

	$sbn = esc_attr(stripslashes_deep(get_option('smartestthemes_business_name')));
	echo '<ul class="menu">'; ?>
	<li class="home"><a title="<?php echo $sbn; ?>" href="<?php echo site_url('/'); ?>"><?php _e('Home', 'crucible'); ?></a></li>
	<?php if((get_option('smartestthemes_about_page') || get_option('smartestthemes_about_picture')) && (get_option('smartestthemes_stop_about') == 'false')) { ?>
		<li class="about"><a title="<?php _e('About', 'crucible'); echo ' ' . $sbn; ?>" href="<?php echo get_page_link(get_option('smartestthemes_about_page_id')); ?>">
		<?php _e('About', 'crucible'); ?></a></li>
	<?php } if(get_option('smartestthemes_show_services') == 'true') { ?>
		<li class="services"><a title="<?php _e( apply_filters( 'smartestthemes_services_menu_label', 'Services' ), 'crucible' ); ?>" href="<?php echo get_post_type_archive_link( 'smartest_services' ); ?>">
		<?php _e( apply_filters( 'smartestthemes_services_menu_label', 'Services' ), 'crucible' ); ?>
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
	<?php } if(get_option('smartestthemes_show_staff') == 'true') { ?>
		<li class="staff"><a title="<?php _e( apply_filters( 'smartestthemes_staff_menu_label', 'Staff' ), 'crucible' ); ?>" href="<?php echo get_post_type_archive_link( 'smartest_staff' ); ?>">
		<?php _e( apply_filters( 'smartestthemes_staff_menu_label', 'Staff' ), 'crucible' ); ?>
		</a></li>
	<?php } if(get_option('smartestthemes_show_news') == 'true') { ?>
		<li class="news"><a title="<?php _e( apply_filters( 'smartestthemes_news_menu_label', 'News' ), 'crucible' ); ?>" href="<?php echo get_post_type_archive_link( 'smartest_news' ); ?>">
		<?php _e( apply_filters( 'smartestthemes_news_menu_label', 'News' ), 'crucible' ); ?>
		</a></li>
	<?php } if(get_option('smartestthemes_stop_contact') == 'false') { ?><li class="contact"><a title="<?php _e('Contact', 'crucible'); echo ' ' . $sbn; ?>" href="<?php echo get_page_link(get_option('smartestthemes_contact_page_id')); ?>">
		<?php _e('Contact', 'crucible'); ?>
		</a></li>
	<?php }
	if (get_option('smartestthemes_add_reviews') == 'true') {
		$smartest_reviewspage_uri = get_page_link(get_option('smartestthemes_reviews_page_id'));
		echo '<li class="reviews"><a title="' . __('Reviews', 'crucible') . '" href="'. $smartest_reviewspage_uri .'">'. __('Reviews', 'crucible'). '</a></li>';

	}
   	echo '</ul>';
}


// CHANGE EXCERPT LENGTH FOR custom post types

function smartestthemes_excerpt_length($length) {
    global $post;
	if (in_array($post->post_type, array('smartest_staff', 'smartest_services')))
		return 12;
	else
	    return 23;
}
// @new @todo if needed add_filter('excerpt_length', 'smartestthemes_excerpt_length');

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