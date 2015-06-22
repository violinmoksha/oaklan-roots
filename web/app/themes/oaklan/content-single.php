<?php
/**
 * @package OaklanDesigns Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title single-entry">', '</h1>' ); ?>
		<div class="post-details">
			<?php oaklan_post_details(); ?>
		</div>
	</header><!-- .entry-header -->

	<?php if ( function_exists('yoast_breadcrumb') ) {
		yoast_breadcrumb('<p id="breadcrumbs">','</p>');
	} ?>

	<div class="entry-content">
		<?php echo get_post()->img_prepend; ?>
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'oaklan' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="after-post-details">
		<?php oaklan_after_post_details(); ?>
	</footer><!-- .after-post-details -->

	<div class="sep"></div>
	<div class="extra-content">
		<h3>Share This Post</h3>
		<div class="socialbmark">
			<a title="Get the RSS Feed of this blog" href="<?php bloginfo('rss2_url');?>"><img src="<?php bloginfo('template_url');?>/img/sb_rss.jpg" alt="RSS" style="position:relative;" onmouseover="this.style.bottom='3px'" onmouseout="this.style.bottom='0px'" /></a>
			<a title="Share this post using Digg" href="http://digg.com/submit?url=<?php the_permalink() ?>"><img src="<?php bloginfo('template_url');?>/img/sb_digg.jpg" alt="Digg" style="position:relative;" onmouseover="this.style.bottom='3px'" onmouseout="this.style.bottom='0px'" /></a>
			<a title="Tweet This" href="http://twitter.com/home?status=Currently%20reading%20<?php the_permalink() ?>"><img src="<?php bloginfo('template_url');?>/img/sb_twitter.jpg" alt="Twitter" style="position:relative;" onmouseover="this.style.bottom='3px'" onmouseout="this.style.bottom='0px'" /></a>
			<div class="tablet-clear"></div>
			<a title="Share this post using StumbleUpon" href="http://www.stumbleupon.com/submit?url=<?php the_permalink() ?>"><img src="<?php bloginfo('template_url');?>/img/sb_su.jpg" alt="StumbleUpon" style="position:relative;" onmouseover="this.style.bottom='3px'" onmouseout="this.style.bottom='0px'" /></a>
			<a title="Share this post using Delicious" href="http://del.icio.us/post?url=<?php the_permalink() ?>"><img src="<?php bloginfo('template_url');?>/img/sb_del.jpg" alt="Delicious" style="position:relative;" onmouseover="this.style.bottom='3px'" onmouseout="this.style.bottom='0px'" /></a>
			<a title="Share this post using Technorati" href="http://www.technorati.com/faves?add=<?php the_permalink() ?>"><img src="<?php bloginfo('template_url');?>/img/sb_techno.jpg" alt="Technorati" style="position:relative;" onmouseover="this.style.bottom='3px'" onmouseout="this.style.bottom='0px'" /></a>
		</div>
	</div>
	<div class="sep"></div>

</article><!-- #post-## -->
