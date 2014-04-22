<?php
/**
 * Adds Reviews Testimonial widget
 *
 * @author 		Smartest Themes
 * @package 	Reviews
 * @extends 	WP_Widget
 */

class SmartestReviewsTestimonial extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'smartest_reviews_testimonial',
			'Smartest Reviews Testimonial',
			array( 'description' => __( 'Display a random review as a testimonial.', 'crucible' ), )
		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$number = isset( $instance['number'] ) ? $instance['number'] : '';

		echo $before_widget;
		if ( ! empty( $title ) )
			echo '<h3 class="widget-title">'. $title . '</h3>';
		/** 
		* pull reviews from smartest reviews table 
		*/

		global $wpdb;
		// get the permalink by page id.
		$reviews_pageurl = get_permalink(get_option('reviews_page_id'));

		$pre = $wpdb->base_prefix;

		if ( is_multisite() ) { 
			
			global $blog_id;
			$bi = get_current_blog_id();
			
			$pre2 = $pre . $bi . '_smareviewsb';

		} else {

			// not Multisite
			$pre2 = $pre . 'smareviewsb';
		}

		if ( ! empty( $number ) )
			$number_testimonials = $number;
		else
			$number_testimonials = 1;
		
		$getreviews = $wpdb->get_results("SELECT review_text FROM $pre2 WHERE status = 1 LIMIT 0,$number_testimonials");
		
		if ( empty( $getreviews ) ) {
				//no review yet, lure them to leave one
				echo '<p>'. __( 'Be the first to', 'crucible' ) . ' <a href="' . $reviews_pageurl . '">' . __( 'leave a review...', 'crucible' ) . '</a></p>';
		} else {
				foreach ( $getreviews as $getreview ) {
					echo '<blockquote>' . wp_trim_words( $getreview->review_text, 20) . '</blockquote><br />';
				}
				echo '<a href="'.$reviews_pageurl.'">'.__('More...', 'crucible').'</a>';
		}
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
		$instance['number'] = strip_tags( $new_instance['number'] );
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
			$title = __( 'Testimonials', 'crucible' );
		}

		if ( isset( $instance[ 'number' ] ) ) {
			$number = $instance[ 'number' ];
		}
		else {
			$number = 1;
		}

		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'crucible' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'How many testimonials to show:', 'crucible' ); ?></label> 
		<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo esc_attr( $number ); ?>" />
	</p>

		<?php 
	}

} ?>