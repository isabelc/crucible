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
	
	$wp_customize->add_section('crucible_site_logo_section', array(
        'title'    => __('Site Logo', 'crucible'),
		'description' => 'Upload a logo to replace the default site name and description in the header',
        'priority' => 10,
    ));
	
//  =============================
    //  = Image Upload 
    //  =============================
   
 	$wp_customize->add_setting('smartestthemes_options[logo_setting]', array(
        'default'           => '',
        'type'           => 'option',
     ));
     
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'smartestthemes_options[logo_setting]', array(
    'label'    => __( 'Logo', 'crucible' ),
    'section'  => 'crucible_site_logo_section',
    'settings' => 'smartestthemes_options[logo_setting]',
) ) );
	
    //  =============================
    //  = Checkbox      @todo checkbox output if '1' if on or nothing.
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[show_tagline]', array(
        'default'           => '',
        'type'           => 'option',
     ));
 
     $wp_customize->add_control('crucible_display_tagline', array(
        'settings' => 'smartestthemes_options[show_tagline]',
        'label'    => __('Show Tagline Under Your Logo?', 'crucible'),
        'section'  => 'crucible_site_logo_section',
        'type'     => 'checkbox',
		'priority'   => 25
    ));
	
	
	//  =============================
    //  = Text Input                =
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[increase_logo]', array(
        'default'           => '',
        'type'           => 'option',
     ));
	
	$wp_customize->add_control('crucible_increase_logo_height', array(
        'label'      => __('Optional: Increase logo height pixels. Default is 150.', 'crucible'),
        'section'    => 'crucible_site_logo_section',
        'settings'   => 'smartestthemes_options[increase_logo]',
		'priority'   => 35
    ));
}
add_action( 'customize_register', 'crucible_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function crucible_customize_preview_js() {
	wp_enqueue_script( 'crucible_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), false, true );
}
add_action( 'customize_preview_init', 'crucible_customize_preview_js' );