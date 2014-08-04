<?php
/**
 * Adds Staff widget to list all staff members
 *
 * @author 		Smartest Themes
 * @package 	Smartest Themes Business Framework
 * @extends 	WP_Widget
 */

class SmartestStaff extends WP_Widget {
	/**
	 * Register widget
	 */
	public function __construct() {
		parent::__construct(
	 		'smartest_staff_list',
			__('Smartest Staff List', 'crucible'),
			array( 'description' => __( 'Display the full list of Staff members.', 'crucible' ), )
		);
		add_action('wp_enqueue_scripts', array( $this, 'staff_css' ) );
	}

	/**
	 * Register stylesheet
	 */
	function staff_css() {
		wp_register_style('sst',
		get_template_directory_uri().'/business-framework/widgets/sst.css');
	} 

	/**
	 * Front-end display of widget.
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		
		// these are our widget options
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		wp_enqueue_style('sst');
		if ( ! empty( $title ) )
			echo '<h3 class="widget-title">'. $title . '</h3>';
		/** 
		* loop through staff
		*/
		$args = array( 
			'posts_per_page' => -1, 
			'post_type' => 'smartest_staff',
			'orderby' => 'meta_value_num',
			'meta_key' => '_smab_staff-order-number',
			'order' => 'ASC' );
		$sbfstaff = new WP_Query( $args );
		if ( $sbfstaff->have_posts() ) {
			while ( $sbfstaff->have_posts() ) {
				$sbfstaff->the_post(); ?>
				<div class="sstwrap">
				<?php if ( has_post_thumbnail() ) { ?>
					<figure class="ssfig"><a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'staffwidget' ); ?></a></figure>
				<?php } ?>
				<div class="sstcontent">
				<h5><a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title(); ?></a></h5></div></div>
			<?php }
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
			$title = __( 'Staff', 'crucible' );
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