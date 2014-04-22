<?php
/**
 * The template for displaying the font page.
 * @package Crucible
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php while ( have_posts() ) : the_post(); ?>

				<?php $attention = get_option('st_attention_grabber');
				if( !empty($attention) ) : ?>
					<div class="attention-grab"><?php echo stripslashes_deep( $attention ); ?></div>
				<?php endif; ?>

				<?php crucible_post_thumbnail(); ?>
				<?php the_content(); ?>
				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<div id="home-footer">
	<?php get_sidebar( 'footer' ); ?>
</div>
<?php get_footer(); ?>