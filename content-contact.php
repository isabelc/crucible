<?php
/**
 * The template used for displaying Contact page content in page.php
 * @package Crucible
 */
 ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="wrapper">
	
		<div class="two-thirds alpha">
		
			<div class="entry-content">
			<?php smartestthemes_contact_form(); ?>
			</div><!-- .entry-content -->
		
		</div><!-- .two-thirds -->
	
		<div class="one-third omega">

			<?php
			do_action( 'crucible_clock_hours' );
			do_action('crucible_contact_info');
			crucible_google_map();
			?>
		
		</div><!-- .one-third -->
	
	</div><!-- .wrapper -->
	
</article><!-- #post-## -->