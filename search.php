<?php
/**
 * WordPress User Page
 *
 * Handles authentication, registering, resetting passwords, forgot password,
 * and other user handling.
 *
 * @package WordPress
 */

/** Make sure that the WordPress bootstrap has run before continuing. */
require __DIR__ . '/wp-load.php';

$sites = [
	"Apple"  => 'Apple fruit', "Banana" => 'Banana fruit', "Orange" => "Orange fruit", "Mango" => "Mango fruit",
	"Grapes" => "Grapes fruit", "Banas" => "Banas fruit",
];


function getData( $sites ) {
	if ( $_SERVER["REQUEST_METHOD"] === "GET" && isset( $_GET['search'] ) ) {
		$searchTerm     = $_GET['search'];
		$filteredFruits = array_filter( $sites, function ( $fruit ) use ( $searchTerm ) {
			return stripos( $fruit, $searchTerm ) !== false;
		} );

		if ( ! empty( $filteredFruits ) ) {
			foreach ( $filteredFruits as $fruit ) {
				echo $fruit . "<br>";
			}
		} else {
			echo "No results found for '$searchTerm'.";
		}
	} else {
		// Return all fruits if no search term is provided
		foreach ( $sites as $fruit ) {
			echo $fruit . "<br>";
		}
	}
}

getData( $sites );