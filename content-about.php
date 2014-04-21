<?php
/**
 * The template used for displaying About page content in page.php
 *
 * @package Crucible
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php smartestthemes_about_top_image(); ?>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<h3><?php bloginfo('description'); ?></h3>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php 
		$options = get_option('smartestthemes_options');
		$aboutcontent = stripslashes_deep($options['about_page']);// @test
		echo wpautop($aboutcontent); 
		the_content();
		smartestthemes_about_bottom_image();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'crucible' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php edit_post_link( __( 'Edit', 'crucible' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' );
if($options['stop_smartshare'] == 'false') {
	echo smartestthemes_share();
} ?>
</article><!-- #post-## -->
