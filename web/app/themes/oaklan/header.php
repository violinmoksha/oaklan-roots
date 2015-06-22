<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package OaklanDesigns Theme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<script src="//cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<?php wp_head(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-62589851-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site container">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<nav id="site-navigation" class="main-navigation navbar navbar-default" role="navigation">
			<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'oaklan' ); ?></a>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-main">
                    <span class="sr-only"><?php _e('Toggle navigation', 'oaklan'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="<?php echo get_template_directory_uri(); ?>/img/logo-mobile.png" alt="Oakland Designs"></a>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse-main">
                <?php
                wp_nav_menu( array(
                        'theme_location'  => 'primary',
                        'container'       => false,
                        'menu_class'      => 'nav navbar-nav',//  navbar-right
                        'walker'          => new Bootstrap_Nav_Menu(),
                    )
                );
                get_search_form();
                ?>
            </div><!-- /.navbar-collapse -->

		</nav><!-- #site-navigation -->

		<div class="site-branding">
			<div class="logo-switch"></div>
			<h1 class="site-title hide"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description hide"><?php bloginfo( 'description' ); ?></h2>
		</div>

		<div class="cats-nav">
			<?php wp_list_categories('style=none&hierarchical=0&title_li=&number=9'); ?>
		</div>

		<div class="clear"></div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
