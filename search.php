<?php
/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package genesis-SB
 * @author  @bobbingwide
 * @license GPL-2.0+
 * @link    https://github.com/bobbingwide/genessis-SB
 */

add_action( 'genesis_before_loop', 'genesis_do_search_title' );

/**
 * Echo the title with the search term.
 *
 * @since 1.9.0
 */
function genesis_do_search_title() {
	//echo __FILE__; 
	$title = sprintf( '<div class="archive-description"><h1 class="archive-title">%s %s</h1></div>', apply_filters( 'genesis_search_title_text', __( 'Search Results for:', 'genesis' ) ), get_search_query() );
	echo apply_filters( 'genesis_search_title_output', $title ) . "\n";
}

/**
 * Enqueues special styles for archives
 */
function genesis_sb_after_footer() {
	genesis_sb_enqueue_extra_style();
}
genesis_sb_page();
