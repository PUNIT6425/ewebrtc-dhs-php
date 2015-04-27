<?php
// Header Controls
//header('Access-Control-Allow-Origin: www.yourwebapp.com'); //Limited access
header("Access-Control-Allow-Origin: *"); //Allowed All
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Content-Type: application/json");

// Configure with your app information
$dhs_host = 'your_server_url/php-dhs'; // Copy to client-side application
$ewebrtc_domain = 'your_ewebrtc_domain';
$app_key = "your_app_key";
$app_secret = "your_app_secret";
$oauth_callback = "your_server_url/php-dhs/php-dhs/oauth.php";
$virtual_number = array (
    '1234567890',
    '0123456789'
);

// Default end points
$api_endpoint = 'https://api.att.com'; 
$token_version = '/oauth/v4/token'; 
$oauth_version = '/oauth/v4/authorize'; 
$rtc_version = '/RTC/v1'; 
$e911id_version = '/emergencyServices/v1/e911Locations';

?>
