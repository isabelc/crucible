<?php
/**
 * The Sidebar containing the main widget areas.
 * Has 3 conditional widget areas: Services Sidebar, Staff Sidebar, Staff Sidebar
 * and 1 default widget area: General Default Sidebar
 * 
 * @package Crucible
 */
?>
<div id="secondary" class="widget-area" role="complementary">

<?php // if is single-service, get service sidebar

if ( 'smartest_services' == get_post_type() ) :
	
	if ( ! dynamic_sidebar( 'servicesidebar' ) ) {
		the_widget( 'SmartestServices', array('title' => __('All Services', 'crucible')) );
	}


// if is single-staff, get staff sidebar

elseif ( 'smartest_staff' == get_post_type() ) :

	// if staff sidebar not active
	if ( ! dynamic_sidebar( 'staffsidebar' ) ) {
		// get staff widget 
		the_widget( 'SmartestStaff', array('title' => __('All Staff', 'crucible')) );
	}

// if is single-announcement, get Announcement sidebar
	
elseif ( 'smartest_news' == get_post_type() ) :

	// if Announcement sidebar not active

	if ( ! dynamic_sidebar( 'announcementsidebar' ) ) {

		// get Recent Announcements widget 
		the_widget( 'SmartestAnnouncements', array( 'title' => __( 'Recent News', 'crucible' ),	'number' => 3 ) );
	}

else :
	if ( ! dynamic_sidebar( 'regularsidebar' ) ) { ?>
		<aside id="search" class="widget widget_search">
			<?php get_search_form(); ?>
		</aside>
		<aside id="archives" class="widget">
			<h3 class="widget-title">Archives</h3>
			<ul>
				<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
			</ul>
		</aside>

		<aside id="meta" class="widget">
			<h1 class="widget-title"><?php _e( 'Meta', 'crucible' ); ?></h1>
			<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
			</ul>
		</aside>
	<?php }
endif; ?>
</div><!-- #secondary -->