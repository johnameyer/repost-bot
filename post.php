<?php

phpinfo();

$url = "https://api.groupme.com/v3/bots/post";

$fields = array(
        'bot_id' => 'ac22bc5749a5997eade2f7c0cf',
	'text' => 'hello'
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close($ch);

// further processing ....
echo $server_output;
//if ($server_output == "OK") {  } else {  }
