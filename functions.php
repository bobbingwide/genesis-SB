<?php // (C) Copyright Bobbing Wide 2016-2018

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
	add_filter( 'genesis_noposts_text', "genesis_sb_noposts_text" );
	add_filter( "bw_custom_column_taxonomy", "genesis_sb_bw_custom_column_taxonomy", 10, 3 );
	
	add_action( "genesis_sb_seen_before", "genesis_sb_seen_before" );

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
	$posts = array_merge( $images, $non_images );
	$query->featured_images = count( $images );
	bw_trace2( $query, "query", false );
	bw_trace2( count( $posts ), "count(posts)", false );
	
	return $posts;
}

/**
 * Implements genesis_noposts_text filter
 */
function genesis_sb_noposts_text( $text ) {
	global $sorry_but;
	if ( $sorry_but ) {
		$text = $sorry_but;
	} else {
		$text = "Sorry but, no posts surfaced before I gave up looking. Sobeit.";
	}
	return $text;
}

/**
 * Filters bw_custom_column_taxonomy 
 * 
 */
function genesis_sb_bw_custom_column_taxonomy( $terms, $column, $post_id ) {
	$terms = str_replace( ",", " ", $terms );
	return $terms;
}

/**
 * Returns the featured image count
 *
 * @param integer offset start offset - to allow for a big image at the start 
 * @return integer number of featured images
 */
function genesis_sb_featured_images( $offset ) {
	$images = 0;
	global $wp_query;
	bw_trace2( $wp_query, "wp_query", false );
	if ( property_exists( $wp_query, "featured_images" ) ) {
		$images = $wp_query->featured_images; 
	}
	//echo "Total: " .  $images . PHP_EOL;
	$images = genesis_sb_full_rows( $images, $offset );
	
	//echo "Images: " . $images . PHP_EOL;
	
	return $images;
}

/**
 * Returns the number of full rows
 * 
 * @param integer $total total number of posts with featured images remaining
 * @param integer $entries_per_row number of items in a rows
 * @param integer $maximum_rows we support
 * @return integer  
 */
function genesis_sb_full_rows( $total, $offset=0, $entries_per_row=4, $maximum_rows=4 ) {
	$rows = intdiv( $total-$offset, $entries_per_row );
	//echo $rows;
	$rows = min( $rows, $maximum_rows );
	return ( $rows * $entries_per_row ) + $offset;
}

/**
 * Display the hero banner
 */
function genesis_sb_hero() {
	//echo "<h1>Hero</h1>";
	//genesis_sb_do_post_content();
	printf( '<section %s>', genesis_attr( 'hero' ) );
		do_action( 'genesis_before_entry' );
		printf( '<article %s>', genesis_attr( 'entry' ) );

			do_action( 'genesis_before_entry_content' );
			printf( '<div %s>', genesis_attr( 'hero' ) );
			genesis_do_post_image();
			echo '</div>';
			
			printf( '<div %s>', genesis_attr( 'entry-content' ) );
			do_action( 'genesis_entry_header' );
			do_action( 'genesis_entry_content' );
			do_action( 'genesis_after_entry_content' );
			do_action( 'genesis_entry_footer' );
			do_action( 'genesis_sb_seen_before' );
			echo '</div>';

		echo '</article>';

		do_action( 'genesis_after_entry' );
	echo '</section>';
}

/**
 * Increments _seen_before post meta count
 * 
 * @return integer Number of times seen before
 */
function genesis_sb_increment_seen_before() {
	$_seen_before = 0;
	$post = get_post();
	if ( !$post ) {
		return 0;
	}
	$_seen_before = get_post_meta( $post->ID, '_seen_before', true );
	if ( false === $_seen_before || '' === $_seen_before ) {
		$_seen_before = 0;
	}
	$seen_before = $_seen_before + 1;
	update_post_meta( $post->ID, '_seen_before', $seen_before );
	return $_seen_before;
}

/**
 * Displays "Seen before" information 
 * 
 * For bigrams only?
 * 
 */
function genesis_sb_seen_before() {
	$seen_before = genesis_sb_increment_seen_before();
	echo '<span class="seen-before">';
	_e( 'Seen before ', 'genesis-SB' );
	echo '</span>';
	echo '<span class="seen-before-value">';
	echo number_format_i18n( $seen_before ); 
	echo '</span>';
}

/**
 * Displays images in blocks of 4
 * 
 * With Title (maybe) and Featured image
 */
function genesis_sb_images() {
	printf( '<article %s>', genesis_attr( 'image' ) );
	//do_action( 'genesis_before_entry_content' );
	//echo '<div class="imgwrap">';
	//echo '</div>';
	printf( '<div %s>', genesis_attr( 'entry-content' ) );
	do_action( 'genesis_entry_header' );
	genesis_do_post_image();
	//do_action( 'genesis_entry_content' );
	echo '</div>';
	//do_action( 'genesis_after_entry_content' );
	//do_action( 'genesis_entry_footer' );
	echo '</article>';
	//do_action( 'genesis_after_entry' );
}


/**
 * Display links from now for the rest of the page
 * 
 * 
 */
function genesis_sb_after_images() {
	//do_action( 'genesis_entry_header' );
	$title =  get_the_title();
	$extra = styled_styles( $title );
	echo retlink( null, get_permalink(), $title, null, null, $extra );
	echo " ";
 // genesis_do_post_title();
}


/**
 * Determines hardcoded styling for SB's
 *
 * @param string $sb expected to be an SB
 * @return string the inline style
 */
function styled_styles( $sb ) {
	$words = explode( " ", $sb . 'SS BB' );
	$sword = $words[0];
	$sletter = substr( $sword, 0, 1 );
	 
	$sord = ord( $sletter ) ;
	$bword = $words[1];
	$bletter = substr( $bword, 0, 1 );
	$bord = ord( $bletter ) ;
	$blue = 255 - ( 10 * strlen( $sb ) );
	$alpha = "0.9";
	$style = sprintf( ' style="color: rgba( %1$s, %2$s, %3$s, %4$s );"', $sord, $bord, $blue, $alpha );
	return $style;
}


/**
 * Creates a styled block
 */
function styled_block( $sb ) {
	$classes = "sb ";
	$html = '<div class="'; 
	$html .= $classes;
	$html .= '"';
	$html .= styled_styles( $sb );
	$html .= '>';
	$html .= $sb;
	$html .= '</div>';
	return $html;
}



function genesis_sb_image_default_args( $defaults, $args ) {
	bw_trace2();
	//$sb = get_the_title();
	$html = styled_block( get_the_title() );
	
	$defaults['fallback'] =  array( "html" => $html, "url" => get_permalink() );
	return $defaults;
}

add_filter( "genesis_get_image_default_args", "genesis_sb_image_default_args", 10, 2 );

/**
 * Implement a tighter loop for archives
 * 
 * Basically we don't want any content except the featured image
 * 
 * BUT 
 * one day we might look at {@link https://github.com/desandro/masonry}
 * 
 */
function genesis_sb_do_loop() {
	$count = 0;
	$images = genesis_sb_featured_images( 1 );
	
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			//do_action( 'genesis_before_entry' );
			if ( $count == 0 ) {
				genesis_sb_hero();
			} elseif ( $count < $images  ) {
				genesis_sb_images();
			} elseif ( $count == $images ) {
				printf( '<div %s>', genesis_attr( "links" ) );
				genesis_sb_after_images();
      } else {
				genesis_sb_after_images();
			}
			$count++;
		}
		echo '</div>';
		do_action( 'genesis_after_endwhile' );
	} else {
		do_action( 'genesis_loop_else' );
	}
}

/**
 * Enqueues extra styles
 *
 * @param string
 */
function genesis_sb_enqueue_extra_styles( $styles=array( "archive" ) ) {
	// @TODO Implement a loop
	foreach ( $styles as $style ) {
		genesis_sb_enqueue_extra_style( $style);
	}
}

/**
 * Enqueues an extra stylesheet
 */
function genesis_sb_enqueue_extra_style( $extra_style="search" ) {
	$timestamp = null;
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$timestamp = filemtime( get_stylesheet_directory() . "/" . $extra_style . ".css" );
	}
	wp_enqueue_style( $extra_style . "-css", get_stylesheet_directory_uri() . '/' . $extra_style . '.css', array(), $timestamp );
}

/**
 * Displays an SB page
 * 
 * This common logic is used in the different templates:
 * - archive.php
 * - category.php
 * - date.php
 * - home.php		?
 * - index.php  ?
 * - search.php
 */
function genesis_sb_page() {
 
 
	/*
	 * Output from genesistant
	 *
	 * We don't want either post_content nor post_content_nav
	 * but we do want the image and may need the post permalink
	 * but this should be before the image
	 * 
	 * `
	 * <!--
	 * action genesis_entry_content genesis_loop,genesis_entry_content
	 *
	 * : 8   genesis_do_post_image;1
	 * : 10   genesis_do_post_content;1
	 * : 12   genesis_do_post_content_nav;1
	 * : 14   genesis_do_post_permalink;1--> 
	 */
	remove_action( "genesis_entry_content", "genesis_do_post_image", 8 );
	remove_action( "genesis_entry_content", "genesis_do_post_content", 10 );
	//remove_action( "genesis_entry_content", "genesis_do_post_content_nav", 12 ); 
	//remove_action( "genesis_entry_content", "genesis_do_post_permalink", 14 );
	//add_action( "genesis_entry_content", "genesis_do_post_permalink", 9 );
 
	// Not necessary to remove these hooks if we don't invoke the action
 
	//remove_action( "genesis_entry_footer", 'genesis_oik_post_info' );
	//remove_action( "genesis_entry_footer", "genesis_entry_footer_markup_open", 5);
	//remove_action( "genesis_entry_footer", "genesis_entry_footer_markup_close", 15 );
	//remove_action( "genesis_entry_header", "genesis_do_post_format_image", 4 );

	remove_action( "genesis_loop", "genesis_do_loop" );
	add_action( "genesis_loop", "genesis_sb_do_loop" );


	//add_action( "genesis_after_footer", "genesis_oik_after_footer" );
	add_action( "wp_enqueue_scripts", "genesis_sb_after_footer" );

	// add_action( "genesis_after_endwhile", "genesis_oik_a2z", 9 );


	genesis();
}





