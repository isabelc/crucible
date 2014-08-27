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
	global $smartestthemes_options;
	$stop = isset($smartestthemes_options['st_stop_theme_icon']) ? $smartestthemes_options['st_stop_theme_icon'] : '';

	if ( ! has_post_thumbnail() ) {
		if ( is_post_type_archive( 'smartest_news' ) && ( $stop == 'false' ) ) {
			// show news icon
			echo '<div class="newsicon"><i class="fa fa-bullhorn fa-3x"></i></div>';
			return;
		} else {
			return;
		}
	}

	/* @new just a note that if I need exact size image for staff archives or so, use this below:
	
			$feedthumb = vt_resize( get_post_thumbnail_id(), '', 250, 127, true);
			$src = $feedthumb['url'];
			$image_width = $feedthumb['width'];
	
	*/

	$out = '';
	$img = get_post_thumbnail_id(); 
	
	$img_data = wp_prepare_attachment_for_js( $img );

	if ( is_singular() ) :
		$out .= '<div class="post-thumbnail"><a href="' . $img_data['url'] . '" title="' . $img_data['title'] . '">' . get_the_post_thumbnail( get_the_id(), 'single', array('itemprop' => 'image') ) . '</a></div>';
		
	else : 

		$out .= '<div class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '" title="' . the_title_attribute('echo=0') . '">' . get_the_post_thumbnail( get_the_id(), 'thumbnail',  array('itemprop' => 'image') ) . '</a></div>';
		
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

		$tw = get_post_meta($post->ID, '_stmb_staff_twitter', true);
		$goo = get_post_meta($post->ID, '_stmb_staff_gplus', true);
		$fa = get_post_meta($post->ID, '_stmb_staff_facebook', true);
		$li = get_post_meta($post->ID, '_stmb_staff_linkedin', true);
		$in = get_post_meta($post->ID, '_stmb_staff_instagram', true);

		// only print if at least 1 is entered
		if( $tw || $goo || $fa || $li || $in ) {
			$output = '<div class="social-staff"><ul>';
		} else {
			// if no social, get out now 
			return;
		}
		if ($tw) {
			$output .= '<li><a class="social-staff-twitter" target="_blank" href="https://twitter.com/' . $tw . '" title="' . __('Twitter', 'crucible' ) . '"><i class="fa fa-twitter-square fa-2x"></i></a></li>';
		} 
		if ($goo) {
			$output .= '<li><a class="social-staff-gplus" target="_blank" href="https://plus.google.com/' . $goo . '" rel="author" title="' . __('Google Plus', 'crucible' ) . '"><i class="fa fa-google-plus-square fa-2x"></i></a></li>';
		} 
		if ($fa) {
			$output .= '<li><a class="social-staff-facebook" target="_blank" href="https://facebook.com/' . $fa . '" title="' . __('Facebook', 'crucible' ) . '"><i class="fa fa-facebook-square fa-2x"></i></a></li>';
		} 
		if ($li) {
			$output .= '<li><a class="social-staff-linkedin" target="_blank" href="https://linkedin.com/' . $li . '" title="' . __('Linkedin', 'crucible' ) . '"><i class="fa fa-linkedin-square fa-2x"></i></a></li>';
		}
		
		if ($in) {
			$output .= '<li><a class="social-staff-instagram" target="_blank" href="http://instagram.com/' . $in . '" title="' . __('Instagram', 'crucible' ) . '"><i class="fa fa-instagram fa-2x"></i></a></li>';
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
			
		$out .= '<span class="jobtitle">' . get_post_meta($post->ID, '_stmb_staff_job_title', true) . '</span><br />';

	} elseif ( 'smartest_news' == get_post_type() ) {

		$out .= '<span class="posted-on">' . sprintf( __( 'Posted on <meta itemprop="datePublished" content="%1$s"><time class="entry-date" datetime="%1$s" pubdate>%2$s</time>', 'crucible' ),
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() )
					) . '</span>';
	} elseif ( 'post' == get_post_type() ) {

		$out .= '<span class="posted-on">' . sprintf( __( 'Posted on <meta itemprop="datePublished" content="%1$s"><time class="entry-date" datetime="%1$s">%2$s</time><span class="byline"> by <span itemprop="author" itemscope itemtype="http://schema.org/Person" class="author"><a itemprop="url" class="url" href="%3$s" title="%4$s"><span class="name">%5$s</span></a></span></span>', 'crucible' ),
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'crucible' ), get_the_author() ) ),
				esc_html( get_the_author() )
				) . '</span>';

	}
		
	$out .= '<br />';
	echo $out;
}
endif;

/** 
* Return the postal address with microdata
*/
function crucible_postal_address() {
	global $smartestthemes_options;
	$street = empty($smartestthemes_options['st_address_street']) ? '' : $smartestthemes_options['st_address_street'];
	$city = empty($smartestthemes_options['st_address_city']) ? '' : $smartestthemes_options['st_address_city'];
	$state = empty($smartestthemes_options['st_address_state']) ? '' : $smartestthemes_options['st_address_state'];
	$zip = empty($smartestthemes_options['st_address_zip']) ? '' : $smartestthemes_options['st_address_zip'];
	$country = empty($smartestthemes_options['st_address_country']) ? '' : $smartestthemes_options['st_address_country'];
	$suite = empty($smartestthemes_options['st_address_suite']) ? '' : $smartestthemes_options['st_address_suite'];	

	$out = '';
	
	if ( $street || $city || $state || $zip || $country || $suite ) {
	
		$out .= '<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">' . $street . '</span>&nbsp; ' . $suite . '<br /><span itemprop="addressLocality"> ' . $city . '</span>';
		if ( $city && $state ) {
			$out .= ', ';
		}
		$out .= '<span itemprop="addressRegion">' . $state . '</span>&nbsp;<span itemprop="postalCode">' . $zip . '</span>&nbsp; ' . $country . '</span>	&nbsp;<br />';
	}
	return $out;
}

/**
 * Display Contact info with microdata from Schema.org
 */
function crucible_contact_info() {
	global $smartestthemes_options;
	
	$schema = empty($smartestthemes_options['st_business_itemtype']) ? 'LocalBusiness' : $smartestthemes_options['st_business_itemtype'];
	$bn = empty($smartestthemes_options['st_business_name']) ? get_bloginfo('name') : stripslashes_deep(esc_attr($smartestthemes_options['st_business_name']));
	$phone = empty($smartestthemes_options['st_phone_number']) ? '' : $smartestthemes_options['st_phone_number'];
	$fax = empty($smartestthemes_options['st_fax_numb']) ? '' : $smartestthemes_options['st_fax_numb'];
	$show_email = empty($smartestthemes_options['st_show_contactemail']) ? '' : $smartestthemes_options['st_show_contactemail'];
	
	$output = '<div itemscope itemtype="http://schema.org/'.$schema. '"><p><strong itemprop="name">' . $bn . '</strong></p><p class="main-address">';
	
	$output .= crucible_postal_address();
	
	if ( $phone ) {
		$output .= '<br /><span class="strong">' . __('Telephone:', 'crucible') . '</span>&nbsp; <span itemprop="telephone">'. $phone . '</span> &nbsp;';
	}
	if ( $fax ) {
	$output .= '<br /><span class="strong">' . __('FAX:', 'crucible') . '</span>&nbsp;  <span itemprop="faxNumber">' . $fax . '</span>&nbsp;';
	} 
	if ( $show_email == 'true' ) {
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
	$map = get_option('st_google_map');
	if ( ! $map ) {
		return;
	}
	echo '<figure class="google-map">' . $map . '</figure>';
}

/**
 * Display the logo
 */
function crucible_logo() {
	global $smartestthemes_options;
	$name = get_bloginfo('name');
	$description = get_bloginfo('description');
	$increase_logo = empty($smartestthemes_options['increase_logo']) ? '' : $smartestthemes_options['increase_logo'];
	
	$bn = empty($smartestthemes_options['st_business_name']) ? $name : stripslashes(esc_attr($smartestthemes_options['st_business_name']));
	
	// seo title
	$ti = empty($smartestthemes_options['st_home_meta_title']) ? $bn : stripslashes(esc_attr($smartestthemes_options['st_home_meta_title']));
	
	
	// get custom tagline font

	$tagline_font = empty($smartestthemes_options['tagline_font']) ? '' : $smartestthemes_options['tagline_font'];
	if ( $tagline_font ) {
		// extract font class before comma from font key
		$tagline_font_pre = strstr($tagline_font, ',', true);
		$tagline_font_class = $tagline_font_pre ? sanitize_title($tagline_font_pre) : sanitize_title($tagline_font);
	}
			
	
	$output = '';

	$custom_logo = empty($smartestthemes_options['logo_setting']) ? '' : $smartestthemes_options['logo_setting'];
	
	if ( $custom_logo ) {
		// there is a logo
		if ( $increase_logo ) {
			// custom height is set, use full size image which is resized with CSS
			$src = $custom_logo;
		} else {
			// use the logo_thumb which is cut during upload and has its retina ready counterpart
			$src_id = st_get_attachment_id_from_url($custom_logo);
			$src_atts = wp_get_attachment_image_src($src_id, 'crucible-logo');
			$src = $src_atts[0];
		}
		$output .= '<a href="' . home_url( '/' ) . '" title="' . $ti . '" id="logolink" rel="home">
		<img id="customlogo" src="' . $src . '" alt="' . $ti . '" title="' . $ti . '" />
		</a><br />';
		if ( empty($smartestthemes_options['hide_tagline']) ) {
		
			$output .= '<h2 class="site-description';

			if ( $tagline_font ) {
				$output .= ' font_' . $tagline_font_class;
			}
			$output .= '">' . $description . '</h2>';		
		
		}
	} else { 
		// no logo image, so use text logo 
		if ( $name ) {
		
			$output .= '<h1 class="site-title"><a';
			
			// get custom logo font
			$logo_font = empty($smartestthemes_options['logo_font']) ? '' : $smartestthemes_options['logo_font'];

			if ( $logo_font ) {
			
				// extract font class before comma from font key
				$logo_font_pre = strstr($logo_font, ',', true);
				$logo_font_class = $logo_font_pre ? sanitize_title($logo_font_pre) : sanitize_title($logo_font);
				
				$output .= ' class="font_' . $logo_font_class . '"';
			}
			
			$output .= ' href="' . home_url( '/' ) . '" title="' . $ti . '" rel="home">' . $name . '</a></h1>';
		}
		if ( empty($smartestthemes_options['hide_tagline']) ) {
		
			$output .= '<h2 class="site-description';

			if ( $tagline_font ) {
				$output .= ' font_' . $tagline_font_class;
			}
			
			$output .= '">' . $description . '</h2>';

		}
	} // end else no logo
	
	echo $output;
}
add_action('crucible_logo', 'crucible_logo');

/**
 * Display the social buttons for the business
 */
function crucible_social_buttons() {
	global $smartestthemes_options;
	$tw = empty( $smartestthemes_options['st_business_twitter'] ) ? '' : $smartestthemes_options['st_business_twitter'];
	$goo = empty( $smartestthemes_options['st_business_gplus'] ) ? '' : $smartestthemes_options['st_business_gplus'];
	$fa = empty( $smartestthemes_options['st_business_facebook'] ) ? '' : $smartestthemes_options['st_business_facebook'];
	$yo = empty( $smartestthemes_options['st_business_youtube'] ) ? '' : $smartestthemes_options['st_business_youtube'];
	$li = empty( $smartestthemes_options['st_business_linkedin'] ) ? '' : $smartestthemes_options['st_business_linkedin'];
	$in = empty( $smartestthemes_options['st_business_instagram'] ) ? '' : $smartestthemes_options['st_business_instagram'];
	$pi = empty( $smartestthemes_options['st_business_pinterest'] ) ? '' : $smartestthemes_options['st_business_pinterest'];
	$social_url_1 = empty($smartestthemes_options['st_business_socialurl1']) ? '' : $smartestthemes_options['st_business_socialurl1'];
	$social_url_2 = empty($smartestthemes_options['st_business_socialurl2']) ? '' : $smartestthemes_options['st_business_socialurl2'];
	$label1 = empty($smartestthemes_options['st_business_sociallabel1']) ? '' : $smartestthemes_options['st_business_sociallabel1'];
	$label2 = empty($smartestthemes_options['st_business_sociallabel2']) ? '' : $smartestthemes_options['st_business_sociallabel2'];
	
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
		$output .= '<li><a class="social-google" target="_blank" href="https://plus.google.com/' . $goo . '" rel="' . apply_filters( 'smartestthemes_google_authorship', 'publisher' ) . '" title="' . __( 'Google Plus', 'crucible' ) . '"><i class="fa fa-2x fa-google-plus-square"></i></a></li>';
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
	if ( $social_url_1 ) {
		$output .= '<br /><a href="' . $social_url_1 . '" target="_blank" rel="nofollow" title="' . __( 'Connect', 'crucible' ) . '">' . $label1 . '</a>';
	} 
	if ( $social_url_2 ) {
		$output .= '&nbsp;  <a href="' . $social_url_2 . '" title="' . __('Connect', 'crucible' ) . '" target="_blank" rel="nofollow">' . $label2 . '</a>';
	}
	echo $output;
}
add_action('crucible_social_buttons', 'crucible_social_buttons');

/**
 * Display the site footer
 */
function crucible_footer() {
	global $smartestthemes_options;
	$output = '';
	$footer_text = empty($smartestthemes_options['footer_text']) ? '' : $smartestthemes_options['footer_text'];
	$bn = empty($smartestthemes_options['st_business_name']) ? get_bloginfo('name') : stripslashes_deep(esc_attr($smartestthemes_options['st_business_name']));
	
	if ( empty($smartestthemes_options['override_footer']) ) { // no override, so do default
		$output .= '<span id="footer-copyright">' . __('Copyright ', 'crucible') . '&copy; '. date_i18n('Y') . '</span> <a id="footer-sitename" href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . '">';
		
		if ( is_front_page() ) {
			$output .= '<span itemprop="name">';
		}
		
		$output .=  $bn;

		if ( is_front_page() ) {
			$output .= '</span>';
		}
		$output .= '</a><br /><span id="custom-footer">';// need for live customizer
		
		
	} else {
		$output .= '<br /><span id="custom-footer">';// need for live customizer.
	}
	if ( $footer_text ) {
		$output .= stripslashes_deep( $footer_text );
	}
	$output .= '</span>';
	echo $output;
}
add_action( 'crucible_footer', 'crucible_footer' );
/**
 * Display the clock icon with the Our Hours heading
 */
function crucible_clock_hours() {
	global $smartestthemes_options;
	$hours = empty($smartestthemes_options['st_hours']) ? '' : $smartestthemes_options['st_hours'];
	$output = '';
	
	if ($hours) {
		$output .= '<div class="clock-hours"><h3><i class="fa fa-clock-o"></i> ';
		$output .= apply_filters('smartestthemes_hours_heading', __('Our Hours', 'crucible')) . '</h3><div class="hours">' . wpautop($hours) . '</div></div>';
	}
	echo $output;
}
add_action('crucible_clock_hours', 'crucible_clock_hours');