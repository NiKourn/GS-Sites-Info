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

//function check_options( $site ): array {
//	switch ( $site ) {
//		case get_option( 'us_woocommerce_catalog_mode' ) === 'no':
//			return [
//				'name' => $site->domain, 'value' => get_option( 'us_woocommerce_catalog_mode' ), 'message' => 'LIVE',
//			];
//		case $option_value = get_option( 'us_store_setup' ) === '1':
//			return [
//				'name'    => $site->domain, 'value' => $option_value,
//				'message' => 'Dashboard - Not pushed Live Button Yet',
//			];
//		case $option_value = get_option( 'us_banking_setup' ) === '1':
//			return [ 'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 7 - Store Setup' ];
//		case $option_value = get_option( 'us_products_setup' ) === '1':
//			return [
//				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 5 - Banking setup',
//			];
//		case $option_value = get_option( 'us_shipping_setup' ) === '1':
//			return [
//				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 6 - Product setup',
//			];
//		case $option_value = get_option( 'us_partnership_setup' ) === '1':
//			return [
//				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 4 - Shipping setup',
//			];
//		case $option_value = get_option( 'us_customize_setup' ) === '1':
//			return [
//				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 3 - Partnership setup',
//			];
//		case $option_value = get_option( 'us_slides_setup' ) === '1':
//			return [
//				'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 2 - Customizer setup',
//			];
//		case $option_value = get_option( 'us_departments_setup' ) === '1':
//			return [ 'name' => $site->domain, 'value' => $option_value, 'message' => 'Stuck in step 1 - Banner setup' ];
//		// Add more cases as needed
//		default:
//			return [
//				'name' => $site->domain, 'value' => 'yes', 'message' => 'This retailer haven\'t started the setup yet',
//			];
//	}
//}

//function get_current_live_blogs1(): array {
//	// Get a list of all sites in the network
//	$sites = get_sites();
////echo '<pre>: ' . print_r( $sites, TRUE ) . '</pre>';die();
//	// Initialize an array to store results
//	$results_array = [];
//	$i             = 0;
//
//	// Iterate through each site
//	if ( $sites ) {
//		foreach ( $sites as $site ) {
//			switch_to_blog( $site->blog_id ); // Switch to the current site's context
//			if ( $site->blog_id === '1' ) {
//				continue;
//			}
//
//			$results_array[ $i ]            = check_options( $site );
//			$results_array[ $i ]['email']   = get_blog_option( $site->blog_id, 'admin_email' );
//			$results_array[ $i ]['blog_id'] = $site->blog_id;
//			$i ++;
//			restore_current_blog(); // Restore the previous site's context
//		}
//	}
//
//	// Return the results
//	return $results_array;
//}
//
//function get_current_live_blogs3($page = 1, $per_page = 2) {
//	// Get a list of all sites in the network
//	$sites = get_sites();
//	$total_sites = count($sites);
//
//	// Calculate the start and end indexes based on the requested page and items per page
//	$start_index = ($page ) * $per_page;
//	$end_index = min($start_index + $per_page, $total_sites);
//
//	// Initialize an array to store results for the current page
//	$results_array = [];
//	$i = 0;
//
//	// Iterate through each site for the current page
//	if ($sites) {
//		foreach ($sites as $index => $site) {
//			if ($index < $start_index || $index >= $end_index) {
//				continue; // Skip sites outside the current page range
//			}
//
//			switch_to_blog($site->blog_id); // Switch to the current site's context
//			if ($site->blog_id === '1') {
//				continue;
//			}
//
//			// Process the site data
//			$results_array[$i] = check_options($site);
//			$results_array[$i]['email'] = get_blog_option($site->blog_id, 'admin_email');
//			$results_array[$i]['blog_id'] = $site->blog_id;
//			$i++;
//
//			restore_current_blog(); // Restore the previous site's context
//		}
//	}
//
//	// Return the results for the current page along with total pages
//	$total_pages = ceil($total_sites / $per_page);
//	return (object)[
//		'results' => $results_array,
//		'total_pages' => $total_pages
//	];
//}
function dummy_data(): array {

// Define an array to hold the results
	$resultsArray = [];

// Messages for the "message" field
	$messages = [
		'Dashboard - Not pushed Live Button Yet', 'LIVE', 'Stuck in step 5 - Banking setup',
		'Stuck in step 6 - Product setup', 'Stuck in step 4 - Shipping setup', 'Stuck in step 3 - Partnership setup',
		'Stuck in step 2 - Customizer setup', 'Stuck in step 1 - Banner setup',
		'This retailer haven\'t started the setup yet',
	];

// Generate 20 random results
	for ( $i = 0; $i < 80; $i ++ ) {
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
	return $resultsArray;
}

// Check if the AJAX request has been made
if ( isset( $_POST['get-current-live-blogs'] ) && $_POST['get-current-live-blogs'] === 'true' ) {

	// Get the results as an array
	$results = get_current_live_blogs()->results;
//	$results = dummy_data();

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
<button id="get-live-blogs">Get only lives</button>
<div>
    <div id="processing"></div>
    <br/>
    <div id="loading" style="display: none;">
        <progress value="0" max="100" id="progress"></progress>
        <div id="progress-text">0%</div>
    </div>
    <br/>
    <div id="results" style="display: none;"></div>
    <br/>
    <div id="pagination-buttons"></div>
    <br/>
    <div id="result"></div>
</div>

<script>
    let resultsArray = [];
    const resultsPerPage = 10; // Set results per page here

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
    const separateResults = async ( results, page, resultsPerPage ) => {
        const startIdx = ( page - 1 ) * resultsPerPage;
        const endIdx = startIdx + resultsPerPage;
        const slicedResults = results.slice( startIdx, endIdx );
        let liveSites = [];
        let catalogSites = [];

        for ( let i = 0; i < slicedResults.length; i++ ) {
            const name = slicedResults[ i ].name;
            const value = slicedResults[ i ].value;
            const message = slicedResults[ i ].message;
            const email = slicedResults[ i ].email;

            if ( value === 'no' ) {
                liveSites.push( `<span style="color:green">${ name }</span>: <br/><span style="font-size:12px;">${ message }</span><br/><span style="font-weight:bold;">${ email }</span>` );
            } else {
                catalogSites.push( `<span style="color:red">${ name }</span>: <br/><span style="font-size:12px;">${ message }</span>` );
            }
        }

        outputTable( liveSites, catalogSites );
    }

    const paginatedResults = async ( results, resultsPerPage ) => {
        const totalPages = Math.ceil( results.length / resultsPerPage );
        let currentPage = 1;
        await separateResults( results, currentPage, resultsPerPage );

        function renderPaginationButtons( currentPage, totalPages ) {
            $( '#pagination-buttons' ).empty();
            for ( let i = 1; i <= totalPages; i++ ) {
                const button = $( `<button class="pagination-btn">${ i }</button>` );
                button.on( 'click', async function () {
                    currentPage = i;
                    await separateResults( results, currentPage, resultsPerPage );
                } );
                $( '#pagination-buttons' ).append( button ).show();
            }
        }

        renderPaginationButtons( currentPage, totalPages );
    }

    const displayResultsProgress = async ( results = [] ) => {
        const totalIterations = resultsArray.length;
        let iterations = 0;
        if ( !results ) {
            results = resultsArray;
        }

        const interval = setInterval( async () => {
            const startIdx = iterations * resultsPerPage;
            const endIdx = startIdx + resultsPerPage;
            const slicedResults = resultsArray.slice( startIdx, endIdx );

            await separateResults( slicedResults, iterations + 1, resultsPerPage );

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

    $( '#get-live-blogs-button' ).click( async function () {
        clearAllResults();
        const results = await $.ajax( {
            url: window.location.href,
            type: 'POST',
            data: { 'get-current-live-blogs': 'true' },
        } );

        resultsArray = JSON.parse( results );

        await displayResultsProgress( resultsArray );
    } );


    $( '#get-live-blogs' ).click( async function () {
        clearAllResults();
        const results = await $.ajax( {
            url: window.location.href,
            type: 'POST',
            data: { 'get-current-live-blogs': 'true' },
        } );

        resultsArray = JSON.parse( results );

        const { liveSites } = await filterResults();
        await displayResultsProgress( liveSites );
    } );

</script>
</body>
</html>
