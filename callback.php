<?php

require_once 'groupme.php';

ini_set('allow_url_fopen', true);

$hasher = new Jenssegers\ImageHash\ImageHash;

$data = callback_GroupMe();

if(($attach = $data['attachments']) && $attach[0]['type'] == 'image'){
	$hash = $hasher->hash($attach[0]['url']);
	$sim_id = exec('./kd_tree.sh ' .  $data['id'] . ' ' . $hash . ' | head -n 1');
	$msg = get_GroupMe($sim_id);
	$val = $hasher->compare($attach[0]['url'], $msg['attachments'][0]['url']);
	if($val == 0){
		//blatant repost
		callout_repost($data, $msg, $val);
	}elseif($val < 5){
		post_GroupMe(get_attachment($msg)['url'], $GLOBALS['valid_bot']);
		file_put_contents('tmp/post.json', json_encode($msg));
		post_GroupMe(get_attachment($data)['url'], $GLOBALS['valid_bot']);
		file_put_contents('tmp/repost.json', json_encode($data));
	}
}
