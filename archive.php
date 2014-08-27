<?php
/**
 * The template for displaying Archive pages.
 * @package Crucible
 */

get_header(); 
// no sidebar if this is a cpt archive
if ( is_post_type_archive(array('smartest_staff', 'smartest_news', 'smartest_services')) || is_tax('smartest_service_category') ) :
	$sidebar = false;
else :
	$sidebar = true;
endif; ?>
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'crucible' ), get_the_author() );

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'crucible' ), '<span>' . get_the_date() . '</span>' );
						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'crucible' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'crucible' ) ) . '</span>' );
						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'crucible' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'crucible' ) ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'crucible' );

						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'crucible');

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'crucible');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'crucible' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'crucible' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'crucible' );

						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'crucible' );

						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'crucible' );

						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'crucible' );
						elseif ( is_post_type_archive('smartest_staff') ) :
							echo apply_filters('smartestthemes_staff_heading', __('Meet The Staff', 'crucible'));
						elseif ( is_post_type_archive('smartest_services') ) :
							echo apply_filters('smartestthemes_services_heading', __('Services', 'crucible'));
						elseif ( is_post_type_archive('smartest_news') ) :
							echo apply_filters('smartestthemes_news_heading', __('Announcements', 'crucible'));
						elseif (is_tax('smartest_service_category')) :
							$queried_object = get_queried_object();
							echo $queried_object->name;
						else :
							_e( 'Archives', 'crucible' );
						endif;
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php crucible_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->
<?php if($sidebar) :
		get_sidebar();
endif;
get_footer(); ?>