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
			
				sitetitle = wp.customize.value('blogname')();
				
				$( 'img#customlogo' ).hide();
				
				// insert title
				$( '.site-branding' ).prepend( '<h1 class="site-title"><a href="#">' + sitetitle + '</a></h1>' );

			} else {
			
				$( '.site-title' ).hide();

				// insert image
				$( '.site-branding' ).prepend( '<img id="customlogo" src="' + newval + '" />' );
				
			}
			
		} );
	} );
	
	
	// logo image height
	wp.customize( 'smartestthemes_options[increase_logo]', function( value ) {
		value.bind( function( newval ) {
		
			if ( '' == newval ) {
			
				$('img#customlogo').css('max-height', '150px');
			
			} else {
				
				// hide cut image
				$( 'img#customlogo' ).hide();
				
				// prepend full size image
				fullImage = wp.customize.value('smartestthemes_options[logo_setting]')();
				$( '.site-branding' ).prepend( '<img id="customlogo" src="' + fullImage + '" />' );
					
				// add the css
				$('img#customlogo').css({'max-height': newval + 'px'});
				
			}
		
		} );
	} );

	// logo font
	wp.customize( 'smartestthemes_options[logo_font]', function( value ) {
		value.bind( function( newval ) {
		
			// @test see if this simple method works when clearing.
			if ( '' == newval ) {
				$('.site-title a').css('font-family', 'Copperplate Bold, Copperplate Gothic Bold, serif;' );// @new default @test
			} else {
				$('.site-title a').css('font-family', newval );
			}
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
				// hide tagline
				$( '.site-description' ).hide();
			} else {
				// show tagline
				tagline = wp.customize.value('blogdescription')();
				$( '.site-branding' ).append( '<h2 class="site-description">' + tagline + '</h2>' );
			}
		} );
	} );

	// tagline font
	wp.customize( 'smartestthemes_options[tagline_font]', function( value ) {
		value.bind( function( newval ) {
			// @test see if this simple method works when clearing.
			if ( '' == newval ) {
				$('h2.site-description').css('font-family', 'Copperplate Bold, Copperplate Gothic Bold, serif;' );// @new default @test
			} else {
				$('h2.site-description').css('font-family', newval );
			}
		
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
			
			$( "a, i.fa-clock-o, i.fa-bullhorn, .entry-meta.jobtitle,.menu .current-menu-item a, body.post-type-archive-smartest_staff .menu li.staff a, body.post-type-archive-smartest_services .menu li.services a, body.post-type-archive-smartest_news .menu li.news a, body.tax-smartest_service_category .menu li.services a, body.single-smartest_services .menu .services a, body.single-smartest_staff .menu .staff a, body.single-smartest_news .menu .news a, body.about .menu li.about a, body.contact .menu li.contact a, body.reviews .menu li.reviews a, .menu > li:first-child:hover a, body.single-post .menu li.blog a, body.archive.author .menu li.blog a, body.archive.category .menu li.blog a, body.archive.tag .menu li.blog a, body.archive.date .menu li.blog a, body.home .menu > li.home > a" ).css('color', to);

		} );
	} );
	
	// Link hover-color.
	wp.customize( 'smartestthemes_options[link_hover_color]', function( value ) {
		value.bind( function( to ) {
		
			maincolor = wp.customize.value('smartestthemes_options[link_color]')();
			
			$('a, i.fa-clock-o, i.fa-bullhorn').hover(
				function () {
					$(this).css( 'color', to );
				}, 
				function () {
					$(this).css( 'color', maincolor);
				}
			);
			
		} );
	} );

	// Button color
	wp.customize( 'smartestthemes_options[button_color]', function( value ) {
		value.bind( function( to ) {
			
			$( "button, .button, input#stcf_contact, input#submit, a#smar_button_1, input#smar_submit_btn" ).css('background', to);	

		} );
	} );

	// Button hover color
	wp.customize( 'smartestthemes_options[button_hover_color]', function( value ) {
		value.bind( function( to ) {
		
			mainButtoncolor = wp.customize.value('smartestthemes_options[button_color]')(); 
			
			$('button, .button, input#stcf_contact, input#submit, a#smar_button_1, input#smar_submit_btn').hover(
				function () {
					$(this).css( 'background', to );
				}, 
				function () {
					$(this).css( 'background', mainButtoncolor );
				}
			);			

		} );
	} );
	
	// Button Text color
	wp.customize( 'smartestthemes_options[button_text_color]', function( value ) {
		value.bind( function( to ) {
			
			$( "button, .button, input#stcf_contact, input#submit, a#smar_button_1, input#smar_submit_btn" ).css('color', to);	

		} );
	} );
	
	//
	// Table caption color
	wp.customize( 'smartestthemes_options[table_caption_bg_color]', function( value ) {
		value.bind( function( to ) {
			
			$( "#today, table caption, thead" ).css('background', to);

		} );
	} );	
	// Table alternating row color
	wp.customize( 'smartestthemes_options[table_alt_row_color]', function( value ) {
		value.bind( function( to ) {
			$("tbody tr:nth-child(even)").css({ "background": to });
		} );
	} );	
	// Header background color
	wp.customize( 'smartestthemes_options[header_bg_color]', function( value ) {
		value.bind( function( to ) {
		
			// @test clear
			if ( '' == to ) {
				$( "#masthead" ).css('background-color', 'transparent');// @test clear
			} else {
		
				$( "#masthead" ).css('background', to);	
			}
		} );
	} );
	// Footer background color
	wp.customize( 'smartestthemes_options[footer_bg_color]', function( value ) {
		value.bind( function( to ) {
		
			if ( '' == to ) {
				$( "footer.site-footer" ).css('background-color', 'transparent');// @test clear
			} else {
				$( "footer.site-footer" ).css('background', to);
			}
			
			
		} );
	} );	
	// Background Texture
	wp.customize( 'smartestthemes_options[bg_texture]', function( value ) {
		value.bind( function( to ) {
			
			// @test clear not working if coming in with texture/
			
			$('body').removeClass('texture_argyle texture_dark_brick_wall texture_white_brick_wall texture_carbon_fibre texture_carpet texture_checkered_pattern texture_circles texture_crissXcross texture_diagonal_striped_brick texture_double_lined texture_hexellence texture_paven texture_plaid texture_pinstripe texture_speckled texture_tiles texture_wood');
			
			if ( '' != to ) {
				$("body").addClass("texture_" + to);
			}
		} );
	} );	


	// @test the 4 font colors to see what happens when clearing it in live preview.
	
	
	// Attention Grabber text color @test
	wp.customize( 'smartestthemes_options[att_grabber_color]', function( value ) {
		value.bind( function( to ) {
		
			if ( '' == to ) {
				$( '.attention-grab' ).css('color', '#404040');// @new default @test does this remove the color?
			} else {		
				$( '.attention-grab' ).css('color', to);
			}
		} );
	} );
	
	// Attention Grabber font @test
	wp.customize( 'smartestthemes_options[att_grabber_font]', function( value ) {
		value.bind( function( newval ) {
			// @test see if this simple method works when clearing.
			if ( '' == newval ) {
				$('.attention-grab').css('font-family', 'cyan' );// @new default, or 'inherit' if no default
			} else {
				$('.attention-grab').css('font-family', newval );
			}
		} );
	} );
	// Attention Grabber size @test
	wp.customize( 'smartestthemes_options[attgrabber_fontsize]', function( value ) {
		value.bind( function( newval ) {
		
			if ( '' == newval ) {
				$('.attention-grab').css('font-size', '64px' );// @new default size
			} else {
				$('.attention-grab').css('font-size', newval );
			}
		
		} );
	} );
	
	// Body text color @test
	wp.customize( 'smartestthemes_options[body_text_color]', function( value ) {
		value.bind( function( to ) {
		
			if ( '' == to ) {
				$( 'body, button, input, select, textarea' ).css('color', '#404040');// @new default @test does this remove the color?
			} else {
				$( 'body, button, input, select, textarea' ).css('color', to);
			}
		
			
		} );
	} );
	
	// Body font @test
	// @todo this, if going into the customizer with and already custom-set-font, it will not clear to default. test it now with 'inherit'
	
	wp.customize( 'smartestthemes_options[body_font]', function( value ) {
		value.bind( function( newval ) {
		
			// @test see if this simple method works when clearing.
			if ( '' == newval ) {
				$('#content').css('font-family', 'inherit' );// @test
			} else {
			
				$('#content').css('font-family', newval );
			}
			
		} );
	} );
	// Body font size @test
	wp.customize( 'smartestthemes_options[body_fontsize]', function( value ) {
		value.bind( function( newval ) {
		
			if ( '' == newval ) {
				// @test of the next line works to clear it properly
				$('#content .main, #home-footer, blockquote').css( 'font-size', '100%' );// @test!!!!
			} else {
				$('#content .main, #home-footer, blockquote').css('font-size', newval );
			}
		
		} );
	} );
	
	
	// Footer text color @test
	wp.customize( 'smartestthemes_options[footer_text_color]', function( value ) {
		value.bind( function( to ) {
			if ( '' == to ) {
				$( '.site-info' ).css('color', '#404040');// @new default @test does this remove the color?
			} else {
				$( '.site-info' ).css('color', to);
			}
		} );
	} );

	
	// Heading text color @test
	wp.customize( 'smartestthemes_options[heading_text_color]', function( value ) {
		value.bind( function( to ) {
		
			if ( '' == to ) {
				$( '#content h1,#content h2,h3,h4,h5,h6' ).css('color', '#404040');// @new default @test does this remove the color?
			} else {
				$( "#content h1,#content h2,h3,h4,h5,h6" ).css('color', to);
			}
			
		} );
	} );

	
	// Heading font @test
	
	wp.customize( 'smartestthemes_options[heading_font]', function( value ) {
		value.bind( function( newval ) {
		
			// @test see if this simple method works when clearing.
			if ( '' == newval ) {
				$('#content h1, #content h1 a, #content h2, #content h2 a, h3, h3 a, h4, h4 a, h5, h5 a').css('font-family', 'inherit' );// @test
			} else {
			
				$('#content h1, #content h1 a, #content h2, #content h2 a, h3, h3 a, h4, h4 a, h5, h5 a').css('font-family', newval );
			}
			
		} );
	} );
	
	// h1 Heading font size @test
	wp.customize( 'smartestthemes_options[h1_fontsize]', function( value ) {
		value.bind( function( to ) {
			if ( '' == to ) {
				// @test of the next line works to clear it properly
				$('#content h1, #content h1 a').css({'font-size': '36px', 'font-size': '3.2rem'});// @new default @test
			} else {
				$('#content h1, #content h1 a').css('font-size', to );
			}
		
		} );
	} );
	
	// h2 Heading font size @test
	wp.customize( 'smartestthemes_options[h2_fontsize]', function( value ) {
		value.bind( function( to ) {
			if ( '' == to ) {
				// @test of the next line works to clear it properly
				$('#content h2, #content h2 a').css({'font-size': '32px', 'font-size': '2.8rem'});// @new default @test
			} else {
				$('#content h2, #content h2 a').css('font-size', to );
			}
		
		} );
	} );
	
	// h3 Heading font size @test
	wp.customize( 'smartestthemes_options[h3_fontsize]', function( value ) {
		value.bind( function( to ) {
			if ( '' == to ) {
				// @test of the next line works to clear it properly
				$('h3').css({'font-size': '28px', 'font-size': '2.6rem'});// @new default @test
			} else {
				$('h3').css('font-size', to );
			}
		
		} );
	} );
	
	// h4 Heading font size @test
	wp.customize( 'smartestthemes_options[h4_fontsize]', function( value ) {
		value.bind( function( to ) {
			if ( '' == to ) {
				// @test of the next line works to clear it properly
				$('h4').css({'font-size': '24px', 'font-size': '2.4rem'});// @new default @test
			} else {
				$('h4').css('font-size', to );
			}
		
		} );
	} );
	
	// Footer text
	
	wp.customize( 'smartestthemes_options[footer_text]', function( value ) {
		value.bind( function( to ) {
			if ( '' == to ) {
				
				//  @test clearing
				$( "#temp-footer" ).remove();
				
				
			} else {

				// @test
				if($('.social').length > 0) {
					 $( '.social' ).before( '<br /><span id="temp-footer">' + to + '</span>' );
				} else {
					$( '.site-info' ).append( '<br /><span id="temp-footer">' + to + '</span>' );
				}
				
			}
		
		} );
	} );
		
	
	
	// Override Footer
	
	wp.customize( 'smartestthemes_options[override_footer]', function( value ) {
		value.bind( function( to ) {
			if ( '1' == to ) {
				// hide footer
				$( "#footer-copyright, #footer-sitename" ).hide();
				
			} else {
				// show default footer
				
				bn = wp.customize.value('blogname')();
				
				year = (new Date).getFullYear(); // @test
				
				$( '.site-info' ).prepend( '<span id="footer-copyright">Copyright &copy; ' + year + '</span> <a id="footer-sitename" href="#">' + bn + '</a>' );
				
			}
		} );
	} );	
	
	
	
	
	
	
} )( jQuery );