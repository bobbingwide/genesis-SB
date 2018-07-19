<?php // (C) Copyright Bobbing Wide 2015-2018
//_e_c( __FILE__ );

/**
 * Template file for the bigram type
 *
 * We do want:
 * - Title
 * - Attached images
 * - the content ( after the excerpt? )
 * - the create date and modification date with Edit link
 * - Sidebar
 * - Post info from meta data
 
 * 
 *
 * We don't want:
 * - Published by
 * - Breadcrumbs
 * - Filed Under:
 *
 * Found out how to do this by using {@link genesis.wp-a2z.org}
 * and oik-bwtrace and good old grep.
 */
add_theme_support( 'html5' );

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
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
//add_action( 'genesis_entry_footer', 'genesis_post_info' );


add_action( 'genesis_entry_footer', 'genesis_sb_seen_before', 9 );
// No need to add it again! 

//add_action( 'genesis_entry_footer', 'genesis_sb_post_info',  );

// Put the image before the rest of the content.
add_action( 'genesis_entry_content', 'genesis_sb_do_entry_content', 9 );
//add_action( "genesis_entry_content", "genesis_sb_do_post_title", 9 );

//remove_action( 'genesis_after_content', 'genesis_get_sidebar' );
//add_action( 'genesis_after_content', 'genesis_oik_get_sidebar' );

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_content_sidebar' );



genesis();
