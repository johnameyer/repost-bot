<?php

require_once 'groupme.php';

ini_set('allow_url_fopen', true);

$hasher = getHasher();

$data = callback_GroupMe();

function imageFunc($attachment){
	return $attachment['type'] == 'image';
}

$attach = $data['attachments'];
if(!$attach || !count($attach)) exit(0);
$attach = array_filter($attach, 'imageFunc');
if(!count($attach)) exit(0);
$img = $attach[0];

$hash = $hasher->hash($img['url']);
$sim_id = exec('./kd_tree.sh ' .  $data['id'] . ' ' . $hash . ' | head -n 1');
$msg = get_GroupMe($sim_id);
$val = $hasher->compare($img['url'], array_filter($msg['attachments'], 'imageFunc')[0]['url']);
if($val == 0){
	//blatant repost
	callout_repost($data, $msg, $val);
}elseif($val == -1){
	//repost by different users - not sure why different values
	callout_repost($data, $msg, $val);
}elseif($val < 20){
	post_GroupMe('Say "yes" if the two are similar (dist: ' . $val . ')', $GLOBALS['valid_bot']);
	post_GroupMe(get_attachment($msg)['url'], $GLOBALS['valid_bot']);
	file_put_contents('tmp/post.json', json_encode($msg));
	post_GroupMe(get_attachment($data)['url'], $GLOBALS['valid_bot']);
	file_put_contents('tmp/repost.json', json_encode($data));
}
