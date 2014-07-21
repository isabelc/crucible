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

	
	// logo image @test works
	wp.customize( 'smartestthemes_options[logo_setting]', function( value ) {
		value.bind( function( newval ) {
			
			if ( '' == newval ) {
				$( '.site-title' ).show();
				$( 'img#customlogo' ).hide();
			
			} else {
			
				$( '.site-title' ).hide();
				$( '.site-branding' ).prepend( '<img id="customlogo" src="' + newval + '" />' );// @test
				
			}
			
		} );
	} );
	
	
	// logo height @test works
	wp.customize( 'smartestthemes_options[increase_logo]', function( value ) {
		value.bind( function( newval ) {
		
			if ( '' == newval ) {
				$('img#customlogo').css('max-height', '150px');
			} else {		
				$('img#customlogo').css({'max-height': newval + 'px'});
			}
		
		} );
	} );

	
	// logo font @test works
	wp.customize( 'smartestthemes_options[logo_font]', function( value ) {
		value.bind( function( newval ) {
			$('.site-title a').css('font-family', newval );
		
		} );
	} );
	
	// Logo color. @test
	wp.customize( 'smartestthemes_options[logo_color]', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
			
				$( '.site-title a' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title a' ).css( {
				'clip': 'auto',
				'color': to,
				'position': 'relative'
				} );
			
			
			}
		} );
	} );
	
	// Logo hover-color. @test
	wp.customize( 'smartestthemes_options[logo_hover_color]', function( value ) {
		value.bind( function( to ) {
			
			maincolor = wp.customize.value('smartestthemes_options[logo_color]')();// @test			
			
			if ( 'blank' === to ) {

				$('.site-title a').hover(
					function () {
						$(this).css( 'color', 'pink' );// @new @todo default title :hover color
					}, 
					function () {
						$(this).css( 'color', maincolor );
					}
				);			

			} else {
			
				$('.site-title a').hover(
					function () {
						$(this).css( 'color', to );
					}, 
					function () {
						$(this).css( 'color', maincolor );
					}
				);
			
	
			}
			

			
		} );
	} );
	
	
	
	
	
	// logo font-size @test
	wp.customize( 'smartestthemes_options[logo_fontsize]', function( value ) {
		value.bind( function( to ) {
		
			if ( '' == to ) {
				$( '.site-title a' ).css( 'font-size', '36px' );// @new @todo default size
			} else {
				$( '.site-title a' ).css( 'font-size', to );
			}
			
			
			
		} );
	} );	
	
	// hide tagline @test
	wp.customize( 'smartestthemes_options[hide_tagline]', function( value ) {
		value.bind( function( to ) {
			if ( '1' == to ) {
				$( '.site-description' ).hide();// @test
			} else {
				$( '.site-description' ).show();// @test
			}
		} );
	} );

	// tagline font @test works!
	wp.customize( 'smartestthemes_options[tagline_font]', function( value ) {
		value.bind( function( newval ) {
			$('h2.site-description').css('font-family', newval );
		
		} );
	} );	
	
	// tagline color. @test
	wp.customize( 'smartestthemes_options[tagline_color]', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( 'h2.site-description' ).css('color','#000');// @new @todo default tagline color
			} else {
				$( 'h2.site-description' ).css('color', to);
			}
		} );
	} );

	// tagline size @test
	wp.customize( 'smartestthemes_options[tagline_size]', function( value ) {
		value.bind( function( newval ) {
		
			if ( '' == newval ) {
				$('h2.site-description').css('font-size', '24px' );// @new @todo default size
			} else {
				$('h2.site-description').css('font-size', newval );
			}
		
		} );
	} );	

} )( jQuery );
