<?php // (C) Copyright Bobbing Wide 2017, 2018

/**
 * Page template: sb
 * 
 * All we want to do here is to deliver the react-SB JavaScript
 * and a div with id='root' into which the JavaScript is run
 * and style it a bit.
 * 
 * @TODO We might want to turn off some of the widgets in the header and footer
 *  
 * Note: react-SB.js and react-SB.css are copied from the [github bobbingwide react-SB]
 */

get_header();

unregister_sidebar( 'footer-1' );
unregister_sidebar( 'footer-2' );

?>
<div id='root'></div>
<?php

$st = genesis_SB_react_update(); 

wp_register_script( "react-SB", CHILD_URL . "/js/react-SB.js", array(), $st );
wp_enqueue_script( "react-SB" ); 
wp_enqueue_style( "react-SB", CHILD_URL . "/css/react-SB.css", array(), $st );

get_footer();

/**
 * Update our files from the react-SB repository
 * 
 * If the react-SB/public directory exists
 * /apache/htdocs/react-SB/public
 */ 

function genesis_SB_react_update() {
	//echo ABSPATH . PHP_EOL;
	$upabit = dirname( ABSPATH );
	$react_SB_public = $upabit . '/react-SB/public';
	//echo $react_SB_public . PHP_EOL;
	if ( is_dir( $react_SB_public ) ) {
		$st1 = genesis_SB_react_update_maybe_copy( $react_SB_public, __DIR__, "/bundle.js", "/js/react-SB.js" );
		$st2 = genesis_SB_react_update_maybe_copy( $react_SB_public, __DIR__, "/css/react-SB.css", "/css/react-SB.css" );
	}	else {
		$st1 = filemtime( __DIR__ . "/js/react-SB.js" );
		$st2 = filemtime( __DIR__ . "/css/react-SB.css" );
	}
	$st = max( $st1, $st2 );
	return( $st );
}

/**
 * Copy a file if necessary
 * 
 * Copy the source file to the target file if newer
 * returning the timestamp of the most recent file
 * 
 * We always expect both files to be present, so we should be happy with warning.
 * 
 */
function genesis_SB_react_update_maybe_copy( $source_dir, $target_dir, $source_file, $target_file ) {
	$source_time = filemtime( $source_dir . $source_file );
	$target_time = filemtime( $target_dir . $target_file );
	if ( $source_time > $target_time ) {
		copy( $source_dir . $source_file, $target_dir . $target_file );
		p( "File refreshed from source" );
		p( "$source_dir $source_file $source_time $target_time" );
	}
	return( $source_time );
}
	



/**
 * Template file for the react-SB version
 *
 * We can probably get away without any genesis() logic for the body
 * `
  
<!DOCTYPE html>
<head> 
<link rel="icon" href="http://www.bigram.co.uk/wp-content/uploads/2016/05/cropped-SB-bigrams-32x32.jpg" sizes="32x32" />
<link rel="icon" href="http://www.bigram.co.uk/wp-content/uploads/2016/05/cropped-SB-bigrams-192x192.jpg" sizes="192x192" />
<link rel="apple-touch-icon-precomposed" href="http://www.bigram.co.uk/wp-content/uploads/2016/05/cropped-SB-bigrams-180x180.jpg" />
<meta name="msapplication-TileImage" content="http://www.bigram.co.uk/wp-content/uploads/2016/05/cropped-SB-bigrams-270x270.jpg" />
</head>

<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <div id='root'>
    </div>
    <script src="bundle.js"></script>
  </body>
</html>
* `
*/
//add_theme_support( 'html5' );



// Remove post info
//remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

// Remove breadcrumbs
//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

// Remove the entry meta in the entry footer. i.e. Remove the Filed Under:
//remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
//add_action( 'genesis_entry_footer', 'genesis_sb_post_meta' );

//bw_disable_filter( 'genesis_edit_post_link', 
//remove_action( 'genesis_edit_post_link', 
//remove_action( 'genesis_before_post_content', 'genesis_post_info' );

// Remove post info
//remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
//add_action( 'genesis_entry_footer', 'genesis_post_info' );


//add_action( 'genesis_entry_footer', 'genesis_sb_post_info' );

// Put the image before the rest of the content.
//add_action( 'genesis_entry_content', 'genesis_sb_do_entry_content', 9 );
//add_action( "genesis_entry_content", "genesis_sb_do_post_title", 9 );

//remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
//add_action( 'genesis_after_content', 'genesis_oik_get_sidebar' );

//add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );



