<?php
/** Used by index.php and archive.php
 * @package Crucible
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); schema_type('archive'); ?>>
	<?php crucible_post_thumbnail(); ?>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><span itemprop="name"><?php the_title(); ?></span></a></h1>

		<div class="entry-meta">
			<?php crucible_entry_meta(); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php crucible_staff_social_buttons(); ?>
			
	<div class="entry-summary">
		<p itemprop="description">
		<?php echo get_the_excerpt(); ?>
		</p>
		<?php
		$anchor = is_post_type_archive('smartest_services') ? __('Details', 'crucible') : 'More';
		?>
		<a class="read-more" href="'. get_permalink() . '"><?php echo $anchor; ?></a>
		
	</div><!-- .entry-summary -->


	<footer class="entry-footer">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'crucible' ) );
				if ( $categories_list && crucible_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'crucible' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'crucible' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'crucible' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'crucible' ), __( '1 Comment', 'crucible' ), __( '% Comments', 'crucible' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'crucible' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->