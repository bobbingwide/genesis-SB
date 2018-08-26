<?php 

 /**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package genesis-SB
 * @author  @bobbingwide
 * @license GPL-2.0+
 * @link    https://github.com/bobbingwide/genesis-SB
 */

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

/**
 * Determines if it's an SB query
 * 
 * Echoes a message indicating its opinion on the search
 * 
 * @param array $terms complete array of terms ( escaped )
 * @param array $swords array of S-words
 * @param array $bwords array of B-words
 * @return bool True if the number of terms is two, one's an S-word and the other's a B-word. False otherwise. 
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
 * Get the term for the given taxonomy
 * 
 * @param string $label Label for the term
 * @param string $word the lower case term word
 * @param string $taxonomy taxonomy name
 * @return term|null the term object found
 */
function genesis_sb_get_term( $label, $word, $taxonomy ) {

	$term = get_term_by( "slug", $word, $taxonomy );
	if ( $term ) {
		echo '<br />';
		$times = _n( 'Found %1$s %2$s once', 'Found %1$s %2$s %3$s times', $term->count, "genesis-SB" );
		printf( $times, $label, $word, $term->count ); 
	}
	return $term;
}

/**
 * Sets the $sorry_but prefix
 */
function genesis_sb_sorry_but( $text ) {
	global $sorry_but;
	$sorry_but = $text;
}

