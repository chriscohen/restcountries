<?php
// Autoload packages using composer's PSR-4 autoloader.
require_once '../vendor/autoload.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RestCountries API implementation</title>

        <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>

    <body class="bg-light">
        <div class="container">
            <div class="py-5 text-center">
                <h2>RestCountries API integration</h2>
                <p>
                    Please use the keyword field below to search for a country name, code, capital city, currency code
                    or name, or language.
                </p>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <input type="text" name="input" class="typeahead"/>
                </div>
            </div>
        </div>

        <script src="js/jquery-3.4.1.js" integrity="sha384-mlceH9HlqLp7GMKHrj5Ara1+LvdTZVMx4S1U43/NxCvAkzIo8WJ0FE7duLel3wVo" crossorigin="anonymous"></script>
        <script src="js/typeahead.bundle.js" integrity="sha384-up5m4qUNHDA0trts45bnm/JBBOfOMbOKtm/uAUX17yitl3RroI3RbrzmkWKBPT3w" crossorigin="anonymous"></script>
        <script src="js/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
            // Set up a Bloodhound to include prefetch, for all countries we already know about, and remote, to get
            // countries from the API if we don't have them yet.
            let countries = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: '/prefetch.php',
                remote: {
                    url: '/remote.php?query=%QUERY',
                    wildcard: '%QUERY'
                }
            });

            $('.typeahead').typeahead(null, {
                name: 'countries',
                display: 'value',
                source: countries
            });
        </script>
    </body>
</html>
