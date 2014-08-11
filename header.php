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
<?php
global $smartestthemes_options;
$schema = empty( $smartestthemes_options['st_business_itemtype'] ) ? 'LocalBusiness' : $smartestthemes_options['st_business_itemtype']; ?>
<body <?php body_class(); if ( is_front_page() ) { echo ' itemscope itemtype="http://schema.org/'.$schema.'"';} ?>>

<div id="page" class="site">
<?php // do_action( 'crucible_social_buttons' ); // @new @todo here or in footer, decide per theme
?>
	<header id="masthead" class="site-header" role="banner">
	
		<div class="site-branding">
		<?php do_action( 'crucible_logo' ); ?>	
		</div>
	
		<?php if ( isset($smartestthemes_options['st_phone_number']) ) {
			echo $smartestthemes_options['st_phone_number'];
		} ?>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<!-- test if menu toggle works on windows phone, too -->
			<button class="menu-toggle"><?php _e( 'Menu', 'crucible' ); ?></button>
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'crucible' ); ?></a>

			<?php wp_nav_menu( array( 'theme_location' => 'primary','fallback_cb' => 'crucible_nav_fallback', 'items_wrap' => '<ul class="%2$s">%3$s</ul>' ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
	<div id="content" class="site-content">