<?php

require_once 'groupme.php';

if(!isset($argc)){

	if(file_exists('tmp/hashes_store.csv')){
		echo 'Has already been initialized.';
		exit(1);
	} else {

		exec('php ./init.php > /dev/null &');
		exit(0);
	}

}

if($argc == 1){

	post_GroupMe('Initializing');

	$i = 0;
	$last = '';

} else {

	$last = $argv[1];
	$i = $argv[2];

}

$hasher = getHasher();
$data = '';

$NUM_TO_GET = 100;
$resp = curl_GroupMe($last, $NUM_TO_GET);

//post_GroupMe('Last msg was ' . $last . ', has processed ' . $i . ' images, and found ' . `cat tmp/hashes_store.csv | wc -l` . ' unique');

foreach($resp as $msg){
	if($msg['attachments'] && $msg['attachments'][0]['type'] == 'image'){//eventually check for mult attach?
		$data .= $msg['id'] . ',' . $hasher->hash($msg['attachments'][0]['url']) . "\n";
		$i += 1;
	}
}

file_put_contents('tmp/hashes_store.csv', $data, FILE_APPEND);

exec('awk \'{print NR "," $0}\' tmp/hashes_store.csv | sort -u -t , -k 3 | sort -t , -k 1 | cut -d , -f 2- > tmp/hashes_store.csv.tmp && mv tmp/hashes_store.csv.tmp tmp/hashes_store.csv');

$count = count($resp);

if($count < $NUM_TO_GET){

	post_GroupMe('Hashed ' . $i . ' images (' . `cat tmp/hashes_store.csv | wc -l` . ' unique) and waiting');

} else {

	$last = $resp[$count - 1]['id'];
	exec('php ./init.php ' . $last . ' ' . $i . ' > /dev/null &');

}
