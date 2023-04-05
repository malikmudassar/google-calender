<?php
require __DIR__ . '/vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setAuthConfig('credentials.json');
$client->setRedirectUri('http://localhost:80/test/callback.php');
$client->addScope(Google_Service_Calendar::CALENDAR);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  $client->setAccessToken($_SESSION['access_token']);
} else {
  header('Location: ' . filter_var($client->createAuthUrl(), FILTER_SANITIZE_URL));
  exit;
}

$service = new Google_Service_Calendar($client);

$event = new Google_Service_Calendar_Event(array(
  'summary' => $_POST['event_name'],
  'start' => array(
    'dateTime' => $_POST['event_date'] . 'T09:00:00',
    'timeZone' => 'Asia/Karachi',
  ),
  'end' => array(
    'dateTime' => $_POST['event_date'] . 'T17:00:00',
    'timeZone' => 'Asia/Karachi',
  ),
  'attendees' => array(
    array('email' => $_POST['guest_1']),
    array('email' => $_POST['guest_2']),
  ),
));

$calendarId = 'primary';
$event = $service->events->insert($calendarId, $event);

echo "Event created: " . $event->getId();
