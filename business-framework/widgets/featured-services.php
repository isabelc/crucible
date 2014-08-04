<?php
/**
 * Adds Featured Services widget to show selected services
 *
 * @author 		Smartest Themes
 * @package 	Smartest Themes Business Framework
 * @extends 	WP_Widget
 */

class SmartestFeaturedServices extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'smartest_featured_services',
			__('Smartest Featured Services', 'crucible'),
			array( 'description' => __( 'Display selected featured services.', 'crucible' ), )
		);
		add_filter( 'smartestthemes_widget_styles', array($this, 'add_css'), 25 );// @test priority
	}
	
	/** @test if loads
	* Add CSS to custom-style.php
	*/
	public function add_css( $css ) {
		$new_css = $css;
		if ( get_option('st_show_services') == 'true' ) {
			$new_css .= '.sfswrap{width:100%;overflow:hidden;position:relative;padding-bottom:21px;border-bottom:1px #e5e5e5 solid;margin-bottom:20px}.sfsfig{float:left;margin:0 20px 0 0}.sfscontent{overflow:hidden;padding-right:15px}.widget_smartest_featured_services .widget-title{margin-left:0}#sidebar .sfsfig{float:none}#first .footer-widget .sfsfig,#third .footer-widget .sfsfig{max-width:30%;} .sfsfig a{width:100%;}@media screen and (max-device-width:568px){.sfsfig{float:none;margin:0 0 1em 0;}}';
		}
		return $new_css;
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

		if ( ! empty( $title ) )
			echo '<h3 class="widget-title">'. $title . '</h3>';
		
		/* loop through announcements */

		if( get_option('st_enable_service_sort') == 'true'  ) {

			// custom sort order is enabled

			$args = array( 
				'post_type' => 'smartest_services',
				'meta_query' => array(
							array  (
								'key' => '_smab_services_featured',
								'value'=> 'on' 
							)
						),
				'orderby' => 'meta_value_num',
				'meta_key' => '_smab_service-order-number',
				'order' => 'ASC'
				);

		} else {

			// default sort order

			$args = array( 
				'post_type' => 'smartest_services',
				'meta_query' => array(
							array  (
								'key' => '_smab_services_featured',
								'value'=> 'on' 
								)
							)
				);

		}
		$sbffs = new WP_Query( $args );
		if ( $sbffs->have_posts() ) {
			while ( $sbffs->have_posts() ) {
				$sbffs->the_post(); ?>
				<div class="sfswrap">
				<?php if ( has_post_thumbnail() ) { ?>
					<figure class="sfsfig"><a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( 'featservices' ); ?>
					</a></figure>
				<?php } ?>
				
				<div class="sfscontent">
					<h4><a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title(); ?></a></h4>
					<?php echo get_the_excerpt(); ?>
				</div>
				</div>
			<?php } // endwhile
		} else {
				$li = '<a href="'.get_post_type_archive_link( 'smartest_services' ).'">'. __('Services', 'crucible'). '</a>';
				?>
				<p><?php printf(__( 'Coming soon. See all %s.', 'crucible'), $li); ?></p>		
<?php	} // endif
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
			$title = __( 'Featured Services', 'crucible' );
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