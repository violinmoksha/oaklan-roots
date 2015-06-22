<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package OaklanDesigns Theme
 */

if ( ! function_exists( 'oaklan_improved_trim_excerpt') ) :
/**
 * a post->ID specific excerpt trimmer, adapted from zenverse blacktribe,
 * done because get_the_excerpt can only be used during the Loop per the wp codex
 * and we wanted a per-post way to access the properly trimmed excerpt right here in our template tags
 * instead of having to call the Loop twice just to output the Front Page
 */

function oaklan_improved_trim_excerpt($text) {
	global $post;

	$text = str_replace(']]>', ']]&gt;', $text);
	$text = strip_tags($text);
	$excerpt_length = 20;
	$words = explode(' ', $text, $excerpt_length + 1);

  if (count($words)> $excerpt_length) {
		array_pop($words);
    array_push($words, '...');
		$text = implode(" ", $words);
	}

	return $text;
}
endif;

if ( ! function_exists( 'oaklan_carousel') ) :
/**
 * Bootstrap Carousel as adaptation of the "featured slider" in the old zenverse blacktribe theme
 *
 * @todo recode to take a different category from wp theme customisation, for now this is v0.1
 * @todo all other blacktribe zenverse admin customisation i.e. ad and twitter integration
 * @todo also, excerpt should come from post_excerpt not post_content but for now we don't have backend for adding it
 */
function oaklan_carousel($category = 3) {
	$portfolio_posts = get_posts(array('posts_per_page' => wp_count_posts()->publish, 'category' => $category));
	?>
	<div id="oaklan-carousel" class="carousel slide" data-ride="carousel">
	  <!-- Indicators -->
	  <ol class="carousel-indicators">
		<?php
		$ix = 0;
		$rs_image_key = 'image';
		$thereisimage = array();
		foreach ($portfolio_posts as $post) {
			$thereisimage[$ix] = get_post_meta($post->ID, $rs_image_key, true);
			if ($thereisimage[$ix]) {
			?>
				<li data-target="#oaklan-carousel" data-slide-to="<?=$ix?>" <?php if ($ix == 0) : ?>class="active"<?php endif; ?>></li>
				<?php
				$ix++;
			}
		}
		?>
	  </ol>

	  <!-- Wrapper for slides -->
	  <div class="carousel-inner" role="listbox">
			<?php
			$ix = 0;
			$first = false;
			foreach ($portfolio_posts as $post) {
				$post_title = $post->post_title;
				if ($thereisimage[$ix]) {
				?>
					<div class="item <?php if ($first == false) : ?>active<?php endif; ?>">
						<a href="<?=get_permalink($post->ID)?>" title="<?=$post->post_title?>" class="alignleft">
							<img class="alignleft" src="<?=$thereisimage[$ix]?>" alt="<?=$post->post_title?>">
						</a>
						<div class="oaklan-carousel-caption alignleft">
			        <h3><?=$post->post_title?></h3>
							<p><?=oaklan_improved_trim_excerpt($post->post_content)?></p>
			      </div>
						<div class="clear"></div>
			    </div>
					<?php
					$first = true;
				}
				$ix++;
			}
			?>
	  </div>

	  <!-- Controls -->
	  <a class="left carousel-control" href="#oaklan-carousel" role="button" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#oaklan-carousel" role="button" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	    <span class="sr-only">Next</span>
	  </a>
	</div>
	<?php
}
endif;

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'oaklan' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'oaklan' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'oaklan' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'oaklan_the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function oaklan_the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'oaklan' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<span class="nav-previous"><b>&laquo;&nbsp;Previous Post</b><br>%link</span>', '%title' );
				next_post_link( '<span class="nav-next"><b>&raquo;&nbsp;Next Post</b><br>%link</span>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'oaklan_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function oaklan_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( 'Posted on %s', 'post date', 'oaklan' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'by %s', 'post author', 'oaklan' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

}
endif;

if ( ! function_exists( 'oaklan_post_details') ) :
/**
 * Prints minimal post details at the top of single posts
 */
function oaklan_post_details() {
	$categories = get_the_category();
	$separator = ' | ';
	$output = '';
	if($categories){
		foreach($categories as $category) {
			$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
		}
		echo '<span class="left">Filed in ' . trim($output, $separator) . '</span>';
	} else {
		echo '<span class="left"></span>';
	}
	echo '<span class="right"><a href="#respond">';
	comments_number();
	echo '</a></span>';

	echo '<div class="clear"></div>';
}
endif;

if ( ! function_exists( 'oaklan_after_post_details') ) :
/**
 * Prints the after post details on single posts
 */
function oaklan_after_post_details() {
	echo '<span class="left">Posted by ' . get_the_author();
	echo '&nbsp;&nbsp;@&nbsp;&nbsp;' . get_post_time('l, F j, Y') . '</span>';

	echo '<span class="right"><a href="#respond" class="commentcount">';
	comments_number();
	echo '</a></span>';

	echo '<div class="clear"></div>';

	if ( has_tag() ) {
		echo '<span class="left">';
		the_tags();
		echo '</span><div class="clear"></div>';
	}

	if ( ! is_admin() ) {
		echo '<span class="left"></span><span class="right adminedit"><a class="post-edit-link" href="'.get_edit_post_link().'">Edit This Post</a></span>';
		echo '<div class="clear"></div>';
	}
}
endif;

if ( ! function_exists( 'oaklan_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function oaklan_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'oaklan' ) );
		if ( $categories_list && oaklan_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'oaklan' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'oaklan' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'oaklan' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'oaklan' ), __( '1 Comment', 'oaklan' ), __( '% Comments', 'oaklan' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'oaklan' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'oaklan' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'oaklan' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'oaklan' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'oaklan' ), get_the_date( _x( 'Y', 'yearly archives date format', 'oaklan' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'oaklan' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'oaklan' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'oaklan' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'oaklan' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', 'oaklan' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', 'oaklan' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', 'oaklan' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', 'oaklan' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', 'oaklan' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', 'oaklan' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', 'oaklan' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', 'oaklan' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', 'oaklan' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'oaklan' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'oaklan' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'oaklan' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function oaklan_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'oaklan_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'oaklan_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so oaklan_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so oaklan_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in oaklan_categorized_blog.
 */
function oaklan_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'oaklan_categories' );
}
add_action( 'edit_category', 'oaklan_category_transient_flusher' );
add_action( 'save_post',     'oaklan_category_transient_flusher' );
