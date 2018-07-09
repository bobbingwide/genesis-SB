<?php // (C) Copyright Bobbing Wide 2016, 2017

genesis_sb_functions_loaded();

/**
 * Function to invoke when genesis-oik is loaded
 * 
 * Register the hooks for this theme
 */
function genesis_sb_functions_loaded() {

	//* Child theme (do not remove) - is this really necessary? 
	define( 'CHILD_THEME_NAME', 'Specially Built' );
	define( 'CHILD_THEME_URL', 'http://www.bobbingwide.com/oik-themes/genesis-SB' );
	//define( 'CHILD_THEME_VERSION', '0.0.0' );
	
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		$timestamp = filemtime( get_stylesheet_directory() . "/style.css" );
		define( 'CHILD_THEME_VERSION', $timestamp );
	} else { 
		define( 'CHILD_THEME_VERSION', '0.0.2' );
	}
	
	// Start the engine	- This is necessary since otherwise remove_action's may be ineffective
	include_once( get_template_directory() . '/lib/init.php' );
	
	//* Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	//* Add viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );
	
	// Add support for structural wraps
	add_theme_support( 'genesis-structural-wraps', array(
	 'header',
	//	'nav',
	//        'subnav',
		'site-inner'
	) );

	//* Add support for 3-column footer widgets - requires extra CSS - see genesis-footer-widgets plugin
	add_theme_support( 'genesis-footer-widgets', 3 );

	add_filter( 'genesis_footer_creds_text', "genesis_sb_footer_creds_text" );
	
  //add_filter( 'genesis_pre_get_option_site_layout', 'genesis_oik_pre_get_option_site_layout', 10, 2 );
	
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	add_action( 'genesis_entry_footer', 'genesis_sb_post_meta' );
	
	// Remove post info
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	add_action( 'genesis_entry_footer', 'genesis_sb_post_info' );
	//add_filter( "genesis_edit_post_link", "__return_false" );
	
	remove_action( "genesis_entry_content", "genesis_do_post_content" );
	add_action( "genesis_entry_content", "genesis_sb_do_post_content", 10 );
  //genesis_oik_register_sidebars();
	
	//genesis_oik_edd();
	add_filter( "the_posts", "genesis_sb_the_posts", 10, 2 );

}

/**
 * Display footer credits for the Specially Built theme
 */	
function genesis_sb_footer_creds_text( $text ) {
	do_action( "oik_add_shortcodes" );
	$text = "[bw_wpadmin]";
  $text .= '<br />';
	$text .= "[bw_copyright]"; 
	$text .= '<hr />';
	$text .= 'Website designed and developed by [bw_link text="Herb Miller" herbmiller.me] of';
	$text .= ' <a href="//www.bobbingwide.com" title="Bobbing Wide - web design, web development">[bw]</a>';
	$text .= '<br />';
	$text .= '[bw_power] and oik-plugins';
  return( $text );
}

/**
 * Display the post info in our style
 *
 * We only want to display the post date and post modified date
 * plus the post_edit link. 
 * Note: The post edit link may appear multiple times
 *
 */
function genesis_sb_post_info() {
	$output = genesis_markup( array(
    'html5'   => '<p %s>',
    'xhtml'   => '<div class="post-info">',
    'context' => 'entry-meta-before-content',
    'echo'    => false,
	) );
	$string = sprintf( __( 'Published %1$s', 'genesis-SB' ), '[post_date]' );
	$string .= '<span class="splitbar">';
	$string .= ' | ';
	$string .= '</span>';
	$string .= sprintf( __( 'Last updated %1$s', 'genesis-SB' ), '[post_modified_date]' );
  $string .= ' [post_edit]';
	$output .= apply_filters( 'genesis_post_info', $string);
	$output .= genesis_html5() ? '</p>' : '</div>';  
	echo $output;
}

/**
 * Display the post meta in our style
 * 
 * This comes before post info
 * 
 */
function genesis_sb_post_meta() {

  //if ( ! post_type_supports( get_post_type(), 'genesis-entry-meta-after-content' ) ) {
  //  return;
  //}
	$post = get_post();
	$ID = $post->ID;

  $filtered = apply_filters( 'genesis_post_meta', "[bw_field category id=$ID] [bw_field s-word id=$ID] [bw_field b-word id=$ID]" );
  if ( empty( $filtered ) ) {
    return;
  }

  $output = genesis_markup( array(
    'html5'   => '<p %s>',
    'xhtml'   => '<div class="post-meta">',
    'context' => 'entry-meta-after-content',
    'echo'    => false,
  ) );

  $output .= $filtered;
  $output .= genesis_html5() ? '</p>' : '</div>';

  echo $output;

}

/**
 * Echo a comment
 *
 * @param string $string the text to echo inside the comment
 */
if ( !function_exists( "_e_c" ) ) { 
function _e_c( $string ) {
	echo "<!--\n";
	echo $string;
	echo "-->";
}
}

/**
 * Display the attached image and title
 * 
 * Here we assume that there is a featured image
 *
 */
function genesis_sb_do_entry_content() {

	$img = genesis_get_image( array(
					'format'  => 'html',
					'size'    => genesis_get_option( 'image_size' ),
					'context' => 'archive',
					'attr'    => genesis_parse_attr( 'entry-image' ),
					) );

	if ( !empty( $img ) ) {
		echo $img;
	}   else  {
		//echo "Not got it yet";
	}
}

/**
 * Display the post title 
 *
 */
function genesis_sb_do_post_title() {
	$title = get_the_title();
	$output = '<div class="entry-title">';
	$output .= $title;
	$output .= '</div>';
	echo $output;
}

/** 
 * Display the post content
 *
 * Simpler than genesis_do_post_content() 
 */
function genesis_sb_do_post_content() {
	$output = '<div class="post-content">';
	$content = get_the_content( "more" );
	$content = apply_filters( "the_content", $content );
	$output .= $content;
	$output .= '</div>';
	echo $output;
}

/**
 * Displays the A to Z pagination
 */
function genesis_oik_a2z() {
	sdiv();
	h3( "s-words" );
	bw_flush();
	do_action( "oik_a2z_display", "s-letter" );
	h3( "b-words" );
	bw_flush();
	do_action( "oik_a2z_display", "b-letter" );
	ediv();
	bw_flush();
}

/**
 * Filters the_posts 
 *
 * Is this a good idea? Filter the posts to get the ones with attached images first.
 * How do we mark the posts that have attached images?
 * 
 * @param array $posts
 * @param object $query
 * @return array reordered array of posts
 */										
function genesis_sb_the_posts( $posts, $query ) {
	bw_trace2( count( $posts ), "count(posts)", false );
	bw_trace2( $query, "query", false );
	
	$images = array();
	$non_images = array();
	foreach ( $posts as $post ) {
		$thumbnail = get_post_thumbnail_id( $post );
		if ( $thumbnail > 0 ) {
			bw_trace2( $thumbnail, "post: ". $post->ID, false ); 
			$images[] = $post;
			
		} else {
			$non_images[] = $post;
		}
	}
	bw_trace2( $images, "images: " . count( $images ), false );
	bw_trace2( $non_images, "non_images: " . count( $non_images ), false );
	$posts = $images + $non_images;
	
	
	return $posts;
}


