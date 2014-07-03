<?php
/**
 * Crucible Theme Customizer
 *
 * @package Crucible
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function crucible_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	// @test all my settings below
	
	$wp_customize->add_section('site_logo', array(
        'title'    => __('Site Logo', 'crucible'),
        'priority' => 10,
    ));
	
//  =============================
    //  = Image Upload              =
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[logo]', array(
        'default'           => '',
        'capability'        => 'edit_theme_options',
        'type'           => 'option',
     ));
 
    $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'image_upload_logo', array(
        'label'    => __('Custom Logo Image', 'crucible'),
        'section'  => 'smartestthemes_site_logo',
        'settings' => 'smartestthemes_options[logo]',
		'priority'   => 1
    )));

	
    //  =============================
    //  = Checkbox      @test             =
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[show_tagline]', array(
        'capability' => 'edit_theme_options',
        'type'       => 'option',
    ));
 
    $wp_customize->add_control('display_header_text', array(
        'settings' => 'smartestthemes_options[show_tagline]',
        'label'    => __('Show Tagline Under Your Logo?', 'crucible'),
        'section'  => 'smartestthemes_site_logo',
        'type'     => 'checkbox',
		'priority'   => 25
    ));
	//  =============================
    //  = Text Input                =
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[increase_logo]', array(
        'default'        => '',
        'capability'     => 'edit_theme_options',
        'type'           => 'option',
 
    ));
	$wp_customize->add_control('smartestthemes_increase_logo_height', array(
        'label'      => __('Optional: Logo Height in px. Default is 150.', 'crucible'),
        'section'    => 'smartestthemes_site_logo',
        'settings'   => 'smartestthemes_options[increase_logo]',
		'priority'   => 35
    ));
}
add_action( 'customize_register', 'crucible_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function crucible_customize_preview_js() {
	wp_enqueue_script( 'crucible_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'crucible_customize_preview_js' );