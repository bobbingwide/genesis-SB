<?php // (C) Copyright Bobbing Wide 2017

/**
 * All we want to do here is to deliver the react-SB JavaScript
 * and a div with id='root' into which the JavaScript is run
 * and style it a bit.
 * 
 * @TODO We might want to turn off some of the widgets in the header and footer
 *  
 * Note: react-SB.js is copied from [github bobbingwide react-SB public/bundle.js]
 */

get_header();

?>
<div id='root'></div>
<?php
wp_register_script( "react-SB", CHILD_URL . "/js/react-SB.js" );
wp_enqueue_script( "react-SB" ); 
wp_enqueue_style( "react-SB", CHILD_URL . "/css/react-SB.css", array() );

get_footer();


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



