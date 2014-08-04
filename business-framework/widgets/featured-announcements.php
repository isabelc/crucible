<?php
/**
 * Adds Featured Announcements widget to show selected announcements
 *
 * @author 	Smartest Themes
 * @package 	Smartest Themes Business Framework
 * @extends 	WP_Widget
 */

class SmartestFeaturedAnnounce extends WP_Widget {

	/**
	 * Register widget
	 */
	public function __construct() {
		parent::__construct(
	 		'smartest_featured_announce',
			__('Smartest Featured Announcements', 'crucible'),
			array( 'description' => __( 'Display selected featured announcements.', 'crucible' ), )
		);
		add_action('wp_enqueue_scripts', array( $this, 'featnews_css' ) );
	}
	/* add css */
	function featnews_css() {
		wp_register_style('sfa', get_template_directory_uri().'/business-framework/widgets/sfa.css');
	} 

	/**
	 * Front-end display of widget.
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		extract( $args );
	
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;
		wp_enqueue_style('sfa');
		if ( ! empty( $title ) )
			echo '<h3 class="widget-title">'. $title . '</h3>';
		
		/** 
		* loop through announcements 
		*/
		$args = array(
			'post_type' => 'smartest_news',
			'meta_query' => array(
								array  (
									'key' => '_smab_news_featured',
									'value'=> 'on'
									)
							)			
			);
		$sbffa = new WP_Query( $args );
		if ( $sbffa->have_posts() ) {
			while ( $sbffa->have_posts() ) {
				$sbffa->the_post();
				echo '<div class="sfawrap">';
				if ( has_post_thumbnail() ) {
					$thumb = get_post_thumbnail_id();
					$smallimage = vt_resize( $thumb, '', 40, 65, true);
					echo '<figure class="sfafig"><a href="'.get_permalink().'" title="'.get_the_title().'">';
					?>
					<img class="thumb" src="<?php echo $smallimage['url']; ?>" width="<?php echo $smallimage['width']; ?>" height="<?php echo $smallimage['height']; ?>" />
					<?php echo '</a></figure>';
				} else {
					// if not stopped with option

					if(get_option('st_stop_theme_icon') != 'true') {

						echo '<a href="'.get_permalink().'" title="'.get_the_title().'" class="sfafig"><div class="newsicon"><i class="fa fa-bullhorn fa-3x"></i></div></a>';
					}

				}
					
		echo '<div class="sfacontent">';
			echo '<h4><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h4>';
				echo '<p>'. get_the_excerpt(). '</p>';
					echo '<a class="button" href="'.get_permalink().'" title="'.get_the_title().'">Read More</a>';
		echo '</div>';
		echo '</div>';	
		 
			} // endwhile;
					
			} // end if have posts

			else { 
				$li = '<a href="'.get_post_type_archive_link( 'smartest_news' ).'">'. __('News', 'crucible'). '</a>';
				?>
				<p><?php printf(__( 'Coming soon. See all %s.', 'crucible'), $li); ?></p>		
<?php 
				}
				wp_reset_postdata();


		echo $after_widget;

	}// end widget

	/**
	 * Sanitize widget form values as they are saved.
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Featured News', 'crucible' );
		}
		
    	?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'crucible' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

}
?>