<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package OaklanDesigns Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		<div class="comments-bubble"><a href="<?php comments_link(); ?>"><?php comments_number('0', '1', '%'); ?></a></div>
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php oaklan_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-summary">
		<?php the_excerpt(); ?>

		<div class="sep"></div>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php oaklan_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
