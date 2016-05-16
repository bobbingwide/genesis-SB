<?php // (C) Copyright Bobbing Wide 2016

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
	define( 'CHILD_THEME_VERSION', '0.0.0' );
	
	// Start the engine	- @TODO Is this necessary?
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

	//* Add support for 5-column footer widgets - requires extra CSS
	add_theme_support( 'genesis-footer-widgets', 3 );

	add_filter( 'genesis_footer_creds_text', "genesis_sb_footer_creds_text" );
	
  //add_filter( 'genesis_pre_get_option_site_layout', 'genesis_oik_pre_get_option_site_layout', 10, 2 );
	
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	add_action( 'genesis_entry_footer', 'genesis_sb_post_meta' );
	
	// Remove post info
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	add_action( 'genesis_entry_footer', 'genesis_sb_post_info' );
	//add_filter( "genesis_edit_post_link", "__return_false" );
	
  //genesis_oik_register_sidebars();
	
	//genesis_oik_edd();

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

  $filtered = apply_filters( 'genesis_post_meta', "[bw_field category id=$ID] [bw_field s id=$ID] [bw_field b id=$ID]" );
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


