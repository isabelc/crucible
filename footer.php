<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the #content div and all content after
 * @package Crucible
 */
?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'crucible_footer' ); ?>
			<?php if ( get_option('st_social_follow_show_footer') == 'true'  ) {
				do_action( 'crucible_social_buttons' );
			}
			?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
