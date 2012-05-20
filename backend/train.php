<?php

require_once('config.php');
require_once('FaceRestClient.php');

function getTrainingUrls($uid) {
	global $siteurl;
	$facedir = $siteurl . 'faces';
	$files = scandir('../faces');

	$full_urls = array();
	foreach ($files as $file) {
		//Ignore . and ..
		if( strlen($file) > 2) {
			array_push($full_urls, "$siteurl.$file");
		}
	}

	return $full_urls;
}

function train(&$api, $uid)
{
    //obtain photos where this uid appears in (limit to 30)
    $urls = getTrainingUrls($uid);
    $urls = array_splice($urls, 0, 30);
    //run face detection on all images
    $tags = $api->faces_detect($urls);
    if (empty($tags->photos))
        return false;
    //build a list of tag ids for training
    $tids = array();
    foreach ($tags->photos as $photo)
    {
        //skip errors
        if (empty($photo->tags))
            continue;
        //skip photos with multiple faces (want to make sure only the uid appears)
        if (count($photo->tags) > 1)
            continue;
        $tid = $photo->tags[0]->tid;
        $tids[] = $tid;
    }
    //if faces were detected, save them for this uid
    if (count($tids) > 0)
        $api->tags_save($tids, $uid, $uid);
    //train the index with the newly saved tags
    $api->faces_train($uid);
    //all done, recognition for $uid can now begin
    return true;
}

//initialize API object with API key and Secret
$api = new FaceRestClient($apikey, $apisecret);
//the list of private-namespace UIDs to train and search for
$uids = array("jreptak");
//train the face.com index with the new uids
foreach ($uids as $uid)
    train($api, $uid);
?>
