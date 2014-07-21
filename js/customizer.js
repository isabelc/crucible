/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	
	/* @test hold
	
	wp.customize( 'smartestthemes_options[logo_color]', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'color': to,
					'position': 'relative'
				} );
			}
		} );
	} );
	
	*/
	

	// logo font @test
	wp.customize( 'smartestthemes_options[logo_font]', function( value ) {
		value.bind( function( newval ) {
			$('.site-title a').css('font-family', newval );
		
		} );
	} );
	
	// Logo color. @test
	wp.customize( 'smartestthemes_options[logo_color]', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).css( 'color': to );
		} );
	} );
	
	// Logo color. @test
	wp.customize( 'smartestthemes_options[logo_hover_color]', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a:hover' ).css( 'color': to );
		} );
	} );
	


	
} )( jQuery );
