<?php

require_once 'groupme.php';

if(file_exists("tmp/hashes_store.csv")){
	echo "Has already been initialized.";
	exit(1);
}

$hasher = new Jenssegers\ImageHash\ImageHash;

post_GroupMe("Initializing");

$resp = curl_GroupMe();

echo json_encode($resp);

$data = "";

$i = 0;

while($resp){
	foreach($resp as $msg){
		if($msg["attachments"] && $msg["attachments"][0]["type"] == "image"){//eventually check for mult attach?
			$data .= $msg["id"] . "," . $hasher->hash($msg["attachments"][0]["url"]) . "\n";
			$i += 1;
		}
	}
	$resp = 0;
}

file_put_contents("tmp/hashes_store.csv", $data);

post_GroupMe("Hashed " . $i . " images and waiting");
