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
require __DIR__ . '/../wp-load.php';

function check_options( $site ): array {
	switch ( $site ) {
		case get_option( 'us_woocommerce_catalog_mode' ) === 'no':
			return [
				'name' => $site->domain, 'value' => get_option( 'us_woocommerce_catalog_mode' ), 'message' => 'LIVE',
			];
		case $option_value = get_option( 'us_store_setup' ) === '1':
			return [
				'name'    => $site->domain, 'value' => $option_value,
				'message' => 'Dashboard - Not pushed Live Button Yet',
			];
		case $option_value = get_option( 'us_banking_setup' ) === '1':
			return [ 'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 7 - Store Setup' ];
		case $option_value = get_option( 'us_products_setup' ) === '1':
			return [
				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 5 - Banking setup',
			];
		case $option_value = get_option( 'us_shipping_setup' ) === '1':
			return [
				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 6 - Product setup',
			];
		case $option_value = get_option( 'us_partnership_setup' ) === '1':
			return [
				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 4 - Shipping setup',
			];
		case $option_value = get_option( 'us_customize_setup' ) === '1':
			return [
				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 3 - Partnership setup',
			];
		case $option_value = get_option( 'us_slides_setup' ) === '1':
			return [
				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 2 - Customizer setup',
			];
		case $option_value = get_option( 'us_departments_setup' ) === '1':
			return [ 'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 1 - Banner setup' ];
		// Add more cases as needed
		default:
			return [
				'name' => $site->domain, 'value' => 'yes', 'message' => 'This retailer haven\'t started the setup yet',
			];
	}
}

function get_current_live_blogs( $page = 1, $per_page = 10 ) {
	// Get a list of all sites in the network
	$sites       = get_sites();
	$total_sites = count( $sites );

	// Calculate the start and end indexes based on the requested page and items per page
	$start_index = ( $page - 1 ) * $per_page;
	$end_index   = min( $start_index + $per_page, $total_sites );

	// Initialize an array to store results for the current page
	$results_array = [];
	$i             = 0;

	// Iterate through each site for the current page
	if ( $sites ) {
		foreach ( $sites as $index => $site ) {
			if ( $index < $start_index || $index >= $end_index ) {
				continue; // Skip sites outside the current page range
			}
			switch_to_blog( $site->blog_id ); // Switch to the current site's context
			if ( $site->blog_id === '1' ) {
				continue;
			}

			// Process the site data
			$results_array[ $i ]            = check_options( $site );
			$results_array[ $i ]['email']   = get_blog_option( $site->blog_id, 'admin_email' );
			$results_array[ $i ]['blog_id'] = $site->blog_id;
			$i ++;

			restore_current_blog(); // Restore the previous site's context
		}
	}

	// Return the results for the current page along with total pages
	$total_pages = ceil( $total_sites / $per_page );

	return (object) [
		'results' => $results_array, 'total_pages' => $total_pages, 'total_sites' => $total_sites,
	];
}

function dummy_data( $page = 1, $per_page = 10 ): object {
	// Define an array to hold the results
	$resultsArray = [];

	// Messages for the "message" field
	$messages = [
		'Dashboard - Not pushed Live Button Yet', 'LIVE', 'Stuck in step 5 - Banking setup',
		'Stuck in step 6 - Product setup', 'Stuck in step 4 - Shipping setup', 'Stuck in step 3 - Partnership setup',
		'Stuck in step 2 - Customizer setup', 'Stuck in step 1 - Banner setup',
		'This retailer haven\'t started the setup yet',
	];

	// Calculate start and end indexes for pagination
    $total_results= 30;
	$start_index = ( $page - 1 ) * $per_page;
	$end_index   = $start_index + $per_page;

	// Generate random results based on pagination indexes
	for ( $i = $start_index; $i < $end_index && $i < $total_results; $i ++ ) {
		$name         = substr( md5( rand() ), 0, 10 ) . '.giftsite.local'; // Generate a random name
		$randomValue  = rand( 0, 2 ); // Random value (0 or 1)
		$messageIndex = array_rand( $messages ); // Random index for selecting a message
		$message      = $messages[ $messageIndex ]; // Get a random message from the array

		if ( $randomValue === 0 ) {
			$value = 0;
		} elseif ( $randomValue === 1 ) {
			$value = 1;
		} else {
			$value   = 'no';
			$message = 'LIVE';
		}

		// Create an array with random values and add it to the results array
		$resultsArray[] = [
			'name' => $name, 'value' => $value, 'message' => $message,
		];
	}

	// Test output to display the generated array
	return (object) [ 'results' => $resultsArray, 'total_sites' => $total_results ];
}


//echo '<pre>: ' . print_r( get_current_live_blogs(), TRUE ) . '</pre>';return;
// Check if the AJAX request has been made
if ( isset( $_POST['get-current-live-blogs'] ) && $_POST['get-current-live-blogs'] === 'true' ) {
	// Get the requested page and per_page from the AJAX request
	$page     = isset( $_POST['page'] ) ? intval( $_POST['page'] ) : 1;
	$per_page = isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : 10;
	// Get the results for the specified page and per_page
	$results = get_current_live_blogs($page, $per_page);
//	$results = dummy_data( $page, $per_page );

	// Encode the array as JSON and return it
	echo json_encode( $results );
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Get Current Live Blogs</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            text-align: center;
        }

        #loading, #results, #result {
            margin: 0 auto;
            text-align: center;
        }

        #results {
            display: flex;
            justify-content: center;
        }

        .pagination-btn {
            margin: 5px;
            padding: 5px 10px;
            cursor: pointer;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<h1>Get Current GiftSite Live Blogs</h1>
<button id="get-live-blogs-button">Get blog status</button>
<!--<button id="get-live-blogs">Get only lives</button>-->
<div>
    <div id="processing"></div>
    <br/>
    <div id="loading" style="display: none;">
        <progress value="0" max="100" id="progress"></progress>
        <div id="progress-text">0%</div>
    </div>
    <br/>
    <div id="results"></div>
    <br/>
    <div id="pagination-buttons"></div>
    <button id="prev-btn">Previous</button>
    <button id="next-btn">Next</button>

    <br/>
    <div id="result"></div>
</div>

<script>
    let resultsArray = [];
    let currentPage = 1; // Set the initial current page
    let totalPages = 0;
    const resultsPerPage = 10; // Set the number of results per page


    const updateProgress = ( progress ) => {
        $( '#progress' ).val( progress );
        $( '#progress-text' ).text( progress + '%' );
    }

    /**
     * Filter results by value (currently filtering only liveSites and catalogSites)
     * @returns {Promise<{liveSites: [], catalogSites: []}>}
     */
    const filterResults = async () => {
        const results = resultsArray;
        let liveSites = [];
        let catalogSites = [];

        for ( let i = 0; i < results.length; i++ ) {
            const value = results[ i ].value;

            if ( value === 'no' ) {
                liveSites.push( { ...results[ i ] } );
            } else {
                catalogSites.push( { ...results[ i ] } );
            }
        }

        return { liveSites, catalogSites };
    }

    const outputTable = ( liveSites, catalogSites ) => {
        const table = $( '<table>' ).css( {
            'border-collapse': 'collapse',
            'border': '1px solid black'
        } );

        const headerRow = $( '<tr>' ).css( 'border', '1px solid black' );
        headerRow.append( $( '<th>' ).css( {
            'border': '1px solid black',
            'padding': '8px'
        } ).text( 'GiftSites' ) );
        table.append( headerRow );

        const maxRows = Math.max( liveSites.length, catalogSites.length );

        for ( let i = 0; i < maxRows; i++ ) {
            const row1 = $( '<tr>' );
            const row2 = $( '<tr>' );

            if ( liveSites[ i ] !== undefined ) {
                const col1 = $( '<td>' ).css( {
                    'border': '1px solid black',
                    'padding': '8px'
                } ).html( liveSites[ i ] );
                row1.append( col1 );
            }
            if ( catalogSites[ i ] !== undefined ) {
                const col2 = $( '<td>' ).css( {
                    'border': '1px solid black',
                    'padding': '8px'
                } ).html( catalogSites[ i ] );
                row2.append( col2 );
            }
            table.append( row1, row2 );
        }
        $( '#results' ).html( table );
    }

    //refactor and use another one for filtered results with liveSitesArray and catalogSitesArray cause it can't be doing 2 jobs
    const showFormattedResults = async ( results ) => {
        let liveSites = [];
        let catalogSites = [];

        for ( let i = 0; i < results.length; i++ ) {
            const name = results[ i ].name;
            const value = results[ i ].value;
            const message = results[ i ].message;
            const email = results[ i ].email;
            if ( value === 'no' ) {
                liveSites.push( `<span style="color:green">${ name }</span>: <br/><span style="font-size:12px;">${ message }</span><br/><span style="font-weight:bold;">${ email }</span>` );
            } else {
                catalogSites.push( `<span style="color:red">${ name }</span>: <br/><span style="font-size:12px;">${ message }</span><br/><span style="font-weight:bold;">${ email }</span>` );
            }
        }

        outputTable( liveSites, catalogSites );
    }

//for use to calculate results etc
    const displayResultsProgress = async ( results = [] ) => {
        const totalIterations = resultsArray.length;
        let iterations = 0;
        if ( !results ) {
            results = resultsArray;
        }

        const interval = setInterval( async () => {
            const slicedResults = resultsArray.slice( startIdx, endIdx );

            await separateResults( slicedResults );

            iterations++;
            const progress = Math.round( ( iterations / totalIterations ) * 100 );
            updateProgress( progress );

            if ( iterations >= totalIterations ) {
                clearInterval( interval );
                $( '#loading' ).hide();
                $( '#results' ).show();
                await paginatedResults( results, resultsPerPage );
            }
        }, 100 );
    }


    const clearAllResults = () => {
        // $( '#results, #result' ).empty();
        // $( '#results, #pagination-buttons' ).hide();
        // $( '#loading' ).show();
        // $( '#progress' ).val( 0 );
        // $( '#progress-text' ).text( '0%' );
    }
    // Function to load results for a specific page
    const loadResultsForPage = async ( page ) => {
        clearAllResults();
        const results = await $.ajax( {
            url: window.location.href,
            type: 'POST',
            data: {
                'get-current-live-blogs': 'true',
                'page': page,
                'per_page': resultsPerPage
            },
        } );

        resultsArray = JSON.parse( results );
        totalPages = resultsArray.total_sites;
        await showFormattedResults( resultsArray.results );
    };

    // Click event for previous button
    $( '#prev-btn' ).click( async function () {
        if ( currentPage > 1 ) {
            currentPage--;
            await loadResultsForPage( currentPage );
        }
    } );

    // Click event for next button
    $( '#next-btn' ).click( async function () {
        // Assuming you have the total number of pages available
        const totals = getTotalPages( totalPages, resultsPerPage ); // Replace getTotalPages() with the actual function to get the total pages

        if ( currentPage < totals ) {
            currentPage++;
            await loadResultsForPage( currentPage );
        }
    } );

    function getTotalPages( totalPages, itemsPerPage ) {
        return Math.ceil( totalPages / itemsPerPage );
    }

    // Function to display the current page number and total pages
    const updatePaginationInfo = () => {
        const totals = getTotalPages( totalPages, resultsPerPage ); // Pass the totalItems and resultsPerPage
        $( '#pagination-info' ).text( `Page ${ currentPage } of ${ totals }` );
    };

    // Initial load of results for the first page
    $( '#get-live-blogs-button' ).click( async function () {
        currentPage = 1;
        await loadResultsForPage( currentPage );
    } );

    // Initial display of pagination info
    updatePaginationInfo();
</script>