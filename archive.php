<?php // (C) Copyright Bobbing Wide 2018

/**
 * Enqueue special styles for archives
 */
function genesis_sb_after_footer() {
	genesis_sb_enqueue_extra_style();
}


add_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description' );
genesis_sb_page();

