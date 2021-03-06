<?php function crucible_wp_head() {
	// inline js for faster page speed
	if ( is_front_page() ) {
	$out = "<script>
			function crucible_center_widgets(){
			if (window.screen.width < 769) { return; }
// count
var kids = document.querySelectorAll('#home-footer .widget').length;

if(2 == kids){

    var one=document.getElementsByClassName('actives1')[0];
    var two=document.getElementsByClassName('actives2')[0];

    // remove class one-third
    one.className=one.className.replace('one-third','');
    two.className=two.className.replace('one-third','');
    
   // add class one-half
    one.className = one.className + ' one-half';
    two.className = two.className + ' one-half';
    
    // add class omega to 2
    two.className = two.className + ' omega';


}

if(1 == kids){
    var onlyone=document.getElementsByClassName('actives1')[0];
	
    // remove class one-third from the dynamic widget 1 
    onlyone.className=onlyone.className.replace('one-third','');    
    
	// add classes .one-half.only1
    onlyone.className = onlyone.className + ' one-half only1';
}


}
		
window.onload = crucible_center_widgets;</script>";
		echo $out;
	}
	
}
add_action('wp_head','crucible_wp_head');

function crucible_enqueue( ) {

	wp_enqueue_style( 'crucible-style', get_stylesheet_uri() );
	wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');

	$jsdir = get_template_directory_uri(). '/js/';
	
	/**
	*	@new see if i need this, depending on theme's style. if so, create the .js file
	*
	* 	do like this instead of inline because we need to make ut jquery dependent
	* or consider making a non-jquery alternative with pure javascript.
	*
	
	if ( is_post_type_archive(array('smartest_staff', 'smartest_services')) || is_tax( 'smartest_service_category' ) ) {
		wp_register_script( 'equalheights', $jsdir . 'equalheights.js', array( 'jquery' ), false, true);
		wp_enqueue_script('equalheights');	
	}

	*/

	wp_enqueue_script( 'crucible-js', $jsdir . 'theme.js', array(), false, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'crucible_enqueue' ); ?>