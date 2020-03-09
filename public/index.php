<?php
// Autoload packages using composer's PSR-4 autoloader.
require_once '../vendor/autoload.php';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>RestCountries API implementation</title>

        <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-GpZ+McEis3rxQ2mOiwBzW8NQ7NvjXIsQsIRqV/8QJ2qDR4vdZSe0GrxMctJv74R7" crossorigin="anonymous">
        <link rel="stylesheet" href="css/app.css"/>
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
            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="card flex-md-row box-shadow" id="result" style="display: none;">
                        <div class="card-body d-flex flex-column align-items-start">
                            <h3 id="country-name">Nigeria</h3>
                            <div class="text-muted" id="region">Africa</div>
                            <div class="table-responsive mt-2">
                                <table class="table table-striped table-sm">
                                    <tbody>
                                        <tr>
                                            <td>Calling Code</td>
                                            <td>+<span id="calling-code">234</span></td>
                                        </tr>
                                        <tr>
                                            <td>Capital</td>
                                            <td id="capital">Abuja</td>
                                        </tr>
                                        <tr>
                                            <td>Timezones</td>
                                            <td id="timezones">UTC+01:00</td>
                                        </tr>
                                        <tr>
                                            <td>Currencies</td>
                                            <td id="currencies">Nigerian naira</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <img id="flag" class="card-img-right flex-auto d-none d-md-block" style="max-width: 300px;"/>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/jquery-3.4.1.js" integrity="sha384-EK6R63hAXaEX5y24UMgRsVRAnE868K+H6JXpKWyK/rE8c0CSjQg/aJKUFodKGLkw" crossorigin="anonymous"></script>
        <script src="js/typeahead.bundle.js" integrity="sha384-wWj2d5hmrsgC1uD08vmE+XB9SZqNOpnFBAl7Vg+fGFzDBTJ68SUE3YKEzNz39B5y" crossorigin="anonymous"></script>
        <script src="js/popper.min.js" integrity="sha384-MgX1msxO2MvLB8cnXqSF6XlqfQl+nuvf1p6ELnCmsfLb6aOEi/KW65/L8jpkABRT" crossorigin="anonymous"></script>
        <script src="js/bootstrap.min.js" integrity="sha384-/VDWkH4VukNJ5zgo0o3hx2681xuIAUYQq4hQprhdx0GnptNk7gk9kqH8yv6tEcwM" crossorigin="anonymous"></script>
        <script src="js/app.js"></script>
    </body>
</html>
