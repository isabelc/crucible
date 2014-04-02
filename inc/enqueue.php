<?php function crucible_enqueue( ) {

	wp_enqueue_style( 'crucible-style', get_stylesheet_uri() );

	wp_enqueue_style('font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');

/* @todo see if i need this

	$jsdir = get_template_directory_uri(). '/js/';

	if ( is_post_type_archive(array('smartest_staff', 'smartest_services'))) {
		wp_register_script( 'equalheights', $jsdir . 'equalheights.js', array( 'jquery' ));
		wp_enqueue_script('equalheights');	
	}

*/
	wp_enqueue_script( 'crucible-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'crucible-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}


}
add_action( 'wp_enqueue_scripts', 'crucible_enqueue' ); ?>