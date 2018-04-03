<?php

require_once 'groupme.php';

ini_set("allow_url_fopen", true);

$data = callback_GroupMe();

if($data["sender_type"] == "bot") exit(0);

if(file_exists("post.json")){
	error_log(strpos($data["text"], "y"));
	if(strpos($data["text"], "y") !== FALSE){
		error_log('ye');
		//is a repost
		$post = json_decode(file_get_contents("post.json"), true);
		$repost = json_decode(file_get_contents("repost.json"), true);
		callout_repost($repost, $post, 0);
	}
	unlink("post.json");
	unlink("repost.json");
}
/*
$url = "https://api.groupme.com/v3/bots/post";

file_put_contents("test.txt", $data);

file_put_contents("test2.txt", $data["name"]);

$fields = array(
        'bot_id' => 'ac22bc5749a5997eade2f7c0cf',
	'text' => $data["name"]
);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

if($data["sender_type"] != "bot")
	$server_output = curl_exec($ch);

curl_close($ch);

// further processing ....
echo $server_output;
//if ($server_output == "OK") {  } else {  }
*/
