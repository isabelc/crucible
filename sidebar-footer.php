<?php // if we have even 1 active footer sidebar, do dynamic sidebars
	if ( is_active_sidebar( 'foot1'  )
		|| is_active_sidebar( 'foot2' )
		|| is_active_sidebar( 'foot3'  )
	) {
		?><div id="home-footer" class="wrapper"><?php
		if ( is_active_sidebar( 'foot1' ) ) : 
		?>
			<aside id="first" class="one-third alpha actives1">
				<?php dynamic_sidebar( 'foot1' ); ?>
			</aside>
		<?php endif; ?>
		
		<?php if ( is_active_sidebar( 'foot2' ) ) : ?>
			<aside id="second" class="one-third actives2">
				<?php dynamic_sidebar( 'foot2' ); ?>
			</aside>
			
		<?php endif; ?>
		
		<?php if ( is_active_sidebar( 'foot3' ) ) : ?>
			<aside id="third" class="one-third omega">
				<?php dynamic_sidebar( 'foot3' ); ?>
			</aside>
		<?php endif;
		
		?></div><?php 

	} else { // no active dynamic footer sidebars, so hard code widgets
		global $smartestthemes_options;
		$ne = isset($smartestthemes_options['st_show_news']) ? $smartestthemes_options['st_show_news'] : '';
		$se = isset($smartestthemes_options['st_show_services']) ? $smartestthemes_options['st_show_services'] : '';
		$re = isset($smartestthemes_options['st_add_reviews']) ? $smartestthemes_options['st_add_reviews'] : '';
		$mo = get_bloginfo('description');
			
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
			// no combo, so no need to continue
			return;
		}
		
		// If we get this far, we have a footer bar.
		
		/* 
		 * Assign classes to widgets 1 and 2 based on number of columns for each $combo
		 */
		
		if (in_array($combo, array($c3a, $c3b, $c3c, $c3d ))) { 
			
			// we have 3 widgets
			
			$wid1style = 'class="one-third alpha"';
			$wid2style = 'class="one-third"';
			
		} elseif (in_array($combo, array($c2a, $c2b, $c2c, $c2d, $c2e, $c2f ))) { 
			
			// we have 2 widgets
			
			$wid1style = 'class="one-half alpha"';
			$wid2style = 'class="one-half omega"';
		} elseif (in_array($combo, array($c1a, $c1b, $c1c, $c1d ))) {
		
			// we have only 1 widget
			
			$wid1style = 'class="one-half" style="margin-left:auto;margin-right:auto;float:none;display:block;max-width:470px;"';
		}
		// localize widget titles
		$inst = array('title' => __('Testimonials', 'crucible'));
		$insf = array('title' => __('Featured Services', 'crucible'));
		$insn = array('title' => __('What\'s New?', 'crucible'), 'number' => 3,);
		
		/* begin output */
		echo '<div id="home-footer" class="wrapper"><aside id="first" '. $wid1style.'>';
		// widget 1
		if (in_array($combo, array($c3a, $c3b, $c3c, $c2a, $c2b, $c2c, $c1a ))) {
			the_widget('SmartestAnnouncements', $insn);
		} elseif (in_array($combo, array($c3d, $c1c ))) {
			the_widget('SmartestReviewsTestimonial', $inst);
		} elseif (in_array($combo, array($c2d, $c2e, $c1b ))) {
				the_widget('SmartestFeaturedServices', $insf);
		} elseif (in_array($combo, array($c2f, $c1d ))) {
				echo '<blockquote id="smotto">'.$mo.'</blockquote>';
		}
		echo '</aside>';
					
		// if not a single wid combo, so next 2 wids

		if (!in_array($combo, array($c1a, $c1b, $c1c, $c1d ))) { 
			echo '<!-- wid 2 --> <aside id="second" '. $wid2style.'>';
			// widget 2
			if (in_array($combo, array($c3a, $c3b, $c3d, $c2a ))) {
				the_widget('SmartestFeaturedServices', $insf);
			} elseif (in_array($combo, array($c3c, $c2c, $c2e ))) {
				echo '<blockquote id="smotto">'.$mo.'</blockquote>';
			} elseif (in_array($combo, array($c2b, $c2d, $c2f ))) {
					the_widget('SmartestReviewsTestimonial', $inst);
			} 
			echo '</aside>';
			
			// widget 3

			// if not a double wid combo, do wid3

			if (!in_array($combo, array($c2a, $c2b, $c2c, $c2d, $c2e, $c2f ))) {
				echo '<!-- wid 3 --> <aside id="third" class="one-third omega">';
				if (in_array($combo, array($c3a, $c3c ))) {
					the_widget('SmartestReviewsTestimonial', $inst);
				} elseif (in_array($combo, array($c3b, $c3d ))) {
					echo '<blockquote id="smotto">'.$mo.'</blockquote>';
				}
				echo '</aside>';
			}
		}
		
		echo '</div>';
		
	} ?>