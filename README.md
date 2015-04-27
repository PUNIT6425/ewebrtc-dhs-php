# PHP-DHS Installation Guide

This document provides procedure to using this PHP-DHS for AT&T OAuth Services to support your WebRTC Web Application. Instructioons include:

* Deploying PHP code files to your PHP server, 
* Updating your web application to use this PHP-DHS, and
* Optionally deploying AT&T WebRTC Sample App to your PHP server.


## Minimum System Requirements
* PHP v5.4 or later.
   Info: http://www.php.net/releases
* AT&T Enhanced WebRTC App Credential. 
   Info: http://developer.att.com/webrtc
   
   
## Get Sample App Package
* Download Sample App at https://github.com/attdevsupport/ewebrtc-sdk
* Unzip the file. (Note: You do NOT need to follow the Node installation instructions.)


## Deploy PHP-DHS
* Copy the entire `php-dhs` folder to any server directory of your choice. You can optionally rename the directory.
* In `php-dhs`, use a text editor to update `package.php` with your own app credentials as follows:
```javascript
    $dhs_host = 'your_server_url/php-dhs'; // Copy to client-side application
    $ewebrtc_domain = 'your_ewebrtc_domain';
    $app_key = 'your_app_key';
    $app_secret = 'your_app_secret';
    $oauth_callback = 'your_server_url/php-dhs/oauth.php';
    $virtual_number = array (
        '1234567890',
        '0123456789'
    );
```    
* In AT&T Developer Portal `My App`, update your app's OAuth Callback URL to `your_server_url`.


## Update Your Web Application
* Load DHS Configuration Information (Optional)
```javascript    
    var myDHS, acessToken, e911Data, myDHSURL = 'your_server_url/php-dhs';
    
    var xhrConfig = new XMLHttpRequest();
    xhrConfig.open('GET', myDHSURL + '/config.php');
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
```    
* Create Access Token
```javascript
    var xhrToken = new XMLHttpRequest();
    xhrToken.open('POST', myDHS.app_token_url); //Or 'your_server_url/php-dhs/token.php' as URL
    xhrToken.setRequestHeader('Content-Type', 'application/json');
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
```    
* Create E911ID
```javascript
    var xhrE911 = new XMLHttpRequest();
    xhrE911.open('POST', myDHS.app_e911id_url); //Or 'your_server_url/php-dhs/e911id.php' as URL
    xhrE911.setRequestHeader('Content-Type', 'application/json');
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
```    
* OAuth Redirect Options
```javascript
    //After consent flow return to current web page with optional DHS Configuration
    window.location.href = myDHS.oauth_callback + '?redirect_uri=' + window.location.href;
    
    //After consent flow return to current web page without optional DHS Configuration
    window.location.href = 'your_server_url/php-dhs/oauth.php?redirect_uri=' + window.location.href;

    //After consent flow return to different page with optional DHS Configuration
    window.location.href = myDHS.oauth_callback + '?redirect_uri=different_page_url';
    
    //After consent flow return to different page without optional DHS Configuration
    window.location.href = 'your_server_url/php-dhs/oauth.php?redirect_uri=different_page_url';
```    
    
## Deploy AT&T WebRTC Sample App (Optional)
* In `/node-sample`, copy the entire `public` folder to any server directory of your choice. You can optionally rename the directory.
* In `/public/js/router.js`, update the following lines to:
```javascript
    var myDHS = 'your_server_url/php-dhs',
    
    xhrConfig.open('GET', myDHS + '/config.php');
```