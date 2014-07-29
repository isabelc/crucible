<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Crucible
 */

get_header();
/*
// @new decide. may need something like this

if (is_page(get_option('smartestthemes_contact_page_id'))){
	$postclass = 'grid_7 alpha';
} else {
	$postclass = 'grid_12';
} 

@new end
*/
 ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php 
				if (is_page(get_option('smartestthemes_contact_page_id'))) :
					get_template_part( 'content', 'contact' );
				elseif (is_page(get_option('smartestthemes_about_page_id'))) :
					get_template_part( 'content', 'about' );
				else : 
					get_template_part( 'content', 'page' );
							
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
