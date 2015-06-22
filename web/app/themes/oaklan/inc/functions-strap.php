<?php

/**
 * Bootstrap menu class injection
 */
function bootstrap_menu_objects($sorted_menu_items, $args)
{
    if($args->theme_location == 'primary')
    {//
        $current = array('current-menu-ancestor', 'current-menu-item');
        $registry = array();
        foreach($sorted_menu_items as $i => $item) {
            $is_current = array_intersect( (array) $item->classes, $current );
            if ( !empty($is_current) ) $item->classes[] = 'active';
            $registry[$item->ID] = $i;
            if($item->menu_item_parent) {
                $parent_index = $registry[$item->menu_item_parent];
                if( !in_array('dropdown', $sorted_menu_items[$parent_index]->classes) ) {
                    $sorted_menu_items[$parent_index]->classes[] = 'dropdown';
                }
            }
        }
        //print_r($sorted_menu_items);print_r($args);exit;
    }
    return $sorted_menu_items;
}
add_filter( 'wp_nav_menu_objects', 'bootstrap_menu_objects', 10, 2 );


/**
 * Custom Bootstrap Walker
 */
class Bootstrap_Nav_Menu extends Walker_Nav_Menu {
    /**
     * @see Walker::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of page. Used for padding.
     */
    function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param int $current_page Menu item ID.
     * @param object $args
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        if(is_array($args)) $args = json_decode(json_encode($args)); // convert to object
        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $dropdown = in_array('dropdown', $classes);
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
        if($depth > 0) $class_names = str_replace('dropdown', 'dropdown-submenu', $class_names);

        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $value . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $attributes .= $dropdown                    ? ' class="dropdown-toggle" data-toggle="dropdown" data-target="#"' : '';

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        if($dropdown && $depth == 0) $item_output .= ' <b class="caret"></b>';
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}

/**
 * Bootstrap styled Caption shortcode.
 * Hat tip: http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
add_filter( 'img_caption_shortcode', 'bootstrap_img_caption_shortcode', 10, 3 );

function bootstrap_img_caption_shortcode( $output, $attr, $content )  {

    /* We're not worried abut captions in feeds, so just return the output here. */
    if ( is_feed() )  return '';

    extract(shortcode_atts(array(
                'id'	=> '',
                'align'	=> 'alignnone',
                'width'	=> '',
                'caption' => ''
            ), $attr));

    if ( 1 > (int) $width || empty($caption) )
        return $content;

    if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

    return '<div ' . $id . 'class="thumbnail ' . esc_attr($align) . '">'
        . do_shortcode( $content ) . '<div class="caption">' . $caption . '</div></div>';
}

/**
 * Bootstrap styled Comment form.
 */
add_filter( 'comment_form_defaults', 'bootstrap_comment_form_defaults', 10, 1 );

function bootstrap_comment_form_defaults( $defaults )
{
    /*    */

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $defaults['fields'] =  array(
        'author' => '<div class="form-group comment-form-author">' .
                '<label for="author" class="col-sm-3 control-label">' . __( 'Name', 'oaklan' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                '<div class="col-sm-9">' .
                    '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"  class="form-control"' . $aria_req . ' />' .
                '</div>' .
            '</div>',
        'email'  => '<div class="form-group comment-form-email">' .
                '<label for="email" class="col-sm-3 control-label">' . __( 'Email', 'oaklan' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                '<div class="col-sm-9">' .
                    '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"  class="form-control"' . $aria_req . ' />' .
                '</div>' .
            '</div>',
        'url'    => '<div class="form-group comment-form-url">' .
            '<label for="url" class="col-sm-3 control-label"">' . __( 'Website', 'oaklan' ) . '</label>' .
                '<div class="col-sm-9">' .
                    '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '"  class="form-control" />' .
                '</div>' .
            '</div>',
    );
    $defaults['comment_field'] = '<div class="form-group comment-form-comment">' .
        '<label for="comment" class="col-sm-3 control-label">' . _x( 'Comment', 'noun', 'oaklan' ) . '</label>' .
            '<div class="col-sm-9">' .
                '<textarea id="comment" name="comment" aria-required="true" class="form-control" rows="8"></textarea>' .
                '<span class="help-block form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</span>' .
           '</div>' .
        '</div>';

    $defaults['comment_notes_after'] = '<div class="form-group comment-form-submit">';

    return $defaults;
}
add_action( 'comment_form', 'bootstrap_comment_form', 10, 1 );

function bootstrap_comment_form( $post_id )
{
    // closing tag for 'comment_notes_after'
    echo '</div><!-- .form-group .comment-form-submit -->';
}


function bootstrap_searchform_class( $bt = array() )
{
    $caller = basename($bt[1]['file'], '.php');
    switch($caller) {
        case 'header':
            return 'navbar-form navbar-right';
        default:
            return 'form-inline';
    }
}

add_filter( 'embed_oembed_html', 'bootstrap_oembed_html', 10, 4 );

function bootstrap_oembed_html( $html, $url, $attr, $post_ID )
{
    return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
}

/**
 * port of the original non-RWD zenverse blacktribe's wp backend customisation,
 * i.e. front page portfolio slider and thumbnail imgs per post with excerpts
 * hats off http://themearea.com/2009/11/blacktribe-dark-portfolio-free-wordpress-theme-free-premium/
 *
 */

/* custom field at add/edit post */
$oaklan_custom_field['thumbnail'] = array(
 "type" => "text",
 "name" => "thumbnail",
 "label" => "Post Thumbnail",
 "std" => "",
 "desc" => "Upload & paste the direct url to the Post Thumbnail here.<br />Size : 125 x 125 px",
 "help" => "",
);
$oaklan_custom_field['image'] = array(
 "type" => "text",
 "name" => "image",
 "label" => "Post Image",
 "std" => "",
 "desc" => "Upload & paste the direct url to the Post Image here.<br />Size : 550 x 190 px",
 "help" => "",
);

function oaklan_customfields_content() {
 	global $post,$oaklan_custom_field;
 	echo '<table width="100%" cellpadding="0" cellspacing="0">'."\n";
 	foreach ($oaklan_custom_field as $single_customfield) {
 		$customfieldvalue = get_post_meta($post->ID,$single_customfield["name"],true);
 		if ($customfieldvalue == '' || !isset($customfieldvalue)) {
 			$customfieldvalue = $single_customfield['std'];
 		}
 		echo '<tr>';
 		echo '<th style="text-align: right;padding-top:10px;padding-right:10px;"><label>'.$single_customfield['label'].':</label>';
     if ($single_customfield["help"] != '') {
     echo '<p><small>'.$single_customfield["help"].'</small></p>';
     }
 		echo '</th><td style="padding-top:10px;"><input size="40" type="'.$single_customfield['type'].'" value="'.$customfieldvalue.'" name="oaklan_'.$single_customfield["name"].'" onblur="if(this.value!=\'\')document.getElementById(\'preview_of_'.$single_customfield["name"].'\').innerHTML=\'<center><img src=\\\'\'+this.value+\'\\\' width=\\\'70\\\' height=\\\'70\\\' style=\\\'border:1px solid #dddddd;padding:2px;\\\' /></center>\';" id="oaklan_customfield_'.$single_customfield['name'].'" /></td>'."\n";
 		echo '</tr>';
 		echo '<tr><td style="border-bottom:1px dotted #aaaaaa;padding-left:5px"><div id="preview_of_'.$single_customfield["name"].'">';
     if ($customfieldvalue!='') { echo '<center><img src="'.$customfieldvalue.'" width="70" height="70" style="border:1px solid #dddddd;padding:2px;" /></center>'; }
     echo '</div></td><td style="border-bottom:1px dotted #aaaaaa;padding:5px"><small>'.$single_customfield['desc'].'</small></td></tr>';
 	}
 	echo '</table>';
}

function oaklan_customfields_insert($postID) {
 	global $oaklan_custom_field;
 	foreach ($oaklan_custom_field as $single_customfield) {
 		$var = "oaklan_".$single_customfield["name"];
 		if (isset($_POST[$var])) {
 			if( get_post_meta( $postID, $single_customfield["name"] ) == "" )
 				add_post_meta($postID, $single_customfield["name"], $_POST[$var], true );
 			elseif($_POST[$var] != get_post_meta($postID, $single_customfield["name"], true))
 				update_post_meta($postID, $single_customfield["name"], $_POST[$var]);
 			elseif($_POST[$var] == "")
 				delete_post_meta($postID, $single_customfield["name"], get_post_meta($postID, $single_customfield["name"], true));
 		}
 	}
}

function oaklan_the_post($post_object) {
  $rs_image_key = 'thumbnail';
  $thumbnail = get_post_meta($post_object->ID, $rs_image_key, true);
  if ($thumbnail) {
    $post_object->img_prepend = '<img src="'.$thumbnail.'" class="alignleft" />';
  }
}

function oaklan_meta_box() {
  if ( function_exists('add_meta_box') ) {
    add_meta_box('oaklan-settings', wp_get_theme()->get('Name').' Post Thumbnail & Featured Image', 'oaklan_customfields_content', 'post', 'normal');
  }
}

add_action('admin_menu', 'oaklan_meta_box');
add_action('wp_insert_post', 'oaklan_customfields_insert');
add_action('the_post', 'oaklan_the_post' );
