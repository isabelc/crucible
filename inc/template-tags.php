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
		if ( is_post_type_archive( 'smartest_news' ) && ( get_option('stop_theme_icon') == 'false' ) ) {
			// show news icon
			echo '<div class="newsicon"><i class="fa fa-bullhorn fa-3x"></i></div>';
			return;
		} else {
			return;
		}
	}

	/* @todo just a note that if I need exact size image for staff archives or so, use this below:
	
			$feedthumb = vt_resize( get_post_thumbnail_id(), '', 250, 127, true);
			$src = $feedthumb['url'];
			$image_width = $feedthumb['width'];
	
	*/

	$out = '';
	$img = get_post_thumbnail_id(); 
	$full_image_url = wp_get_attachment_image_src( $img, 'full');

	if ( is_singular() ) :
		$out .= '<div class="post-thumbnail"><a href="' . $full_image_url[0] . '" title="' . the_title_attribute('echo=0') . '"><img src="' . $full_image_url[0] . '" alt="' . the_title_attribute('echo=0') . '"></a></div>';

	else : 

		$out .= '<div class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute('echo=0') . '">' . get_the_post_thumbnail( get_the_id(), 'thumbnail' ) . '</a></div>';

	endif;

	echo $out;
}

/**
 * Print the Staff social buttons
 */
if ( ! function_exists( 'crucible_staff_social_buttons' ) ) :

	function crucible_staff_social_buttons() {
	
		global $post;

		if ( 'smartest_staff' != get_post_type() ) {
			return;
		}

		$tw = get_post_meta($post->ID, '_smab_staff_twitter', true);
		$goo = get_post_meta($post->ID, '_smab_staff_gplus', true);
		$fa = get_post_meta($post->ID, '_smab_staff_facebook', true);
		$li = get_post_meta($post->ID, '_smab_staff_linkedin', true);
		$in = get_post_meta($post->ID, '_smab_staff_instagram', true);

		// only print if at least 1 is entered
		if( $tw || $goo || $fa || $li || $in ) {
			$output = '<div id="social-staff"><ul>';
		} else {
			// if no social, get out now 
			return;
		}
		if ($tw) {
			$output .= '<li><a class="social-staff-twitter" target="_blank" href="https://twitter.com/' . $tw . '" title="' . __('Twitter', 'smartestb' ) . '"><i class="fa fa-twitter-square fa-2x"></i></a></li>';
		} 
		if ($goo) {
			$output .= '<li><a class="social-staff-gplus" target="_blank" href="https://plus.google.com/' . $goo . '" rel="publisher" title="' . __('Google Plus', 'smartestb' ) . '"><i class="fa fa-google-plus-square fa-2x"></i></a></li>';
		} 
		if ($fa) {
			$output .= '<li><a class="social-staff-facebook" target="_blank" href="https://facebook.com/' . $fa . '" title="' . __('Facebook', 'smartestb' ) . '"><i class="fa fa-facebook-square fa-2x"></i></a></li>';
		} 
		if ($li) {
			$output .= '<li><a class="social-staff-linkedin" target="_blank" href="https://linkedin.com/' . $li . '" title="' . __('Linkedin', 'smartestb' ) . '"><i class="fa fa-linkedin-square fa-2x"></i></a></li>';
		}
		
		if ($in) {
			$output .= '<li><a class="social-staff-instagram" target="_blank" href="http://instagram.com/' . $in . '" title="' . __('Instagram', 'smartestb' ) . '"><i class="fa fa-instagram fa-2x"></i></a></li>';
		}

		$output .= '</ul></div>';
		echo $output;
	}		
		
endif;
		
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
	} elseif ( 'post' == get_post_type() ) {

		$out .= '<span class="posted-on">' . sprintf( __( 'Posted on <time class="entry-date" datetime="%1$s">%2$s</time><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%3$s" title="%4$s" rel="author">%5$s</a></span></span>', 'crucible' ),
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'crucible' ), get_the_author() ) ),
				esc_html( get_the_author() )
				) . '</span>';

	}
		
	$out .= '<br />';
	return $out;
}
endif;

/**
 * Display Contact info with microdata from Schema.org
 */
function crucible_contact_info() {

	$options = get_option('smartestthemes_options');

	$output = '<div itemscope itemtype="http://schema.org/'.$options['business_schema']. '"><p><strong itemprop="name">';
	$bn = stripslashes_deep(esc_attr($options['business_name']));
	if($bn) {
		$output .= $bn;
	} else {
		$output .= get_bloginfo('name');
	}
	$output .= '</strong></p><p class="main-address"><span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">' . $options['address_street'] . '</span>&nbsp; ' . $options['address_suite'] . '<br /><span itemprop="addressLocality"> ' . $options['address_city'] . '</span>';
	if ( $options['address_city'] && $options['address_state'] ) {
		$output .= ', ';
	}
	$output .= '<span itemprop="addressRegion">' . $options['address_state'] . '</span>&nbsp;<span temprop="postalCode">' . $options['address_zip'] . '</span>&nbsp; ' . $options['address_country'] . '</span>	&nbsp;<br />';
	if ( $options['phone_number'] ) {
		$output .= '<br /><span class="strong">' . __('Telephone:', 'crucible') . '</span>&nbsp; <span itemprop="telephone">'. $options['phone_number']. '</span> &nbsp;';
	}
	if ( $options['fax_numb'] ) {
	$output .= '<br /><span class="strong">' . __('FAX:', 'crucible') . '</span>&nbsp;  <span itemprop="faxNumber">' . $options['fax_numb'] . '</span>&nbsp;';
	} 
	if ( $options['show_contactemail'] == 'true' ) {
	$output .= '<br /><span class="strong">' . __('Email:', 'crucible') . '</span>&nbsp;<a href="mailto:' . get_bloginfo('admin_email') . '"><span itemprop="email">' . get_bloginfo('admin_email') . '</span></a>';
	}
	$output .= '</p></div>';
	echo $output;
}
add_action('crucible_contact_info', 'crucible_contact_info');

/**
 * Display the Google map
 */
function crucible_google_map() {
	$map = get_option('google_map');
	if ( ! $map ) {
		return;
	}
	echo '<figure class="google-map">' . $map . '</figure>';
}

/**
 * Display the logo
 */
function crucible_logo() {

	$options = get_option('smartestthemes_options');

	$bn = stripslashes(esc_attr($options['business_name']));
	if(!$bn) { $bn = get_bloginfo('name'); }
	//seo title
	$ti = stripslashes(esc_attr($options['home_meta_title']));
	if(empty($ti)) $ti = $bn;
	$output = '';
	/* @test replace whole logo section
	*
	*
	
	if ( $options['logo'] ) {
		// there is a logo
		if ( $options['increase_logo'] ) {
			// custom height is set, use full size image which is resized with CSS
			$src = $options['logo'];
		} else {
			// use the logo_thumb which is cut during upload and has its retina ready counterpart
			$src_id = st_get_attachment_id_from_url($options['logo']);
			$src_atts = wp_get_attachment_image_src($src_id, 'ps-logo');
			$src = $src_atts[0];
		}
		$output .= '<a href="' . home_url( '/' ) . '" title="' . $ti . '" id="logolink" rel="home">
		<img id="customlogo" src="' . $src . '" alt="' . $ti . '" title="' . $ti . '" />
		</a><br />';
		if ( $options['show_tagline'] == 'true' ) { // @test see if 'true' is the right thing to check for. what value is saved for checkboxes?
			$output .= '<h2 class="site-description">' . get_bloginfo('description') . '</h2>';
		}
		
		
		
		
	*/
	$test = get_theme_mod('smartestthemes_logo');
	
	if ( $test ) {
		$output .= '<h3>THIS IS A @TEST </h3><br />' . $test;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	} else {
		//no logo option, use text logo 
		$logo_text_part_1		= stripslashes_deep( $options['logo_text_part_1'] );
		$logo_text_part_orange	= stripslashes_deep( $options['logo_text_part_orange'] );
		$logo_text_part_3		= stripslashes_deep( $options['logo_text_part_3'] );
		$logo_text_part_small	= stripslashes_deep( $options['logo_text_part_small'] );
		// if all empty, use blogname
		if ( empty($logo_text_part_1) && empty($logo_text_part_orange) && empty($logo_text_part_3) && empty($logo_text_part_small) ) $logo_text_part_1 = get_bloginfo('name');
		if ( $logo_text_part_1 ) {
			$output .= '<h1 class="site-title"><a href="' . home_url( '/' ) . '" title="' . $ti . '" rel="home">' . $logo_text_part_1;
			if ( $logo_text_part_orange ) {
				$output .= '<strong>' . $logo_text_part_orange . '</strong>';
			}
			if ( $logo_text_part_3 ) {
				$output .= $logo_text_part_3;
			}
			$output .= '</a>';
			if ( $logo_text_part_small ) {
				$output .= '<span>' . $logo_text_part_small . '</span>';
			}
			$output .= '</h1><h2 class="site-description">' . bloginfo('description') . '</h2>';
			
		} // end if $logo_text_part_1
	} // end else no logo
	echo $output;
}
add_action('crucible_logo', 'crucible_logo');

/**
 * Display the social buttons for the business
 */
function crucible_social_buttons() {
	$options = get_option('smartestthemes_options');
	$tw = $options['business_twitter'];
	$goo = $options['business_gplus'];
	$fa = $options['business_facebook'];
	$yo = $options['business_youtube'];
	$li = $options['business_linkedin'];
	$in = $options['business_instagram'];
	$pi = $options['business_pinterest'];

	// don't do unless at least one is entered
	if( $tw || $goo || $fa || $yo || $li || $in || $pi ) {
		$output = '<div class="social">';
	} else {
		// if no social, get out now 
		return;
	}

	$output .= '<ul>';
	if ( $tw ) {
		$output .= '<li><a class="social-twitter" target="_blank" href="https://twitter.com/' . $tw . '" title="' . __( 'Twitter', 'crucible' ) . '"><i class="fa fa-2x fa-twitter-square"></i></a></li>';
	} if ( $goo ) {
		$output .= '<li><a class="social-google" target="_blank" href="https://plus.google.com/' . $goo . '" rel="publisher" title="' . __( 'Google Plus', 'crucible' ) . '"><i class="fa fa-2x fa-google-plus-square"></i></a></li>';
	} if ( $fa ) {
		$output .= '<li><a class="social-facebook" target="_blank" href="https://facebook.com/' . $fa . '" title="' . __( 'Facebook', 'crucible' ) . '"><i class="fa fa-2x fa-facebook-square"></i></a></li>';
	} if ( $yo ) {
		$output .= '<li><a class="social-youtube" target="_blank" href="https://www.youtube.com/user/' . $yo . '" title="' . __( 'Youtube', 'crucible' ) . '"><i class="fa fa-2x fa-youtube-square"></i></a></li>';
	} if ( $li ) {
		$output .= '<li><a class="social-linkedin" target="_blank" href="https://www.linkedin.com/' . $li . '" title="' . __( 'Linkedin', 'crucible' ) . '"><i class="fa fa-2x fa-linkedin-square"></i></a></li>';
	}  if ( $in ) {
		$output .= '<li><a class="social-instagram" target="_blank" href="http://instagram.com/' . $in . '" title="' . __( 'Instagram', 'crucible' ) . '"><i class="fa fa-2x fa-instagram"></i></a></li>';
	}  if ( $li ) {
		$output .= '<li><a class="social-pinterest" target="_blank" href="http://www.pinterest.com/' . $pi . '" title="' . __( 'Pinterest', 'crucible' ) . '"><i class="fa fa-2x fa-pinterest-square"></i></a></li>';
	}
	$output .= '</ul></div><!-- .social -->';

	// extra social links
	if ( $options['business_socialurl1'] ) {
		$output .= '<br /><a href="' . $options['business_socialurl1'] . '" target="_blank" rel="nofollow" title="' . __( 'Connect', 'crucible' ) . '">' . $options['business_sociallabel1'] . '</a>';
	} 
	if ( $options['business_socialurl2'] ) {
		$output .= '&nbsp;  <a href="' . $options['business_socialurl2'] . '" title="' . __('Connect', 'crucible' ) . '" target="_blank" rel="nofollow">' . $options['business_sociallabel2'] . '</a>';
	}
	echo $output;
}
add_action('crucible_social_buttons', 'crucible_social_buttons');

/**
 * Display the site footer
 */
function crucible_footer() {
	$options = get_option('smartestthemes_options');
	$output = '';
	if ($options['override_footer'] == 'false') { // no override, so do default				
		$output .= '<span>' . __('Copyright ', 'crucible') . '&copy; '. date_i18n('Y') . '</span> <a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '">';

		$bn = stripslashes_deep(esc_attr($options['business_name']));
		if($bn) {
			$output .= $bn;
		} else {
			$output .= get_bloginfo('name');
		}
		$output .= '</a>';
		if ( $options['footer_text']) {
			$output .= '<br />';// if default plus custom, need <br />
		}
	}
	if ($options['footer_text']) {
		$output .= stripslashes_deep($options['footer_text']);
	} 
	echo $output;
}
add_action( 'crucible_footer', 'crucible_footer' );

/**
 * Display the clock icon with the Our Hours heading
 */
function crucible_clock_hours() {
	$output = '';
	$option = get_option('hours');
	if ($option) {
		$output .= '<div class="clock-hours"><h3><i class="fa fa-clock-o"></i> ';
		$output .= apply_filters('smartestthemes_hours_heading', __('Our Hours', 'crucible')) . '</h3><div class="hours">' . wpautop($option) . '</div></div>';
	}
	echo $output;
}
add_action('crucible_clock_hours', 'crucible_clock_hours');
