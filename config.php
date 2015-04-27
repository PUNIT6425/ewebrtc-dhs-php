<?php
//DHS Get Configuration (GET)

// Do NOT modify Client-side variables and URLs. They are loaded onto the application side. 

include 'package.php';

// Construct the data arrary
$getDHS = array (
    'api_endpoint' => $api_endpoint,
    'authorize_uri' => $oauth_version,
    'oauth_callback' => $oauth_callback,
    'app_key'=> $app_key,
    'ewebrtc_domain' => $ewebrtc_domain,
    'virtual_numbers_pool' => $virtual_number,
    'app_token_url' => $dhs_host.'/token.php',
    'app_e911id_url' => $dhs_host.'/e911id.php',
    'info' => array (
        'dhs_name' => 'att.dhs',
        'dhs_platform' => 'PHP',
        'dhs_version' => '1.0.2',
        'api_env' => 'NA',
        'token_uri' => $token_version,
        'e911id_uri' => $e911id_version,
        'ewebrtc_uri' => $rtc_version,
        'scope_map' => array (
            'MOBILE_NUMBER' => "WEBRTCMOBILE",
            'VIRTUAL_NUMBER' => 'WEBRTC',
            'ACCOUNT_ID'=> 'WEBRTC',
            'E911' => 'EMERGENCYSERVICES'
        )
    )
);

// JSON Encode the data   
$getDHS = json_encode($getDHS);

// Return JSOn to requesting client
echo $getDHS;
exit;

/* Get DHS Configuration

var myDHS, myDHSURL = 'my_dhs_url';

var xhrConfig = new XMLHttpRequest();
xhrConfig.open('GET', myDHSURL + "/config.php");
xhrConfig.onreadystatechange = function() {
    if (xhrConfig.readyState == 4) {
        if (xhrConfig.status == 200) {
            console.log(xhrConfig.responseText);
            myDHS = JSON.parse(xhrConfig.responseText);
        } else {
            console.log(xhrConfig.responseText);
        }
    }
}
xhrConfig.send();

*/

?>
