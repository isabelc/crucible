<?php
/**
 * Crucible functions and definitions
 *
 * @package Crucible
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */ // @todo decide on this!!
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

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'crucible' ),
	) );// @todo see about changing from 'primary-menu' to 'primary'. how will this affect QBW plugin?	 @new always use location=primary-menu since that's what i use as a condition in framework to insert cpt menu links

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );
}
endif; // crucible_setup
add_action( 'after_setup_theme', 'crucible_setup' );

/** @todo bring in all 7 sidebars
 * Register widgetized area and update sidebar with default widgets.
 */
function crucible_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'crucible' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'crucible_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function crucible_scripts() {
	wp_enqueue_style( 'crucible-style', get_stylesheet_uri() );

	wp_enqueue_script( 'crucible-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'crucible-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'crucible_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/**	 // @todo move this into framework functions 
 * Check how many images there are for About page
 */

function smartestthemes_about_page_images() {// @test maybe have to pass post id

	$img_url = '';
	$full_featUrl = '';
	$topImg = '';
	if ( has_post_thumbnail() ) {
		$img = get_post_thumbnail_id(); 
		$full_featUrl = wp_get_attachment_image_src( $img, 'full');
	}
	if ( get_option('smartestthemes_about_picture') ) {
		$img_url = get_option('smartestthemes_about_picture');
		$topImg = $img_url;
	} elseif ( isset($full_featUrl) && ! empty($full_featUrl) ) {
		$topImg = $full_featUrl[0];
	}

	$out = array();

	if( isset($topImg) && ! empty($topImg) ) {
		$out[] = '<figure><a href="' . $topImg . '" title="' . the_title_attribute('echo=0') . '" ><img src="' . $topImg . '" alt="' . the_title_attribute('echo=0') . '" /></a></figure>';
	}

	if ( isset($img_url) && $full_featUrl ) {
			
		$out[] = '<figure><a href="' . $full_featUrl[0] . '" title="' . the_title_attribute('echo=0') . '" ><img src="' . $full_featUrl[0] . '" alt="' . the_title_attribute('echo=0') . '" /></a></figure>';
	}

	return $out;
}

/**	 // @todo move this into framework functions 
 * Top Image for About page
 */

function crucible_about_top_image() {
	$out = '';
	$imgs = smartestthemes_about_page_images();
	if ( isset($imgs[0]) && !empty($imgs[0]) ) {
		$out .= $imgs[0];
	}
	return $out;
}

/**	 // @todo move this into framework functions 
 * Bottom Image for About page
 */

function crucible_about_bottom_image() {
	$out = '';
	$imgs = smartestthemes_about_page_images();
	if ( isset($imgs[1]) && !empty($imgs[1]) ) {
		$out .= $imgs[1];
	}
	return $out;
}