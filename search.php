<?php
/**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package genesis-SB
 * @author  @bobbingwide
 * @license GPL-2.0+
 * @link    https://github.com/bobbingwide/genessis-SB
 */

add_action( 'genesis_before_loop', 'genesis_sb_do_search_title' );
add_action( 'genesis_before_loop', 'genesis_sb_seen_before' );

/**
 * Echo the title with the search term.
 *
 * @since 1.9.0
 */
function genesis_sb_do_search_title() {
	//echo __FILE__; 
	$title = sprintf( '<div class="archive-description"><h1 class="archive-title">%s %s</h1></div>', apply_filters( 'genesis_search_title_text', __( 'Search Results for:', 'genesis' ) ), get_search_query() );
	echo apply_filters( 'genesis_search_title_output', $title ) . "\n";
}


/**
 * Analyses the search for SBs that may have been seen before
 * 
 * Processing depends on the search and the results
 * 
 * search | results | processing
 * ------ | ------- | ------------------
 * SB     | 0       | Create a new post in category "Seen before?" 
 * SB     | 1       | Is it a match? 
 * SB     | > 1     | Is it a match?
 * not SB | ?       | Why not search for words starting with S or B ?
 * 
 */
function genesis_sb_seen_before() {
	if ( !is_search() ) {
		return;
	}
	echo '<div class="search-banter">';
	
	$terms = genesis_sb_get_terms();
	$swords = get_words_starting( $terms, "s", "s-word" );
	$bwords = get_words_starting( $terms, "b", "b-word" );
	$is_sb_query = is_sb_query( $terms, $swords, $bwords );
	if ( $is_sb_query ) {
		global $wp_query;
		if ( $wp_query->post_count > 0 ) {
			// something found
			genesis_sb_check_first_post( $swords, $bwords );
		} else {
			// Nothing found
			// Look for combinations
			genesis_sb_consider_terms( $swords, $bwords );
		}
	
	} else {
		// We don't really care if it's not an SB query
	}
	echo '</div>';

}

/**
 * Gets search terms
 * 
 * @return array of the escaped words in the search
 */
function genesis_sb_get_terms() {
	$terms = get_search_query();
	//echo $terms;
	
	$terms = trim( $terms );
	$terms = strtolower( $terms );
	$array = explode( " ", $terms );
	return $array;
}

/**
 * Extracts terms starting with the given letter
 *
 * @param array $terms - array of lower case terms
 * @param string $letter - the lower case first letter
 * @param string $taxonomy - the s-word or b-word taxonomy name
 */ 
function get_words_starting( $terms, $letter, $taxonomy ) {
	$words = array();
	foreach ( $terms as $word ) {
		if ( substr( $word, 0, 1 ) == $letter ) {
			$words[ $word ] = $word;
		}
	}
	return $words;
}

function genesis_sb_sorry_but( $text ) {
	global $sorry_but;
	$sorry_but = $text;
}

/**
 * 
 */
function genesis_sb_consider_terms( $swords, $bwords ) {
	bw_trace2();
	$sword = current( $swords );
	$bword = current( $bwords );
	printf( '<br />Considering terms: %1$s %2$s', $sword, $bword );
	
	$sterm = get_term_by( "slug", $sword, "s-word" );
	$bterm = get_term_by( "slug", $bword, "b-word" );
	
	echo sprintf( '<br />Found S-word %1$s %2$d times', $sword, $sterm->count ); 
	echo sprintf( '<br />Found B-word %1$s %2$d times', $bword, $bterm->count ); 
	
	if ( $sterm && $bterm && $sterm->count && $bterm->count ) {
		//print_r( $sterm );
		if ( is_user_logged_in() ) {
			genesis_sb_create_seen_before( $sword, $bword, $sterm, $bterm );
		} else {
			genesis_sb_sorry_but( "You need to be logged in to automatically create searched bigrams" );
		}
	} else {
		genesis_sb_sorry_but( "This doesn't qualify for automatic creation of an SB" );
	}
}

function genesis_sb_create_post_content( $sword, $bword, $sterm, $bterm ) {
	$content = sprintf( '<!--more-->Seen before as %1$s and %2$s.', $sword, $bword );
	return $content;
}

function genesis_sb_title_text( $sword, $bword ) {
	$title_text = ucfirst( $sword );
	$title_text .= " ";
	$title_text .= ucfirst( $bword );
	return $title_text;
}	

/**
 * Create a new bigram where the terms have been seen before
 * 
 * Category: Seen before?
 */
function genesis_sb_create_seen_before( $sword, $bword, $sterm, $bterm ) {
	$title_text = genesis_sb_title_text( $sword, $bword );
	$post_content = genesis_sb_create_post_content( $sword, $bword, $sterm, $bterm );  
	$post = array( "post_type" => "bigram"
							 , "post_title" => $title_text
							 , "post_name" => $title_text
							 , "post_status" => "publish"
							 , "post_content" => $post_content
							 , "post_author" => 1
							);
	$id = wp_insert_post( $post, true );
	$metadesc = "{$title_text} bigram";
	update_post_meta( $id, "_yoast_wpseo_metadesc", $metadesc );
	update_post_meta( $id, "_yoast_wpseo_focuskw", $metadesc );
	
	//echo "Created $id for {$this->title_text}" . PHP_EOL;
		
	return $id;
		
}

/**
 * See if we've got a perfect match
 * then perhaps report on when it was last searched for
 * and how many times displayed.
 */


function genesis_sb_check_first_post( $swords, $bwords ) {
	$sword = current( $swords );
	$bword = current( $bwords );
	$post = get_post();
	echo "<br />Checking first post: " . $post->post_title;
	//print_r( $post );
	if ( $post->post_title == genesis_sb_title_text( $sword, $bword ) ) {
		echo "<br />Satisfied by...";	
	}
	
	
	
}



/**
 * Determines if it's an SB query
 * 
 * Count | S words | B words
 * ----- | ------- | -------
 * 
 * 
 */ 
function is_sb_query( $terms, $swords, $bwords ) {
	$is_sb_query = false;
	$lookup = count( $terms );
	$lookup .= count( $swords );
	$lookup .= count( $bwords );
	$messages = array();
	$messages[ "211" ] = "Searching Beautifully...";
	$messages[ "110" ] = "How about a B word too?";
	$messages[ "101" ] = "How about searching for an S word as well?";
	// $messages[ "100" ] = 
	// $messages[ "200" ] = 
	$message = bw_array_get( $messages, $lookup, "Try to search for an S-word a B-word or both" );
	//echo "<br />";
	echo $message;
	if ( $lookup == "211" ) {
		$is_sb_query = true;
	} 
	return $is_sb_query;
}

/**
 * Enqueues special styles for archives
 */
function genesis_sb_after_footer() {
	genesis_sb_enqueue_extra_style();
}
genesis_sb_page();
