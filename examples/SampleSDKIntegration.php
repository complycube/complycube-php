<?php

namespace Example;

require_once __DIR__ . '/vendor/autoload.php';

use ComplyCube\ApiClient;
use ComplyCube\ComplyCubeClient;
use ComplyCube\Model\ComplyCubeCollection;
use ComplyCube\Model\PersonDetails;
use ComplyCube\Model\CompanyDetails;
use ComplyCube\Model\Client;

$apiKey = '<API KEY>';
$complycube = new ComplyCubeClient($apiKey);
$result = $complycube->clients()->create(['type' => 'person',
    'email' => 'email@domain.com',
    'personDetails' => ['firstName' => 'John',
        'lastName' => 'Smith']]);

$clientId = $result->id;
$result = $complycube->tokens()->generate($clientId, '*://*/*');
$token = $result->token;
?>
<html lang='en_us'>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


        <!-- Place this in your </head> tag -->
        <script src="complycube.min.js"></script>
        <link rel="stylesheet" href="style.css"/>

    </head>

    <script>
        var complycube = {};

        function startVerification() {

            complycube = ComplyCube.mount({
                token: "<?php echo trim($token); ?>",
                onModalClose: function () { 
                    complycube.updateSettings({ isModalOpen: false });
                },
                onComplete: function (data) {
                    console.log("Capture complete");
                    console.log(data);
                },
                onError: function ({ type, message }) {
                    console.log("Error");
                    console.log(type);
                },
                onExit: function(reason) {
                    console.log(reason);
                },
            });
            
        }
    </script>
    <body>
        <!-- Place this in your </body> tag -->
        <div id="complycube-mount"></div>
        <button onClick="startVerification()">
            Start verification
        </button>
        <input id="verification_complete" type="hidden" name="verification_complete" value=""/>
    </body>
</html>
