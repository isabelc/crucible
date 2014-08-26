<?php
/**
 * Adds Services widget to list all services
 *
 * @author 	Smartest Themes
 * @package 	Smartest Themes Business Framework
 * @extends 	WP_Widget
 */

class SmartestServices extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'smartest_services_list',
			__('Smartest Services List', 'crucible'),
			array( 'description' => __( 'Display the full list of Services, or a selected segment.', 'crucible' ), )
		);
	}
	/**
	 * Front-end display of widget.
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Services', 'crucible' ) : $instance['title'], $instance, $this->id_base );		
		$service_category_term_id = isset( $instance['service_category'] ) ? $instance['service_category'] : '';
		$service_category = !empty($service_category_term_id) ? $service_category_term_id : '';
		global $smartestthemes_options;
		$sort = isset($smartestthemes_options['st_enable_service_sort']) ? $smartestthemes_options['st_enable_service_sort'] : '';
		echo $args['before_widget'];
		echo '<h3 class="widget-title">'. $title . '</h3>';

		/* loop through announcements */

		// if cat is selected, do tax query
		if ( ! empty ($service_category) ) {
		
			if ( 'true' == $sort ) {

				// custom sort order is enabled

				$query_args = array( 
					'posts_per_page' => -1, 
					'post_type' => 'smartest_services',
					'tax_query' => array(
						array(
							'taxonomy' => 'smartest_service_category',
							'field' => 'id',
							'terms' => array( $service_category ),
						)
					),
					'orderby' => 'meta_value_num',
					'meta_key' => '_stmb_service_order_number',
					'order' => 'ASC' );

			} else { 

				// default sort order
			
				$query_args = array( 
					'posts_per_page' => -1, 
					'post_type' => 'smartest_services',
					'tax_query' => array(
						array(
							'taxonomy' => 'smartest_service_category',
							'field' => 'id',
							'terms' => array( $service_category ),
						)
					),
					'orderby' => 'title',
					'order' => 'ASC' );

			}

		} else {

			// no tax query

			if ( 'true' == $sort ) {

				// custom sort order is enabled

				$query_args = array( 
					'posts_per_page' => -1, 
					'post_type' => 'smartest_services',
					'orderby' => 'meta_value_num',
					'meta_key' => '_stmb_service_order_number',
					'order' => 'ASC' );


			} else {

				// default sort order

				$query_args = array( 
					'posts_per_page' => -1, 
					'post_type' => 'smartest_services',
					'orderby' => 'title',
					'order' => 'ASC' );
			}

		}

		$sbfservices = new WP_Query( $query_args );

		if ( $sbfservices->have_posts() ) { ?>
			<ul class="serviceslist">
			<?php while ( $sbfservices->have_posts() ) {
				$sbfservices->the_post(); ?>
				<li><a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php echo get_the_title(); ?></a></li>
			<?php } ?>
			</ul>
		<?php }
		wp_reset_postdata();
		echo $args['after_widget'];
	}// end widget

	/**
	 * Sanitize widget form values as they are saved.
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['service_category'] = $new_instance['service_category'];
		return $instance;
	}

	/**
	 * Back-end widget form.
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : __( 'Services', 'crucible' );
		$instance_service_category = isset( $instance[ 'service_category' ] ) ? $instance[ 'service_category' ] : '';
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'crucible' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'service_category' ); ?>"><?php _e( 'Optional. Only show services of this category:', 'crucible' ); ?></label>
		<select class="widefat" name="<?php echo $this->get_field_name( 'service_category' ); ?>" id="<?php echo $this->get_field_id( 'service_category' ); ?>">
	  	<option value="" <?php if (empty($instance_service_category)) echo 'selected="selected"'; ?>>  
		</option>';
		<?php $service_cats = get_terms('smartest_service_category');
		foreach ( $service_cats as $service_cat ) {
			$sele = ( $service_cat->term_id == $instance_service_category ) ? 'selected="selected"' : '';
		  	$option = '<option value="' . $service_cat->term_id  . '" ' . $sele . '>';
			$option .= $service_cat->name;
			$option .= '</option>';
			echo $option;
		} ?>
		</select>
		</p>
		<?php 
	}
} ?>