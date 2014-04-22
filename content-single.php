<?php
/**
 * @package Crucible
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); if ( 'smartest_staff' == get_post_type() ) { echo ' itemscope itemtype="http://schema.org/Person"'; } ?>>
	<header class="entry-header">
		<h1 class="entry-title" <?php if ( 'smartest_staff' == get_post_type() ) { echo ' itemprop="name"'; } ?>><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php crucible_entry_meta(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php crucible_post_thumbnail(); ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
		if ( 'smartest_staff' == get_post_type() ) {
			crucible_staff_social_buttons();
		}
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'crucible' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		/* translators: used between list items, there is a space after the comma */
		$category_list = get_the_category_list( __( ', ', 'crucible' ) );
		/* translators: used between list items, there is a space after the comma */
		$tag_list = get_the_tag_list( '', __( ', ', 'crucible' ) );

		if ( ! crucible_categorized_blog() ) {
			// This blog only has 1 category so we just need to worry about tags in the meta text
			if ( '' != $tag_list ) {
				$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'crucible' );
			} else {
				$meta_text = __( 'Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'crucible' );
			}

		} else {
			// But this blog has loads of categories so we should probably display them here
			if ( '' != $tag_list ) {
				$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'crucible' );
			} else {
				$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" rel="bookmark">permalink</a>.', 'crucible' );
			}

		} // end check for categories on this blog

		// do not do categories for cpts
		if ( ! in_array( get_post_type(), array('smartest_staff', 'smartest_staff', 'smartest_staff') ) ) {
			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink()
			);
		}
		edit_post_link( __( 'Edit', 'crucible' ), '<span class="edit-link">', '</span>' ); 
		if(get_option('stop_smartshare') == 'false') {
			echo smartestthemes_share(); 
		} ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
