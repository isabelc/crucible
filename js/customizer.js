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

	
	// logo image
	wp.customize( 'smartestthemes_options[logo_setting]', function( value ) {
		value.bind( function( newval ) {
			
			if ( '' == newval ) {
				$( '.site-title' ).show();
				$( 'img#customlogo' ).hide();
			
			} else {
			
				$( '.site-title' ).hide();
				$( '.site-branding' ).prepend( '<img id="customlogo" src="' + newval + '" />' );
				
			}
			
		} );
	} );
	
	
	// logo height
	wp.customize( 'smartestthemes_options[increase_logo]', function( value ) {
		value.bind( function( newval ) {
		
			if ( '' == newval ) {
				$('img#customlogo').css('max-height', '150px');
			} else {		
				$('img#customlogo').css({'max-height': newval + 'px'});
			}
		
		} );
	} );

	// logo font
	wp.customize( 'smartestthemes_options[logo_font]', function( value ) {
		value.bind( function( newval ) {
			$('.site-title a').css('font-family', newval );
		} );
	} );
	
	// Logo color
	wp.customize( 'smartestthemes_options[logo_color]', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).css('color', to);
		} );
	} );
	
	


	// logo font-size
	wp.customize( 'smartestthemes_options[logo_fontsize]', function( value ) {
		value.bind( function( to ) {
		
			if ( '' == to ) {
				$( '.site-title a' ).css( 'font-size', '36px' );// @new default size
			} else {
				$( '.site-title a' ).css( 'font-size', to );
			}
			
			
			
		} );
	} );	
	
	// hide tagline
	wp.customize( 'smartestthemes_options[hide_tagline]', function( value ) {
		value.bind( function( to ) {
			if ( '1' == to ) {
				$( '.site-description' ).hide();
			} else {
				$( '.site-description' ).show();
			}
		} );
	} );

	// tagline font
	wp.customize( 'smartestthemes_options[tagline_font]', function( value ) {
		value.bind( function( newval ) {
			$('h2.site-description').css('font-family', newval );
		
		} );
	} );	
	// tagline color
	wp.customize( 'smartestthemes_options[tagline_color]', function( value ) {
		value.bind( function( to ) {
			$( 'h2.site-description' ).css('color', to);
		} );
	} );
	// tagline size
	wp.customize( 'smartestthemes_options[tagline_size]', function( value ) {
		value.bind( function( newval ) {
		
			if ( '' == newval ) {
				$('h2.site-description').css('font-size', '24px' );// @new default size
			} else {
				$('h2.site-description').css('font-size', newval );
			}
		
		} );
	} );
	
	// link color
	wp.customize( 'smartestthemes_options[link_color]', function( value ) {
		value.bind( function( to ) {
			
			$( "a, i.fa, .widget ul li, .entry-meta.jobtitle,.menu .current-menu-item a, body.post-type-archive-smartest_staff .menu li.staff a, body.post-type-archive-smartest_services .menu li.services a, body.post-type-archive-smartest_news .menu li.news a, body.tax-smartest_service_category .menu li.services a, body.single-smartest_services .menu .services a, body.single-smartest_staff .menu .staff a, body.single-smartest_news .menu .news a, body.about .menu li.about a, body.contact .menu li.contact a, body.reviews .menu li.reviews a, .menu > li:first-child:hover a, body.single-post .menu li.blog a, body.archive.author .menu li.blog a, body.archive.category .menu li.blog a, body.archive.tag .menu li.blog a, body.archive.date .menu li.blog a, body.home .menu > li.home > a" ).css('color', to);
	
			$( "input#stcf_contact, input#submit, #smar_button_1, #smar_submit_btn" ).css('background', to);	


	} );
		
		
		
	} );
	
	
	// Link hover-color.
		
	wp.customize( 'smartestthemes_options[link_hover_color]', function( value ) {
		value.bind( function( to ) {
		
			maincolor = wp.customize.value('smartestthemes_options[link_color]')(); 
			
			$('a, i.fa').hover(
				function () {
					$(this).css( 'color', to );
				}, 
				function () {
					$(this).css( 'color', maincolor );
				}
			);
			$('button, .button, input#stcf_contact, input#submit, #smar_button_1, #smar_submit_btn').hover(
				function () {
					$(this).css( 'background', to );
				}, 
				function () {
					$(this).css( 'background', maincolor );
				}
			);
			
		} );
	} );

	
} )( jQuery );
