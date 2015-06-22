<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package OaklanDesigns Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="comments-bubble"><a href="<?php comments_link(); ?>"><?php comments_number('0', '1', '%'); ?></a></div>
	</header><!-- .entry-header -->

	<div class="clear"></div>
	
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'oaklan' ),
				'after'  => '</div>',
			) );
		?>
		<div class="sep"></div>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php edit_post_link( __( 'Edit', 'oaklan' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
