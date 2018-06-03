<?php

require_once 'groupme.php';

ini_set("allow_url_fopen", true);

$data = callback_GroupMe();

if($data["sender_type"] == "bot") exit(0);

if(file_exists("tmp/post.json")){
	error_log(strpos($data["text"], "y"));
	if(strpos($data["text"], "y") !== FALSE){
		error_log('ye');
		//is a repost
		$post = json_decode(file_get_contents("tmp/post.json"), true);
		$repost = json_decode(file_get_contents("tmp/repost.json"), true);
		callout_repost($repost, $post, 0);
	}
	unlink("tmp/post.json");
	unlink("tmp/repost.json");
}
