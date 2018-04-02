<?php

require_once 'groupme.php';

ini_set("allow_url_fopen", true);

$hasher = new Jenssegers\ImageHash\ImageHash;

$data = callback_GroupMe();

if(($attach = $data["attachments"]) && $attach[0]["type"] == "image"){
	$hash = $hasher->hash($attach[0]["url"]);
	$sim_id = exec("./kd_tree/build/kdtree -f hashes.csv " .  $hash);
	file_put_contents("hashes.csv", $data["id"] . "," . $hash . "\n", FILE_APPEND | LOCK_EX); // figure out how to handle locks better
	$msg = get_GroupMe($sim_id);
	$val = $hasher->compare($attach[0]["url"], $msg["attachments"][0]["url"]);
	if($val == 0){
		//blatant repost
		callout_repost($data, $msg, $val);
	}elseif($val == 1){
		//pretty darn sure
		post_GroupMe("Ahahahah");
	}elseif($val < 4){
		//human validation through dm bot?
		post_GroupMe("Ahaha?");
	}
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
