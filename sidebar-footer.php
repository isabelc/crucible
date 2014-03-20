<?php // if we have even 1 active footer sidebar, do dynamic sidebars and forget my hard code widget calls below.
	if ( is_active_sidebar( 'foot1'  )
		|| is_active_sidebar( 'foot2' )
		|| is_active_sidebar( 'foot3'  )
	) {
			if ( is_active_sidebar( 'foot1' ) ) : ?>
			<aside id="first" class="widget grid_3 alpha actives1">
				<?php dynamic_sidebar( 'foot1' ); ?>
			</aside><!-- #first .widget-area -->
			<?php endif; ?>
		
			<?php if ( is_active_sidebar( 'foot2' ) ) : ?>
			<aside id="second" class="widget grid_6 actives2">
				<?php dynamic_sidebar( 'foot2' ); ?>
			</aside><!-- #second .widget-area -->
			<?php endif; ?>
		
			<?php if ( is_active_sidebar( 'foot3' ) ) : ?>
			<aside id="third" class="widget grid_3 omega">
				<?php dynamic_sidebar( 'foot3' ); ?>
			</aside><!-- #third .widget-area -->
			<?php endif;

	} else { // no active dynamic footer sidebars, so hard code widget calls NEXT
		
			/** 
			 * Option names
			 */

			$ne = get_option('smartestb_show_news');
			$se = get_option('smartestb_show_services');
			$re = get_option('smartestb_add_reviews');
			$mo = get_option('smartestb_business_motto');
			

			/**
			 * widget combos
			 */
				$c3a = 'combo3a';
				$c3b = 'combo3b';
				$c3c = 'combo3c';
				$c3d = 'combo3d';
				$c2a = 'combo2a';
				$c2b = 'combo2b';
				$c2c = 'combo2c';
				$c2d = 'combo2d';
				$c2e = 'combo2e';
				$c2f = 'combo2f';
				$c1a = 'combo1a';
				$c1b = 'combo1b';
				$c1c = 'combo1c';
				$c1d = 'combo1d';

			// if 3 things enabled...
			
			// if news & feat.svc & reviews, set 'combo3a
			if( ( $ne == 'true' ) && ( $se == 'true' ) && ( $re == 'true' ) ) {
				
				$combo = 'combo3a';
			
			} elseif ( ( $ne == 'true' ) && ( $se == 'true' ) && ($mo) ) { 
			
				//elseif news & feat. svcs & motto, set combo3b
			
				$combo = 'combo3b';
			
			} elseif ( ( $ne == 'true' ) && ( $mo ) && ( $re == 'true' ) ) {
			
				//elseif news & motto & reviews, set 'combo3c
			
				$combo = 'combo3c';
			
			} elseif ( ( $re == 'true' ) && ( $se == 'true' ) && $mo ) {
			
				//elseif reviews & feat.svc. & motto, set 'combo3d
			
				$combo = 'combo3d';
	
			
			//elseif 2 things enabled...
			
			} elseif ( ( $ne == 'true' ) && ( $se == 'true' ) ) {
			
				//elseif news & feat.svcs., set 'combo2a
				
				$combo = 'combo2a';
			
			} elseif ( ( $ne == 'true' ) && ($re == 'true' ) ) {
			
				//elseif news & reviews, set 'combo2b
			
				$combo = 'combo2b';
			
			} elseif ( ( $ne == 'true' ) && $mo ) {
			
				//elseif news & motto , set 'combo2c
			
				$combo = 'combo2c';
			
			} elseif ( ( $se == 'true' ) && ( $re == 'true' ) ) {
			
				//elseif feat.svc & reviews, set 'combo2d
			
				$combo = 'combo2d';
			
			} elseif ( ( $se == 'true' ) && ($mo) ) {
			
				//elseif feat.svc & motto, set 'combo2e
			
				$combo = 'combo2e';
			
			} elseif ( ($mo)  && ( $re == 'true' ) ) {
			
				//elseif motto & reviews, set 'combo2f
			
				$combo = 'combo2f';
						
			// elseif only 1 thing...
			
			
			} elseif ( $ne == 'true' ) {
			
				//elseif news, set 'combo1a
			
				$combo = 'combo1a';
			
			} elseif ( $se == 'true' ) {
			
				//elseif feat.svc, set 'combo1b
			
				$combo = 'combo1b';
			
			} elseif ( $re == 'true' ) {
			
				//elseif reviews, set 'combo1c
			
				$combo = 'combo1c';
			
			} elseif ( $mo ) {
			
				//elseif motto, set 'combo1d
			
				$combo = 'combo1d';
			
			// else $combo = $no_combo means no footer bar
			
			} else { 
			
				// no combo, so no need to continue below
				// get out early
				return;
			
			}


			// If we get this far, we have a footer bar. Let do this.
			
			/* 
			 * Set gridnumber for each $combo
			 * widget 3 style='grid_3' always, no variable needed.
			 *
			 * widget 2 style='grid_6 center-wid', unless no wid3, then add class omega
			 * 
			 * if 3, $wid1style='grid_3'
			 * if 2, $wid1style='grid_6'
			 * if 1, $wid1style='grid_6' with the extra inline style
			 */
			
			
			if (in_array($combo, array($c3a, $c3b, $c3c, $c3d ))) { 
				$wid1style = 'class="widget grid_3 alpha"';
				$wid2style = 'class="widget grid_6"';
			}
			elseif (in_array($combo, array($c2a, $c2b, $c2c, $c2d, $c2e, $c2f ))) { 

				$wid1style = 'class="widget grid_6 alpha"';
				$wid2style = 'class="widget grid_6 omega"';

			} elseif (in_array($combo, array($c1a, $c1b, $c1c, $c1d ))) {
				$wid1style = 'class="widget grid_6" style="margin-left:auto;margin-right:auto;float:none;display:block;max-width:470px;"';
			}

			
			
// set var for stripped motto
$smo = esc_attr(stripslashes_deep(get_option('smartestb_business_motto')));

// localize wid titles
$inst = array('title' => __('Testimonials', 'smartestb'));
$insf = array('title' => __('Featured Services', 'smartestb'));
$insn = array('title' => __('What\'s New?', 'smartestb'), 'number' => 3,);
		
/* begin output */
			echo '<!-- wid 1 --> <aside id="first" '. $wid1style.'>';
				// widget 1
		
				if (in_array($combo, array($c3a, $c3b, $c3c, $c2a, $c2b, $c2c, $c1a ))) {

						the_widget('SmartestAnnouncements', $insn);
				
				} elseif (in_array($combo, array($c3d, $c1c ))) {
						
						the_widget('SmartestReviewsTestimonial', $inst);
				
				} elseif (in_array($combo, array($c2d, $c2e, $c1b ))) {
				
						the_widget('SmartestFeaturedServices', $insf);
				
				} elseif (in_array($combo, array($c2f, $c1d ))) {
				
						echo '<blockquote id="smotto">'.$smo.'</blockquote>';
				
				}
			

			echo '</aside>';
			
					
			// if not a single wid combo, so next 2 wids

			if (!in_array($combo, array($c1a, $c1b, $c1c, $c1d ))) { 
		
				echo '<!-- wid 2 --> <aside id="second" '. $wid2style.'>';
			
					// widget 2
				
				
					if (in_array($combo, array($c3a, $c3b, $c3d, $c2a ))) {
				
							the_widget('SmartestFeaturedServices', $insf);
				
				
					} elseif (in_array($combo, array($c3c, $c2c, $c2e ))) {
				
							echo '<blockquote id="smotto">'.$smo.'</blockquote>';
				
					} elseif (in_array($combo, array($c2b, $c2d, $c2f ))) {
					
							the_widget('SmartestReviewsTestimonial', $inst);
				
					} 

				echo '</aside>';
				
				// widget 3

				// if not a double wid combo, do wid3

				if (!in_array($combo, array($c2a, $c2b, $c2c, $c2d, $c2e, $c2f ))) {
			
						echo '<!-- wid 3 --> <aside id="third" class="widget grid_3 omega">';
			
							if (in_array($combo, array($c3a, $c3c ))) {
						
									the_widget('SmartestReviewsTestimonial', $inst);
						
							} elseif (in_array($combo, array($c3b, $c3d ))) {
						
									echo '<blockquote id="smotto">'.$smo.'</blockquote>';
					
							 }

						echo '</aside>';

				}
			
			} 
	} ?>