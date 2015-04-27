<?php
//DHS Create Access Token (POST)

include 'package.php';

// XMLHttpRequest POST sends data in a JSON body. The actual $_POST data is empty.
$request_body = file_get_contents('php://input');
$json_body = json_decode($request_body);

// AJAX POST sends data in $_POST data.

// Since they are mutually exclusive, one request will be empty.
// Combine them to support both AJAX or XMLHttpRequest requests
$app_scope = $_POST['app_scope'] . $json_body->app_scope;
$auth_code = $_POST['auth_code'] . $json_body->auth_code;

// Check for POST data errors
if (empty($app_scope)) {
    echo "PHP DHS Token Request Error: Missing App Scope.";
    exit;
} elseif ($app_scope == 'WEBRTCMOBILE' OR $app_scope == 'MOBILE_NUMBER') {
    $grant_type = 'authorization_code';
    if (empty($auth_code)) {
        echo "PHP DHS Token Request Error: Missing Authorization Code for AT&T Mobile Number Consent.";
        exit;
    }
} elseif ($app_scope == 'ACCOUNT_ID' OR $app_scope == 'VIRTUAL_NUMBER' OR $app_scope == 'WEBRTC') {
    $token_scope = 'WEBRTC';
    $grant_type = 'client_credentials';
} elseif ($app_scope == 'E911' OR $app_scope == 'EMERGENCYSERVICES') {
    $token_scope = 'EMERGENCYSERVICES';
    $grant_type = 'client_credentials';
} else {
    echo "PHP DHS Token Request Error: Invalid App Scope.";
    exit;
}

// Construct API request body to send to AT&T API Platform
$body = array (
      'client_id'=>$app_key,
      'client_secret'=>$app_secret,
      'grant_type'=>$grant_type,
      'code'=>$auth_code,
      'scope'=>$token_scope
);

// Use CURL to send request to AT&T API Platform      
$ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $api_endpoint.$token_version); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
  $token_data = curl_exec($ch); 
  curl_close($ch); 
  
// Return AT&T API reponse data back to requesting client
echo $token_data;
exit;

/* Create access token (appScope: ACCOUNT_ID, E911, MOBILE_NUMBER or VIRTUAL_NUMBER)

var appScope, authCode, accessToken;

function createAccessToken() {
    var xhrToken = new XMLHttpRequest();
        xhrToken.open('POST', myDHS.app_token_url);
        xhrToken.setRequestHeader("Content-Type", "application/json");
        xhrToken.onreadystatechange = function() {
            if (xhrToken.readyState == 4) {
                if (xhrToken.status == 200) {
                    console.log(xhrToken.responseText);
                    accessToken = (JSON.parse(xhrToken.responseText));
                } else {
                    console.log(xhrToken.responseText);
                }
            }
        }
        xhrToken.send(JSON.stringify({app_scope: appScope, auth_code: authCode}));
}

*/

?>






