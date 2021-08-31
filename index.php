<?php
error_reporting(0);
// Put your device token here (without spaces):


$deviceToken = 'fb28f93f0f7eb664de83d9970e48123bc5e7e292ba71087de1e7607cd2d8d564';
// $deviceToken = '2b53809de9b630199d2a1026918da67719a14bbcbf6f6e1b06c3f181c5558f6c';

//


// Put your private key's passphrase here:
$passphrase = '1';

// Put your alert message here:
$message = 'My first silent push notification!';



$ctx = stream_context_create();
// stream_context_set_option($ctx, 'ssl', 'local_cert', 'apns-dev.pem');
stream_context_set_option($ctx, 'ssl', 'local_cert', '/app/code.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
//  'ssl://gateway.push.apple.com:2195', $err,
'ssl://gateway.sandbox.push.apple.com:2195', $err,
$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body

$body['aps'] = array(
'content-available'=> 1,
'alert' => $message,
'sound' => 'default',
'badge' => 0,
);



// Encode the payload as JSON

$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
echo 'Message not delivered' . PHP_EOL;
else
echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);

?>

