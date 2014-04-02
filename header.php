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
<body <?php body_class(); if ( is_front_page() ) { $schematype = get_option('smartetthemes_business_itemtype'); echo ' itemscope itemtype="http://schema.org/'.$schematype.'"';} ?>>
<!-- @todo search everywhere for 'smartetthemes_business_itemtype' or '_business_itemtype' to replace it wirh 'smartetthemes_bus_schema' -->
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
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div>

		<?php if ( get_option('smartestthemes_phone_number') ) {
			echo get_option('smartestthemes_phone_number');
		} ?>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<h1 class="menu-toggle"><?php _e( 'Menu', 'crucible' ); ?></h1>
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'crucible' ); ?></a>

			<?php wp_nav_menu( array( 'theme_location' => 'primary','fallback_cb' => 'smartestthemes_nav_fallback', 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) );// @todo search in frame and elsewhere to change 'primary-menu' to 'primary'

// @todo  search in frame and elsewhere to change 'smartestb_mainnav_fallback' to 'smartestthemes_nav_fallback'
?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
