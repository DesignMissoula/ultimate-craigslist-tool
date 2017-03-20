<?php

/*
Plugin Name: Ultimate Craigslist Tool
Plugin URI: http://dropzone.ml/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of one man.
Author: Bradford Knowlton
Version: 1.6
Author URI: http://bradknowlton.com/
*/


add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    
    remove_action( 'catchresponsive_footer', 'catchresponsive_footer_content', 100 );
	add_action( 'catchresponsive_footer', 'nss_footer_content', 100 );

}

add_action( 'init', 'register_cpt_cities' );

function register_cpt_cities() {
	
	
	 $labels = array( 
        'name' => _x( 'State', 'state' ),
        'singular_name' => _x( 'State', 'state' ),
        'search_items' => _x( 'Search States', 'state' ),
        'popular_items' => _x( 'Popular States', 'state' ),
        'all_items' => _x( 'All States', 'state' ),
        'parent_item' => _x( 'Parent State', 'state' ),
        'parent_item_colon' => _x( 'Parent State:', 'state' ),
        'edit_item' => _x( 'Edit State', 'state' ),
        'update_item' => _x( 'Update State', 'state' ),
        'add_new_item' => _x( 'Add New State', 'state' ),
        'new_item_name' => _x( 'New State', 'state' ),
        'separate_items_with_commas' => _x( 'Separate states with commas', 'state' ),
        'add_or_remove_items' => _x( 'Add or remove state', 'state' ),
        'choose_from_most_used' => _x( 'Choose from the most used state', 'state' ),
        'menu_name' => _x( 'States', 'state' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => false,
        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'state', array('city'), $args );

    $labels = array( 
        'name' => _x( 'Region', 'region' ),
        'singular_name' => _x( 'Region', 'region' ),
        'search_items' => _x( 'Search Regions', 'region' ),
        'popular_items' => _x( 'Popular Regions', 'region' ),
        'all_items' => _x( 'All Regions', 'region' ),
        'parent_item' => _x( 'Parent Region', 'region' ),
        'parent_item_colon' => _x( 'Parent Region:', 'region' ),
        'edit_item' => _x( 'Edit Region', 'region' ),
        'update_item' => _x( 'Update Region', 'region' ),
        'add_new_item' => _x( 'Add New Region', 'region' ),
        'new_item_name' => _x( 'New Region', 'region' ),
        'separate_items_with_commas' => _x( 'Separate regions with commas', 'region' ),
        'add_or_remove_items' => _x( 'Add or remove region', 'region' ),
        'choose_from_most_used' => _x( 'Choose from the most used region', 'region' ),
        'menu_name' => _x( 'Regions', 'region' ),
    );

    $args = array( 
        'labels' => $labels,
        'public' => true,
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => false,
        'hierarchical' => false,
        'rewrite' => true,
        'query_var' => true
    );

    register_taxonomy( 'region', array('city'), $args );

    $labels = array( 
        'name' => _x( 'Cities', 'city' ),
        'singular_name' => _x( 'City', 'city' ),
        'add_new' => _x( 'Add New', 'city' ),
        'add_new_item' => _x( 'Add New City', 'city' ),
        'edit_item' => _x( 'Edit City', 'city' ),
        'new_item' => _x( 'New City', 'city' ),
        'view_item' => _x( 'View City', 'city' ),
        'search_items' => _x( 'Search Cities', 'city' ),
        'not_found' => _x( 'No cities found', 'city' ),
        'not_found_in_trash' => _x( 'No cities found in Trash', 'city' ),
        'parent_item_colon' => _x( 'Parent City:', 'city' ),
        'menu_name' => _x( 'Cities', 'city' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'editor',  'thumbnail', 'custom-fields' ), // 'excerpt',
        'taxonomies' => array( 'state' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        'menu_icon' => 'dashicons-admin-site',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array( 'slug' => 'local/%region%/%state%'),
        'capability_type' => 'post'
    );

    register_post_type( 'city', $args );
    
     remove_action( 'admin_notices', 'woothemes_updater_notice' );
}


function wpa_course_post_link( $post_link, $id = 0 ){
    $post = get_post($id);  
    if ( is_object( $post ) ){
        $terms = wp_get_object_terms( $post->ID, 'state' );
        if( $terms ){
            $post_link = str_replace( '%state%' , $terms[0]->slug , $post_link );
        }
    }
    if ( is_object( $post ) ){
        $terms = wp_get_object_terms( $post->ID, 'region' );
        if( $terms ){
            $post_link = str_replace( '%region%' , $terms[0]->slug , $post_link );
        }
    }
    return $post_link;  
}
add_filter( 'post_type_link', 'wpa_course_post_link', 1, 3 );


function my_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    register_cpt_cities();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );

// returns the content of $GLOBALS['post']
// if the page is called 'debug'
function city_the_content_filter($content) {
  global $post;		
  if( is_single() && 'city' == $post->post_type ){
  		
  		$url_base = get_post_meta( $post->ID, 'link_base', true );
  		$city = get_post_meta( $post->ID, 'city_name', true );
  		$state = get_post_meta( $post->ID, 'state_name', true );

  		$url = sprintf('%s/search/mca',$url_base);
  		$rss = sprintf('%s?format=rss', $url);

  		$content = sprintf('<div class="cleafix"><h3>%s, %s</h3><p><a href="%s" target="_BLANK">See All Autos For Sale</a></p><hr/></div>',ucwords($city), ucwords($state), $url);

  		$content .= '<div class="widget kopa-masonry-list-1-widget">
	<div class="masonry-list-wrapper">';

  		$args = array('show_summary' => 1, 'show_date' => 1);

  		// later, you in your theme
		ob_start();
  		city_content_rss_output($rss, $args);
  		$content .= ob_get_clean();

  		$content .= '</div>
  		</div>';
  }

  // otherwise returns the database content
  return $content;
}

add_filter( 'the_content', 'city_the_content_filter' );


function city_content_rss_output( $rss, $args = array() ) {
    if ( is_string( $rss ) ) {
        $rss = fetch_feed($rss);
    } elseif ( is_array($rss) && isset($rss['url']) ) {
        $args = $rss;
        $rss = fetch_feed($rss['url']);
    } elseif ( !is_object($rss) ) {
        return;
    }
 
    if ( is_wp_error($rss) ) {
        if ( is_admin() || current_user_can('manage_options') )
            echo '<p>' . sprintf( __('<strong>RSS Error</strong>: %s'), $rss->get_error_message() ) . '</p>';
        return;
    }
 
    $default_args = array( 'show_author' => 0, 'show_date' => 0, 'show_summary' => 0, 'items' => 0 );
    $args = wp_parse_args( $args, $default_args );
 
    $items = (int) $args['items'];
    if ( $items < 1 || 20 < $items )
        $items = 100;
    $show_summary  = (int) $args['show_summary'];
    $show_author   = (int) $args['show_author'];
    $show_date     = (int) $args['show_date'];
 
    if ( !$rss->get_item_quantity() ) {
        echo '<ul><li>' . __( 'An error has occurred, which probably means the feed is down. Try again later.' ) . '</li></ul>';
        $rss->__destruct();
        unset($rss);
        return;
    }
 
    echo '<ul id="beat_mix_lite_loadmore_placeholder" class="clearfix">';
    foreach ( $rss->get_items( 0, $items ) as $item ) {
        $link = $item->get_link();
        while ( stristr( $link, 'http' ) != $link ) {
            $link = substr( $link, 1 );
        }
        $link = esc_url( strip_tags( $link ) );
 
        $title = esc_html( trim( strip_tags( $item->get_title() ) ) );
        if ( empty( $title ) ) {
            $title = __( 'Untitled' );
        }
 
        $desc = $item->get_description();
        $desc = nl2br( $desc );
 
        $summary = '';
        if ( $show_summary ) {
            $summary = $desc;
 
            $summary = '<div class="rssSummary">' . $desc . '</div>';
        }
 
        $date = '';
        if ( $show_date ) {
            $date = $item->get_date( 'U' );
 
        }
 
        $author = '';
        if ( $show_author ) {
            $author = $item->get_author();
            if ( is_object($author) ) {
                $author = $author->get_name();
                $author = ' <cite>' . esc_html( strip_tags( $author ) ) . '</cite>';
            }
        }

        $thumbnail = '';


// array(1) {
//   [0]=>
//   array(5) {
//     ["data"]=>
//     string(0) ""
//     ["attribs"]=>
//     array(1) {
//       [""]=>
//       array(2) {
//         ["resource"]=>
//         string(58) "http://images.craigslist.org/00x0x_5azyfiF6s5g_300x300.jpg"
//         ["type"]=>
//         string(10) "image/jpeg"
//       }
//     }
//     ["xml_base"]=>
//     string(0) ""
//     ["xml_base_explicit"]=>
//     bool(false)
//     ["xml_lang"]=>
//     string(0) ""
//   }
// }
        $thumbnail_url = '';

        if( $thumbnail = $item->get_item_tags('http://purl.oclc.org/net/rss_2.0/enc#','enclosure') ){
         	$thumbnail_url = $thumbnail[0]['attribs']['']['resource'];
        }
		
		// echo "<li class='masonry-item'><article class='entry-item'><a class='rsswidget' href='$link'>$title</a>{$date}{$summary}{$author}</article></li>"; 
		?>
			<li class="masonry-item">

					<article <?php post_class(array('entry-item', 'entry-item')); ?>>
					  <div class="entry-content">
					      <header>
					      	<span class="entry-date"><?php echo esc_attr(human_time_diff($date)); ?> ago</span>
					      </header>

					      <h3 class="entry-title">
					      	<a href="<?php echo $link; ?>"><?php echo $title; ?></a>
					      </h3>

					      <?php echo $desc; ?>					      
					  </div>

					  <?php if( $thumbnail_url ): ?>
						  <div class="entry-thumb">
					      <a href="<?php echo $link; ?>">
					      	<img src="<?php echo $thumbnail_url; ?>" class="img-responsive" />
					      </a>
					      <div class="mask"><a href="#"><i class="fa fa-plus"></i></a></div>
						  </div>
						<?php endif;?>
					</article>
				</li>

		<?php


    }
    echo '</ul>';
    $rss->__destruct();
    unset($rss);
}

