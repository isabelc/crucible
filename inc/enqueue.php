<?php function crucible_wp_head() {
	if( !is_page( get_option('smartestthemes_reviews_page_id') ) ) {
		echo "<script>jQuery(function(){jQuery('table').wrap('<div class=\"overflow\">');});</script>";
	}
}
add_action('wp_head','crucible_wp_head');

function crucible_enqueue( ) {

	wp_enqueue_style( 'crucible-style', get_stylesheet_uri() );

	wp_enqueue_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');

	$jsdir = get_template_directory_uri(). '/js/';
	

	/**
	*	@new see if i need this, depending on theme's style. if so, create the .js file
	*
	* 	do like this instead of inline because we need to make ut jquery dependent
	*
	
	if ( is_post_type_archive(array('smartest_staff', 'smartest_services')) || is_tax( 'smartest_service_category' ) ) {
		wp_register_script( 'equalheights', $jsdir . 'equalheights.js', array( 'jquery' ), false, true);
		wp_enqueue_script('equalheights');	
	}

	*/

	wp_enqueue_script( 'crucible-navigation', $jsdir . 'navigation.js', array(), false, true );
	wp_enqueue_script( 'crucible-skip-link-focus-fix', $jsdir . 'skip-link-focus-fix.js', array(), 'false', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'crucible_enqueue' ); ?>