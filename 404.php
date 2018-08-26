<?php
/**
 * Specially Built 404 page
 *
 /**
 * @copyright (C) Copyright Bobbing Wide 2018
 * @package genesis-SB
 * @author  @bobbingwide
 * @license GPL-2.0+
 * @link    https://github.com/bobbingwide/genesis-SB
 */
 
include_once( get_stylesheet_directory() . '/lib/searchor404.php' );

// Remove default loop.
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'genesis_sb_404' );
/**
 * This function outputs a 404 "Not Found" error message.
 *
 * @since 1.6
 */
function genesis_sb_404() {
	if ( !is_404() ) {
		return;
	}

	genesis_markup( array(
		'open'    => '<article class="entry">',
		'context' => 'entry-404',
	) );

	genesis_markup( array(
		'open'    => '<h1 %s>',
		'close'   => '</h1>',
		'content' => apply_filters( 'genesis_404_entry_title', __( 'So basically... Not found, error 404', 'genesis' ) ),
		'context' => 'entry-title',
	) );

	echo '<div class="entry-content">';
	
	//$explanation = __( 'This site contains a lot of stuff. But not what you asked for.', 'genesis-sb' );
	
	//$explanation = apply_filters( 'genesis_404_entry_content', '<p>' . $explanation . '</p>' );
	//echo $explanation;
	
	
	genesis_sb_analyze_query();
	

	/*
	if ( genesis_a11y( '404-page' ) ) {
		echo '<h2>' . esc_html__( 'Sitemap', 'genesis' ) . '</h2>';
		//genesis_sitemap( 'h3' );
	} else {
		//genesis_sitemap( 'h4' );
	}
	*/

	echo '</div>';

	genesis_markup( array(
		'close'   => '</article>',
		'context' => 'entry-404',
	) );

}

/**
 * Analyze the query
 *
 * Note: The bigram plugin may already have modified the request to set the post_type to bigram and name to Sword-Bword format.
 * So we don't have to do it again. 
 * We can treat it like a search that failed to find anything.
 */
function genesis_sb_analyze_query() {
	
	global $wp_query;
	//print_r( $wp_query );
	
	$name = get_query_var( "name" );
	$post_type = get_query_var( "post_type" );
	
	//echo $name;
	//echo $post_type;
	if ( $post_type == "bigram" ) {
		$name = strtolower( $name );
		$words = explode( "-", $name );
		$swords = get_words_starting( $words, "s", "s-word" );
		$bwords = get_words_starting( $words, "b", "b-word" );
		$is_sb_query = is_sb_query( $words, $swords, $bwords );
		if ( $is_sb_query ) {
			genesis_sb_404_consider_terms( $swords, $bwords );
		}
	}
	$explanation = "<p>"; 
	$explanation .= __( 'This site contains a lot of stuff. But not what you asked for.', 'genesis-sb' );
	$explanation .= "<br />";
	$explanation .= genesis_sb_noposts_text( '' );
	$explanation .= "</p>";
	echo $explanation;
}

/**
 * Considers the terms in the 404 
 * 
 * We've already determined that the request was for an SB, but it was not found
 * 
 */
function genesis_sb_404_consider_terms( $swords, $bwords ) {
	bw_trace2();
	$sword = current( $swords );
	$bword = current( $bwords );
	printf( '<br />Considering terms: %1$s %2$s', $sword, $bword );
	
	$sterm = genesis_sb_get_term( "S-word", $sword, "s-word" ); 
	$bterm = genesis_sb_get_term( "B-word", $bword, "b-word" );
	
	if ( $sterm && $bterm && $sterm->count && $bterm->count ) {
		//print_r( $sterm );
		if ( is_user_logged_in() ) {
			//genesis_sb_create_seen_before( $sword, $bword, $sterm, $bterm );
			genesis_sb_sorry_but( "Selected bigram should be created." );
		} else {
			genesis_sb_sorry_but( "You need to be logged in to automatically create sampled bigrams." );
		}
	} else {
		genesis_sb_sorry_but( "This doesn't qualify for automatic creation of an SB." );
	}
	
	
}


													 


genesis();
