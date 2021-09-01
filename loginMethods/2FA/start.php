<?php

require_once 'PHPGangsta/GoogleAuthenticator.php';
 
$ga = new PHPGangsta_GoogleAuthenticator();
 
$secret = $ga->createSecret();
 
$qrCodeUrl = $ga->getQRCodeGoogleUrl("Webtech 2 - Zadanie 3 - Martin Kováčik", $secret);
echo 'Scan this QR code using Google Authenticator:<br /><img src="'.$qrCodeUrl.'" />';

?>