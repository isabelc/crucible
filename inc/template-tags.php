<?php
/**
 * Custom template tags for this theme.
 * @package Crucible
 */

if ( ! function_exists( 'crucible_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @return void
 */
function crucible_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'crucible' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'crucible' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'crucible' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'crucible_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @return void
 */
function crucible_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'crucible' ); ?></h1>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'crucible' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'crucible' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 */
function crucible_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so crucible_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so crucible_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in crucible_categorized_blog.
 */
function crucible_category_transient_flusher() {
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'crucible_category_transient_flusher' );
add_action( 'save_post',     'crucible_category_transient_flusher' );

/**
 * Show the featured image. Links to permalink on index
 * views, or a to full size image on single views.
 */

function crucible_post_thumbnail() {
	if ( post_password_required() ) {
		return;
	}

	if ( ! has_post_thumbnail() ) {
		if ( is_post_type_archive( 'smartest_news' ) && ( get_option('smartestthemes_stop_theme_icon') == 'false' ) ) {
			// show news icon
			$out .= '<div class="post-thumbnail newsicon"><a href="' . $full_image_url[0] . '" title="' . the_title_attribute('echo=0') . '"><img src="' . get_template_directory_uri(). '/images/newsicon.png'; . '" alt="' . the_title_attribute('echo=0') . '"></a></div>';
		} else {
			return;
		}
	} /* @test this logic, ends here */

/* @todo just a note that if i need exact size image for staff archives or so, use this:

		$feedthumb = vt_resize( get_post_thumbnail_id(), '', 250, 127, true);
		$src = $feedthumb['url'];
		$image_width = $feedthumb['width'];

*/

	$out = '';
	$img = get_post_thumbnail_id(); 
	$full_image_url = wp_get_attachment_image_src( $img, 'full');

	if ( is_singular() ) :
		$out .= '<div class="post-thumbnail"><a href="' . $full_image_url[0] . '" title="' . the_title_attribute('echo=0') . '"><img src="' . $full_image_url[0]; . '" alt="' . the_title_attribute('echo=0') . '"></a></div>';

	else : 

		$out .= '<div class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute('echo=0') . '"><img src="' . $full_image_url[0] . '" alt="' . the_title_attribute('echo=0') . '"></a></div>';

	endif;

	return $out;
}

/**
 * Prints HTML with meta information for the current post depending on post type.
 */
if ( ! function_exists( 'crucible_entry_meta' ) ) :
function crucible_entry_meta() {

	global $post;

	$out = '';

	if ( 'smartest_services' == get_post_type() ) {
		// if service cat is assigned, show it
		$service_cats = wp_get_post_terms( $post->ID, 'smartest_service_category' );
		$count = count($service_cats);
		if ( $count > 0 ){
			foreach ( $service_cats as $service_cat ) {
			$out .= '<a title="' . esc_attr( $service_cat->name ) . '" href="'. get_term_link( $service_cat ) .'" class="service-cats">' . $service_cat->name . '</a> ';
			}
		}
	} elseif ( 'smartest_staff' == get_post_type() ) {
			
		$out .= '<span class="jobtitle">' . get_post_meta($post->ID, '_smab_staff_job_title', true) . '</span><br />';

	} elseif ( 'smartest_news' == get_post_type() ) {

		$out .= '<span class="posted-on">' . sprintf( __( 'Posted on <time class="entry-date" datetime="%1$s" pubdate>%2$s</time>', 'crucible' ),
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() )
					) . '</span>';
	} else {

		$out .= '<span class="posted-on">' . sprintf( __( 'Posted on <time class="entry-date" datetime="%1$s">%2$s</time><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%3$s" title="%4$s" rel="author">%5$s</a></span></span>', 'smartestb' ),
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'smartestb' ), get_the_author() ) ),
				esc_html( get_the_author() )
				) . '</span>';

	}
		
	$out .= '<br />';
	return $out;
}
endif;
