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
	
	// make changes to existing sections
	
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_section('colors')->title = __( 'Accent Colors', 'crucible' );
	$wp_customize->remove_section('static_front_page');
	
	
	$wp_customize->add_section('crucible_site_logo_section', array(
        'title'			=> __('Site Logo', 'crucible'),
		'description'	=> 'Upload a logo to replace the site title in the header',
        'priority'		=> 10,
    ));
	
	//  =============================
    //  = Image Upload 
    //  =============================
   
 	$wp_customize->add_setting('smartestthemes_options[logo_setting]', array(
        'default'	=> '',
        'type'		=> 'option',
		'transport'	=> 'postMessage'
     ));
     
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'smartestthemes_options[logo_setting]', array(
    'label'    => __( 'Logo', 'crucible' ),
    'section'  => 'crucible_site_logo_section',
    'settings' => 'smartestthemes_options[logo_setting]',
) ) );
	
	//  =============================
    //  = Text Input                =
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[increase_logo]', array(
        'default'	=> '',
        'type'		=> 'option',
		'transport'	=> 'postMessage'
     ));
	
	$wp_customize->add_control('crucible_increase_logo_height', array(
        'label'      => __('Optional: Increase logo height. Default is 150.', 'crucible'),
        'section'    => 'crucible_site_logo_section',
        'settings'   => 'smartestthemes_options[increase_logo]',
		'priority'   => 35
    ));
	
	
	// =============================
	// = Select Box =
	// =============================
	$wp_customize->add_setting('smartestthemes_options[logo_font]', array(
		'default'	=> 'Copperplate Bold, Copperplate Gothic Bold, serif',// @new default logo font
		'type'		=> 'option',
		'transport'	=> 'postMessage'
	));
	$wp_customize->add_control( 'logo_font_select', array(
		'settings' => 'smartestthemes_options[logo_font]',
		'label' => 'Site Title Font:',
		'section' => 'title_tagline',
		'type' => 'select',
		'choices' => array(
			'Arial,Helvetica,sans-serif' => 'Arial',
			'Cambria, Georgia, Times, Times New Roman, serif' => 'Cambria',
			'Copperplate Light, Copperplate Gothic Light, serif' => 'Copperplate Gothic Light',
			'Copperplate Bold, Copperplate Gothic Bold, serif' => 'Copperplate Gothic Bold',
			'Garamond, Hoefler Text, Times New Roman, Times, serif' => 'Garamond',
			'Georgia, Times, Times New Roman, serif' => 'Georgia',
			'GillSans, Calibri, Trebuchet MS, sans-serif' => 'GillSans, Calibri',
			'Impact, Haettenschweiler, Arial Narrow Bold, sans-serif' => 'Impact',
			'Monotype Corsiva, Arial, sans-serif' => 'Monotype Corsiva',
			'Lucida Console,Monaco,monospace' => 'Lucida Console, Monaco, monospace',
			'Lucida Sans Unicode,Lucida Grande,sans-serif' => 'Lucida Sans',
			'Palatino Linotype,Book Antiqua,Palatino,serif' => 'Palatino Linotype, Book Antiqua, serif',
			'Tahoma,Geneva,sans-serif' => 'Tahoma, Geneva',
			'Trebuchet MS,Arial,Helvetica,sans-serif' => 'Trebuchet MS',
			'Verdana,Geneva,sans-serif' => 'Verdana, Geneva'
		),
		'priority'   => 62
	));


	//  =============================
    //  = Color Input
    //  =============================
	$wp_customize->add_setting('smartestthemes_options[logo_color]', array(
        'default'	=> '#008000',// @new default
        'type'		=> 'option',
		'transport'	=> 'postMessage'
     ));

	$wp_customize->add_control( new WP_Customize_Color_Control( 
		$wp_customize, 
			'st_logo_color', 
			array(
				'label'      => __( 'Site Title Color', 'crucible' ),
				'section'    => 'title_tagline',
				'settings'   => 'smartestthemes_options[logo_color]',
				'priority'   => 63
			)
	));

	//  =============================
    //  = Text Input
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[logo_fontsize]', array(
        'default'	=> '',
        'type'		=> 'option',
		'transport'	=> 'postMessage'
     ));
	
	$wp_customize->add_control('crucible_logo_font_size', array(
        'label'      => __('Optional: Title Size. Default is 36px.', 'crucible'),// @new default size
        'section'    => 'title_tagline',
        'settings'   => 'smartestthemes_options[logo_fontsize]',
		'priority'   => 65
    ));
	
	
	//  =============================
    //  = Checkbox      checkbox outputs '1' if checked, or nothing if not checked.
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[hide_tagline]', array(
        'default'	=> '',
        'type'		=> 'option',
		'transport'	=> 'postMessage'
     ));
 
     $wp_customize->add_control('crucible_display_tagline', array(
        'settings' => 'smartestthemes_options[hide_tagline]',
        'label'    => __('Hide The Tagline From Header?', 'crucible'),
        'section'  => 'title_tagline',
        'type'     => 'checkbox',
		'priority'   => 15
    ));
	// =============================
	// = Select Box =
	// =============================
	$wp_customize->add_setting('smartestthemes_options[tagline_font]', array(
		'default'	=> 'Copperplate Bold, Copperplate Gothic Bold, serif',// @new default logo font
		'type'		=> 'option',
		'transport'	=> 'postMessage'
	));
	$wp_customize->add_control( 'tagline_font_select', array(
		'settings' => 'smartestthemes_options[tagline_font]',
		'label' => 'Tagline Font:',
		'section' => 'title_tagline',
		'type' => 'select',
		'choices' => array(
			'Arial,Helvetica,sans-serif' => 'Arial',
			'Cambria, Georgia, Times, Times New Roman, serif' => 'Cambria',
			'Copperplate Light, Copperplate Gothic Light, serif' => 'Copperplate Gothic Light',
			'Copperplate Bold, Copperplate Gothic Bold, serif' => 'Copperplate Gothic Bold',
			'Garamond, Hoefler Text, Times New Roman, Times, serif' => 'Garamond',
			'Georgia, Times, Times New Roman, serif' => 'Georgia',
			'GillSans, Calibri, Trebuchet MS, sans-serif' => 'GillSans, Calibri',
			'Impact, Haettenschweiler, Arial Narrow Bold, sans-serif' => 'Impact',
			'Monotype Corsiva, Arial, sans-serif' => 'Monotype Corsiva',
			'Lucida Console,Monaco,monospace' => 'Lucida Console, Monaco, monospace',
			'Lucida Sans Unicode,Lucida Grande,sans-serif' => 'Lucida Sans',
			'Palatino Linotype,Book Antiqua,Palatino,serif' => 'Palatino Linotype, Book Antiqua, serif',
			'Tahoma,Geneva,sans-serif' => 'Tahoma, Geneva',
			'Trebuchet MS,Arial,Helvetica,sans-serif' => 'Trebuchet MS',
			'Verdana,Geneva,sans-serif' => 'Verdana, Geneva'
		),
		'priority'   => 66
	));


	//  =============================
    //  = Color Input                =
    //  =============================
	$wp_customize->add_setting('smartestthemes_options[tagline_color]', array(
        'default'	=> '#404040',// @new default
        'type'		=> 'option',
		'transport'	=> 'postMessage'
     ));

	$wp_customize->add_control( new WP_Customize_Color_Control( 
		$wp_customize, 
			'st_tagline_color', 
			array(
				'label'      => __( 'Tagline Color', 'crucible' ),
				'section'    => 'title_tagline',
				'settings'   => 'smartestthemes_options[tagline_color]',
				'priority'   => 67
			)
	));
	//  =============================
    //  = Text Input                =
    //  =============================
    $wp_customize->add_setting('smartestthemes_options[tagline_size]', array(
        'default'	=> '',
        'type'		=> 'option',
		'transport'	=> 'postMessage'
     ));
	
	$wp_customize->add_control('crucible_tagline_font_size', array(
        'label'      => __('Optional: Tagline Font Size. Default is 24px.', 'crucible'),// @new default size
        'section'    => 'title_tagline',
        'settings'   => 'smartestthemes_options[tagline_size]',
		'priority'   => 68
    ));
	
	/* Link Color */
	
	$wp_customize->add_setting(
		'smartestthemes_options[link_color]',
		array(
			'default'	=> '#008000', // @new default
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		)
	);

	$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'st_link_color',
			array(
				'label'      => __( 'Link Color', 'crucible' ),
				'section'    => 'colors',
				'settings'   => 'smartestthemes_options[link_color]'
			)
	) );	

	
	/* Link Hover Color */
	$wp_customize->add_setting(
		'smartestthemes_options[link_hover_color]',
		array(
			'default'	=> '#ffc0cb', // @new default hover
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		)
	);
	$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'st_link_hover_color',
			array(
				'label'      => __( 'Link Hover Color', 'crucible' ),
				'section'    => 'colors',
				'settings'   => 'smartestthemes_options[link_hover_color]'
			)
	) );
	
	
	/* Button Color */
	
	$wp_customize->add_setting(
		'smartestthemes_options[button_color]',
		array(
			'default'	=> '#e6e6e6', // @new default
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		)
	);

	$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'st_button_color',
			array(
				'label'      => __( 'Button Color', 'crucible' ),
				'section'    => 'colors',
				'settings'   => 'smartestthemes_options[button_color]'
			)
	) );
	
	/* Button Hover Color */
	
	$wp_customize->add_setting(
		'smartestthemes_options[button_hover_color]',
		array(
			'default'	=> '#e6e6e6', // @new default
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		)
	);

	$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'st_button_hover_color',
			array(
				'label'      => __( 'Button Hover Color', 'crucible' ),
				'section'    => 'colors',
				'settings'   => 'smartestthemes_options[button_hover_color]'
			)
	) );
	
	/* Button Text Color */
	$wp_customize->add_setting(
		'smartestthemes_options[button_text_color]',
		array(
			'default'	=> '#191919', // @new default
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		)
	);

	$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'st_button_text_color',
			array(
				'label'      => __( 'Button Text Color', 'crucible' ),
				'section'    => 'colors',
				'settings'   => 'smartestthemes_options[button_text_color]'
			)
	) );	
	
	

	
}
add_action( 'customize_register', 'crucible_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function crucible_customize_preview_js() {
	wp_enqueue_script( 'crucible_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), false, true );
}
add_action( 'customize_preview_init', 'crucible_customize_preview_js' );