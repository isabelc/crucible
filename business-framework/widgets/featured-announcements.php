<?php
/**
 * Adds Featured Announcements widget to show selected announcements
 *
 * @author 	Smartest Themes
 * @package 	Smartest Business Framework
 * @extends 	WP_Widget
 */

class SmartestFeaturedAnnounce extends WP_Widget {

	/**
	 * Register widget
	 */
	public function __construct() {
		parent::__construct(
	 		'smartest_featured_announce', // Base ID
			__('Smartest Featured Announcements', 'smartestb'), // Name
			array( 'description' => __( 'Display selected featured announcements.', 'smartestb' ), )
		);
		add_action('wp_enqueue_scripts', array( $this, 'smar_featnews_css' ) );
	}
	/* add css */
	function smar_featnews_css() {
		wp_register_style('sfa', get_template_directory_uri().'/business-framework/widgets/sfa.css');
		wp_enqueue_style('sfa');
	} 

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {


		extract( $args );
		
		// these are our widget options
		$title = apply_filters('widget_title', $instance['title']);

		echo $before_widget;
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
				echo '<div id="sfawrap">';
			if ( has_post_thumbnail() ) {
			$thumb = get_post_thumbnail_id();
			$smallimage = vt_resize( $thumb, '', 40, 65, true);
			echo '<figure id="sfafig"><a href="'.get_permalink().'" title="'.get_the_title().'">';
			?>
			<img class="thumb" src="<?php echo $smallimage['url']; ?>" width="<?php echo $smallimage['width']; ?>" />
		<?php echo '</a></figure>';
		} else {
				// if not stopped with option smartestb_stop_theme_icon
				if(get_option('smartestb_stop_theme_icon') != 'true') {
				$smallimage = array('url' => get_template_directory_uri(). '/images/newsicon.png', 'width' => '40px', 'cl' => 'icon');
					echo '<figure class="img-indent"><a href="'.get_permalink().'" title="'.get_the_title().'">'; ?>

					<img class="icon" src="<?php echo get_template_directory_uri(); ?>/images/newsicon.png" width="40px" />
					<?php echo '</a></figure>';
				}

		}
					
						echo '<div id="sfacontent">';
							echo '<h4><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h4>';
							echo '<p>'. get_the_excerpt(). '</p>';
							echo '<a class="button" href="'.get_permalink().'" title="'.get_the_title().'">Read More</a>';
						echo '</div>';
				echo '</div>';	
		 
				} // endwhile;
					
			} // end if have posts

			else { 
				$li = '<a href="'.get_post_type_archive_link( 'smartest_news' ).'">'. __('News', 'smartestb'). '</a>';
				?>
				<p><?php printf(__( 'Coming soon. See all %s.', 'smartestb'), $li); ?></p>		
<?php 
				}
				wp_reset_postdata();


		echo $after_widget;

	}// end widget

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title'] );
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Featured News', 'smartestb' );
		}
		
    	?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'smartestb' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

}
?>