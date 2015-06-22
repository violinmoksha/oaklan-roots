<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package OaklanDesigns Theme
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'oaklan' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'oaklan' ), 'WordPress' ); ?></a>
			&nbsp;|&nbsp;
			<?php printf( __( 'Theme: %1$s by %2$s', 'oaklan' ), 'oaklan', '<a href="http://oaklandesigns.com/" rel="designer">Paul Yeager</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
