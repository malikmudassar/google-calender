<?php
require __DIR__ . '/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->setRedirectUri('http://localhost:80/test/callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);

if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $_SESSION['access_token'] = $token;
  header('Location: create_event.php');
  exit;
} else {
  echo "Error: Authorization code not found.";
}
