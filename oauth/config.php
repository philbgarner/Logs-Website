<?php
session_start();
include_once("src/Google_Client.php");
include_once("src/contrib/Google_Oauth2Service.php");
######### edit details ##########
$clientId = '151201712268-7dala6d6ucal0g23s1brotmtgm2ajukn.apps.googleusercontent.com'; //Google CLIENT ID
$clientSecret = 'uPorWd-gxlrYoLlRH9OU-HSM'; //Google CLIENT SECRET
$redirectUrl = 'http://ec2-35-164-53-143.us-west-2.compute.amazonaws.com/';  //return url (url to script)
$homeUrl = 'http://ec2-35-164-53-143.us-west-2.compute.amazonaws.com/';  //return to home

##################################

$gClient = new Google_Client();
$gClient->setApplicationName('CivEx Log Reporting');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>
