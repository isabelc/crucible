<?php
/**
 * Template Name: Page With Sidebar
 * Use this template for displaying pages with a sidebar.
 * @package Crucible
 */

get_header(); ?>

	<div id="primary" class="content-area">
	<?php 

	/*
	@new decide. may need this to fit sidebar
	// post_class( 'content-area two-thirds alpha' )
	// @todo if i need this, then I will have to add CSS for .two-thirds, which I do not have yet.
	
	@test if this page has proper HTML. 
	*/
?>
	<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
