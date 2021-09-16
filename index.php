<?php

// Put your device token here (without spaces):


//$deviceToken = 'fc51e36cd18b8f2dbe17eb268124f4881d4c83ff70bd139dcff8e81b25cc3d8a';
 $deviceToken = 'C8F79797A2E325DBE37554289CB6C1BF0205B3347D8DAD0937137B48F7CCB946';

//


// Put your private key's passphrase here:
$passphrase = "1234";

// Put your alert message here:
$message = 'My first silent push notification!';



$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', '/app/cebod_san_push_cert.pem');
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
