<?php 
//DHS Create E911ID (POST)

include 'package.php';

// XMLHttpRequest POST sends data in a JSON body. The actual $_POST data is empty.
$request_body = file_get_contents('php://input');
$json_body = json_decode($request_body);

// AJAX POST sends data in $_POST data.
$address = $_POST['address'];

// Since they are mutually exclusive, one request will be empty.
// Combine them to support both AJAX or XMLHttpRequest requests

// Token is required
$token = $_POST['token'] . $json_body->token;

// Custom and standard variables used in WebRTC SDK E911 address format
$is_confirmed = $_POST['is_confirmed'] . $json_body->is_confirmed; //Custom
$first_name = $address['first_name'] . $json_body->address->first_name; //Custom
$last_name = $address['last_name'] . $json_body->address->last_name; //Custom
$house_number = $address['house_number'] . $json_body->address->house_number; //Custom
$street = $address['street'] . $json_body->address->street; //Standard
$unit = $address['unit'] . $json_body->address->unit; //Custom
$city = $address['city'] . $json_body->address->city; //Standard
$state = $address['state'] . $json_body->address->state; //Standard
$zip = $address['zip'] . $json_body->address->zip; //Standard

// Standard variables used in official E911 API address format
// Combine custom and standard varilables
$isAddressConfirmed = $_POST['isAddressConfirmed'] . $json_body->isAddressConfirmed . $is_confirmed; //Combined
$name = $address['name'] . $json_body->address->name . $first_name . ' ' . $last_name; //Combined
$houseNumber = $address['houseNumber'] . $json_body->address->houseNumber . $house_number; //Combined
$houseNumExt = $address['houseNumExt'] . $json_body->address->houseNumExt . $unit; //Combined
$streetNameSuffix = $address['streetNameSuffix'] . $json_body->address->streetNameSuffix; //Standard
$streetDir = $address['streetDir'] . $json_body->address->streetDir; //Standard
$streetDirSuffix = $address['streetDirSuffix'] . $json_body->address->streetDirSuffix; //Standard
$addressAdditional = $address['addressAdditional'] . $json_body->address->addressAdditional; //Standard
$comments = $address['comments'] . $json_body->address->comments; //Standard

// Check for POST data errors from WebRTC only
if (empty($token)) {
    echo "PHP DHS e911ID Error: Missing Access Token.";
    exit;
} elseif (empty($is_confirmed)) {
    echo "PHP DHS e911ID Error: No response to e911 Address confirmation question.";
    exit;
} elseif (empty($_POST['address']) AND (!$json_body->address)) {
    echo "PHP DHS e911ID Error: Missing e911 Address.";
    exit;
} elseif (empty($first_name)) {
    echo "PHP DHS e911ID Error: Missing User's First Name.";
    exit;
} elseif (empty($last_name)) {
    echo "PHP DHS e911ID Error: Missing User's Last Name.";
    exit;
} elseif (empty($house_number)) {
    echo "PHP DHS e911ID Error: Missing User's Stree Number.";
    exit;
} elseif (empty($street)) {
    echo "PHP DHS e911ID Error: Missing User's Stree Name.";
    exit;
} elseif (empty($city)) {
    echo "PHP DHS e911ID Error: Missing User's City.";
    exit;
} elseif (empty($state)) {
    echo "PHP DHS e911ID Error: Missing User's State.";
    exit;
} elseif (empty($zip)) {
    echo "PHP DHS e911ID Error: Missing User's Zip Code.";
    exit;
}

// Construct API request header to send to AT&T API Platform
$header = array (
    'content-type: application/json',
    'accept: application/json',
    'authorization: Bearer ' . $token
);

// Construct API request body using default  to send to AT&T API Platform
$body = array (                           
    'e911Context' => array  (
        'isAddressConfirmed' => $isAddressConfirmed,
        'address' => array  (
            'name' => $name,
            'houseNumber' => $houseNumber,
            'houseNumExt' => $houseNumExt,
            'street' => $street,
            'streetNameSuffix' => $streetNameSuffix,
            'streetDir' => $streetDir,
            'streetDirSuffix' => $streetDirSuffix,
            'city' => $city,
            'state' => $state,
            'zip' => $zip,
            'addressAdditional' => $addressAdditional,
            'comments' => $comments
        )
    )
);

// JSON Encode the address data   
$e911_context = json_encode($body);

// Use CURL to send request to AT&T API Platform    
$ch = curl_init(); 
  curl_setopt($ch, CURLOPT_URL, $api_endpoint . $e911id_version); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header );
  curl_setopt($ch, CURLOPT_POSTFIELDS, $e911_context);
  $e911id_data = curl_exec($ch); 
  curl_close($ch); 

// Return AT&T API reponse data back to requesting client
echo $e911id_data;
exit;

/* Create E911 Address ID

var xhrE911 = new XMLHttpRequest();
xhrE911.open('POST', myDHS.app_e911id_url);
xhrE911.setRequestHeader("Content-Type", "application/json");
xhrE911.onreadystatechange = function() {
    if (xhrE911.readyState == 4) {
        if (xhrE911.status == 200) {
            console.log(xhrE911.responseText);
            e911Data = (JSON.parse(xhrE911.responseText));
        } else {
            console.log(xhrE911.responseText);
        }
    }
}
xhrE911.send(JSON.stringify({
    token: accessToken.access_token,
    address: {
                first_name: 'ATT',
                last_name: 'Inc',
                house_number: '16221',
                street: 'NE 72nd Way',
                unit: '',
                city: 'Redmond',
                state: 'WA',
                zip: '98052'
    },
    is_confirmed: 'true'
}));
}

*/

?>


