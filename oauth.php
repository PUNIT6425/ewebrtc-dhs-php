<?php 
//DHS Oauth Redirect

include 'package.php';

// If the URL has only the redirect_uri, it is the forward call-flow from client->DHS
if (is_null($_REQUEST['code']) AND isset($_REQUEST['redirect_uri']) ) {
    // Contruct the standard AT&T OAuth Consent Callback URL
    $authorize_redirect_uri = $api_endpoint . $oauth_version . '?client_id=' . $app_key ."&scope=WEBRTCMOBILE&redirect_uri=" . $oauth_callback;
    // Add the client return landing page as parameter
    $forward_pass = $authorize_redirect_uri . '?client_return=' . $_REQUEST['redirect_uri'];
    // Redirect to AT&T as forward call-flow from DHS->AT&T
    header('Location: ' . $forward_pass);
    exit;
// if the URL has both the code and client_return, it is the returnn call-flow from AT&T->DHS
} elseif (isset($_REQUEST['code']) AND isset($_REQUEST['client_return']) ) {
    $return_pass = $_REQUEST['client_return'] . '?code=' . $_REQUEST['code'];
    // Redirect to client as return call-flow from DHS->Client
    header('Location: ' . $return_pass);
    exit;
} else {
    echo "PHP DHS OAuth Redirect Error: Missing Redirect URI.";
    exit;
}

/*

Client-side implementation

//After consentt flow return to current web page
window.location.href = myDHS.oauth_callback + '?redirect_uri=' + window.location.href;

//After consentt flow return to different page
window.location.href = myDHS.oauth_callback + '?redirect_uri=' + 'different_page_url';

*/

?>


