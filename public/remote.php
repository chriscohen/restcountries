<?php
/**
 * @file
 * Respond to API queries by searching the API and returning responses in JSON format.
 */

use NNV\RestCountries;

/**
 * These are the API calls that will be made using the specified term.
 */
define('SEARCHES', [
    'Name',
    'Codes',
    'CapitalCity',
    'Currency',
    'Language',
]);

require_once('../vendor/autoload.php');

// Make sure we have a "query" in the querystring, or bail out.
if (empty($_GET['query'])) {
    // HTTP 400 bad request.
    http_response_code(400);
    die();
}

// URL-decode and remove any spaces from the query term.
$term = urldecode($_GET['query']);
$term = str_replace(' ', '', $term);

$restCountries = new RestCountries();
$output = [];

foreach (SEARCHES as $searchType) {
    // Turn the search type into a valid method name such as 'byName'.
    $methodName = 'by' . $searchType;

    try {
        $response = $restCountries->$methodName($term);
    } catch (Exception $e) {
        $response = [];
    }

    $output = array_merge($output, $response);
}

// Turn the arary back to JSON for output.
$response = json_encode($output);

print $response;
