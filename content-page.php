<?php
/**
 * The template used for displaying page content in page.php
 * @package Crucible
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php crucible_post_thumbnail(); ?>
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'crucible' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php edit_post_link( __( 'Edit', 'crucible' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
	<?php if(get_option('st_stop_smartshare') == 'false') {
		echo smartestthemes_share(); 
	} ?>
</article><!-- #post-## -->
