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
require_once get_template_directory() . '/business-framework/admin-init.php';
// Theme specific functionality
$incpath = get_template_directory() . '/inc/';
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
	$content_width = 640; // @new
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

	add_editor_style();
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'crucible-logo', 9999, 150 );// @new prefix. @new max-height.
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

	global $smartestthemes_options;
	
	$sbn = empty($smartestthemes_options['st_business_name']) ? '' : esc_attr(stripslashes_deep($smartestthemes_options['st_business_name']));
	$about_page = empty($smartestthemes_options['st_about_page']) ? '' : $smartestthemes_options['st_about_page'];
	$about_picture = empty($smartestthemes_options['st_about_picture']) ? '' : $smartestthemes_options['st_about_picture'];
	$stop_about = empty($smartestthemes_options['st_stop_about']) ? '' : $smartestthemes_options['st_stop_about'];
	$stop_contact = empty($smartestthemes_options['st_stop_contact']) ? '' : $smartestthemes_options['st_stop_contact'];
	$svcs = empty($smartestthemes_options['st_show_services']) ? '' : $smartestthemes_options['st_show_services'];
	$staff = empty($smartestthemes_options['st_show_staff']) ? '' : $smartestthemes_options['st_show_staff'];
	$news = empty($smartestthemes_options['st_show_news']) ? '' : $smartestthemes_options['st_show_news'];
	$reviews = empty($smartestthemes_options['st_add_reviews']) ? '' : $smartestthemes_options['st_add_reviews'];

	echo '<ul class="menu">'; ?>
	<li class="home"><a title="<?php echo $sbn; ?>" href="<?php echo site_url('/'); ?>"><?php _e('Home', 'crucible'); ?></a></li>
	<?php if(($about_page || $about_picture) && ( $stop_about != 'true' ) ) { ?>
		<li class="about"><a title="<?php _e('About', 'crucible'); echo ' ' . $sbn; ?>" href="<?php echo get_page_link(get_option('smartestthemes_about_page_id')); ?>">
		<?php _e('About', 'crucible'); ?></a></li>
	<?php } if($svcs == 'true') { 
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
				$staff_label = apply_filters( 'smartestthemes_staff_menu_label', __( 'Staff', 'crucible' ) );
				?>
		<li class="staff"><a title="<?php echo esc_attr( $staff_label ); ?>" href="<?php echo get_post_type_archive_link( 'smartest_staff' ); ?>">
		<?php echo $staff_label; ?>
		</a></li>
	<?php } if($news == 'true') { 
				$news_label = apply_filters( 'smartestthemes_news_menu_label', __( 'News', 'crucible' ) );
				?>
		<li class="news"><a title="<?php echo esc_attr( $news_label ); ?>" href="<?php echo get_post_type_archive_link( 'smartest_news' ); ?>">
		<?php echo $news_label; ?>
		</a></li>
	<?php } if($stop_contact != 'true') {
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
// @new if needed add_filter('excerpt_length', 'crucible_excerpt_length');

/**
 * Control the number of posts per page in taxonomy or cat archives
 * make it 9 instead of 10 for grid style layouts
 */
function crucible_numberposts( $query ) {
    if ( $query->is_post_type_archive(array('smartest_services')) || $query->is_tax(array('smartest_service_category')) ) {
        set_query_var('posts_per_page', 9);
    }
}
// @new if needed add_action( 'pre_get_posts', 'crucible_numberposts' );
add_filter('widget_text', 'do_shortcode');

/**
* Add custom texture CSS class to body element
*/
function crucible_texture_class( $classes ) {
	// only if there is no bg image do we check for texture
	if ( ! get_theme_mod( 'background_image' ) ) {
		global $smartestthemes_options;
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

/**
* Custom walker for comments with schema.org microdata
*/
class Crucible_Comment_Walker extends Walker_Comment {
	var $tree_type = 'comment';
	var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
 
	// constructor – wrapper for the comments list
	function __construct() { ?>
		<section class="comments-list">

	<?php }

	// start_lvl – wrapper for child comments list
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 2; ?>
			
		<section class="child-comments comments-list">

	<?php }
	
	// end_lvl – closing wrapper for child comments list
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 2; ?>

		</section>

	<?php }

	// start_el – HTML for comment template
	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;
		$parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' ); 
	
		if ( 'article' == $args['style'] ) {
			$tag = 'article';
			$add_below = 'comment';
		} else {
			$tag = 'article';
			$add_below = 'comment';
		} ?>

		<article <?php comment_class(empty( $args['has_children'] ) ? '' :'parent') ?> id="comment-<?php comment_ID() ?>">
			<div class="comment-schema-wrap" itemprop="comment" itemscope itemtype="http://schema.org/UserComments">
			<div class="comment-column">
			<figure class="gravatar"><?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?></figure>
			<h2 class="comment-author" itemprop='creator' itemscope itemtype='http://schema.org/Person'>
					<a class="comment-author-link" href="<?php comment_author_url(); ?>"><span itemprop='name'><?php comment_author(); ?></span></a> <span class="says"><?php _e(' says:', 'crucible'); ?></span>
				</h2>
			
			
			
			</div>
			<div class="comment-col-2">
			<div class="comment-meta post-meta">
				<time class="comment-meta-item" datetime="<?php comment_date('c'); ?>" itemprop="commentTime">
				<?php comment_date('F jS, Y'); ?> at <a href="#comment-<?php comment_ID(); ?>"><?php comment_time(); ?></a></time>

				<?php edit_comment_link(); ?>
				
				<?php if ($comment->comment_approved == '0') : ?>
				<p class="comment-meta-item"><?php _e('Your comment is awaiting moderation.', 'crucible'); ?></p>
					<?php endif; ?>
					
			</div>
			
			<div class="comment-content post-content" itemprop="commenttext">
				<?php comment_text(); ?>
				<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
			</div>
			</div>
			

	<?php }

	// end_el – closing HTML for comment template
	function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>

		</article>

	<?php }

	// destructor – closing wrapper for the comments list
	function __destruct() { ?>

		</section>
		
	<?php }

} // end Crucible_Comment_Walker class
	
/**
* WooCommerce compatibility
*/	
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
	echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
}

function my_theme_wrapper_end() {
	echo '</main><!-- #main -->
	</div><!-- #primary -->';
}

/**
* Add custom page slugs to body class for Reviews, About, and Contact pages in order to be able to highlight these menu items when current on the fallback menu.
*/
function crucible_custom_page_class( $classes ) {
	global $post;
	if ( is_page(get_option('smartestthemes_about_page_id')) )
		$classes[] = 'about';
	if ( is_page(get_option('smartestthemes_contact_page_id')) )
		$classes[] = 'contact';
	if ( is_page(get_option('smartestthemes_reviews_page_id')) )
		$classes[] = 'reviews';	
	return $classes;
}
add_filter( 'body_class', 'crucible_custom_page_class' );