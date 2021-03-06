<?php

require_once 'vendor/autoload.php';

require_once 'config.php';

function getHasher(){
	return new Jenssegers\ImageHash\ImageHash(new Jenssegers\ImageHash\Implementations\DifferenceHash(16));
}

function post_GroupMe($msg, $group=0){
	if(!$group) $group = $GLOBALS['main_bot'];
	$ch = curl_init();
	$url = 'https://api.groupme.com/v3/bots/post';
	$fields = array(
		'bot_id' => $group,
		'text' => $msg
	);

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close($ch);
}

function craft_message($repost, $post, $sureness){
	$date = ' from ' . date('M d, Y h:i:s A', $post['created_at']);
	$poster = $post['name'];
	$reposter = $repost['name'];

	$caption = ($repost['text']) ? ' with caption "' . $repost['text'] . '"' : '';

	$recency = '';

	{
		$recent_msgs = curl_GroupMe('', 15);
		$i = 1;
		foreach($recent_msgs as $msg){
			if($msg['id'] == $repost['id']) break;
			$i += 1;
		}

		$recency = ($i < 10) ? ( ($i == 1) ? ' just now'  : ' ' . $i . ' messages ago' ) : ' a while ago';

	}

	if($repost['sender_id'] == '22102250'){
		return '@' . $repost['name'] . ' reposted' . $date . $recency . $caption . ' but it is an iconic meme';
	}
	if($repost['name'] != $post['name']){
		return '@' . $repost['name'] . ' totally just reposted ' . $post['name'] . '\'s post' . $date . $recency . $caption;
	}else{
		return '@' . $repost['name'] . ' just reposted their post' . $date . $recency . $caption;
	}
}

function callout_repost($repost, $post, $sureness){
	$msg = craft_message($repost, $post, $sureness);

	$tag = array(
		'loci' => array(array(
			strpos($msg, '@'),
			strlen($repost['name']) + 1
		)),
		'type' => 'mentions',
		'user_ids' => array($repost['sender_id'])
	);

        $ch = curl_init();
        $url = 'https://api.groupme.com/v3/bots/post';
        $fields = array(
                'bot_id' => $GLOBALS['main_bot'],
                'text' => $msg,
		'attachments' => array($tag)
	);

	//post_GroupMe(json_encode($fields));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));

        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
}

function curl_GroupMe($last = '', $limit = 100){
	$msg = curl_init();
	$id = $GLOBALS['main_group'];
	$url = 'https://api.groupme.com/v3/groups/' . $id . '/messages';
	$fields = array(
		'token' => $GLOBALS['api'],
		'limit' => $limit,
		'before_id' => $last
	);

	curl_setopt($msg, CURLOPT_URL, $url . '?' . http_build_query($fields));
	curl_setopt($msg, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($msg);
	curl_close($msg);

	return json_decode($server_output, true)['response']['messages'];
}

function callback_GroupMe(){
	return json_decode(file_get_contents('php://input'), true);
}

function get_GroupMe($id){
	$prev = curl_GroupMe($id, 1)[0]['id'];
	$msg = curl_init();
        $id = $GLOBALS['main_group'];
        $url = 'https://api.groupme.com/v3/groups/' . $id . '/messages';
        $fields = array(
                'token' => $GLOBALS['api'],
                'limit' => 1,
                'after_id' => $prev
        );

        curl_setopt($msg, CURLOPT_URL, $url . '?' . http_build_query($fields));
        curl_setopt($msg, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($msg);
        curl_close($msg);

        return json_decode($server_output, true)['response']['messages'][0];
}

function get_attachment($msg){
	foreach($msg['attachments'] as $attach){
		if($attach['type'] == 'image') return $attach;
	}
}
