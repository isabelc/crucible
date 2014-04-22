<?php
/**
 * The Header for our theme.
 * Displays all of the <head> section and everything up till <div id="content">
 * @package Crucible
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php wp_head(); ?>
</head>
<?php $options = get_option('smartestthemes_options'); ?>
<body <?php body_class(); if ( is_front_page() ) { echo ' itemscope itemtype="http://schema.org/'.$options['st_business_schema'].'"';} ?>>

<div id="page" class="hfeed site">

<!-- 
@todo @test the customizer's ability to create a logo with fonts and different colors,
 and to alternate between text logo and image logo.

If it works well, consider using that instead of my logo creator in panel.

do_action( 'crucible_logo' ); // hold

do_action( 'crucible_social_buttons' ); // @todo here or in footer, decide per theme

-->
	<header id="masthead" class="site-header" role="banner">
	
		<div class="site-branding">
		<?php // @test @hold do_action( 'crucible_logo' ); ?>	
		</div>
	
	
	<?php // @test debus
	
	$test = get_theme_mod('smartestthemes_logo');
	$test2 = get_option('st_smartestthemes_logo');
	
	echo '<h3>test 1, theme_mod:</h3> ' . $test . '<br /><br /><h3>Test 2, option: </h3>' . $test2; 
	?>
		<?php if ( $options['st_phone_number'] ) {
			echo $options['st_phone_number'];
		} ?>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<h1 class="menu-toggle"><?php _e( 'Menu', 'crucible' ); ?></h1>
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'crucible' ); ?></a>

			<?php wp_nav_menu( array( 'theme_location' => 'primary','fallback_cb' => 'crucible_nav_fallback', 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
	<div id="content" class="site-content">
