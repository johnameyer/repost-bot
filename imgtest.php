<?php

require_once 'groupme.php';

ini_set('allow_url_fopen', true);

$hash1 = new Jenssegers\ImageHash\Implementations\AverageHash();
$hash2 = new Jenssegers\ImageHash\Implementations\DifferenceHash();
$hash3 = new Jenssegers\ImageHash\Implementations\PerceptualHash();

$hasher = new Jenssegers\ImageHash\ImageHash($hash1);

//echo ('https://i.groupme.com/' . $_REQUEST['one']) . ' and ' . ('https://i.groupme.com/' . $_REQUEST['two']);

echo 'Hash 1: ' . $hasher->hash('https://i.groupme.com/' . $_REQUEST['one']) . "<br>";
echo 'Hash 2: ' . $hasher->hash('https://i.groupme.com/' . $_REQUEST['two']) . "<br>";
echo 'Val: ' . $hasher->compare('https://i.groupme.com/' . $_REQUEST['one'], 'https://i.groupme.com/' . $_REQUEST['two']);

