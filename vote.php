<?php
// カウントアップ処理
$id	= $_POST['id'];
$plus	= $_POST['plus'];
$check	= $_SERVER['HTTP_X_REQUESTED_WITH'];

if ($id && $plus && $check && strtolower($check) == 'xmlhttprequest') {
	$filename = 'data.json';
	$json = file_get_contents($filename);
	if ($json === false) {
	    throw new \RuntimeException('file not found.');
	}
	$data = json_decode($json, true);

	if ($id == 'vote_01') {
		if ($plus == 'plus') {
			$data['vote']['vote_01'] =  intval($data['vote']['vote_01']) + 1;
		}elseif ($plus == 'minus') {
			$data['vote']['vote_01'] =  intval($data['vote']['vote_01']) - 1;
		}
		
	}

	if ($id == 'vote_02') {
		if ($plus == 'plus') {
			$data['vote']['vote_02'] =  intval($data['vote']['vote_02']) + 1;
		}elseif ($plus == 'minus') {
			$data['vote']['vote_02'] =  intval($data['vote']['vote_02']) - 1;
		}
		
	}


	$fp = @fopen($filename, 'w');
	flock($fp, LOCK_EX);
	fwrite($fp, json_encode($data));
	flock($fp, LOCK_UN);
	fclose($fp);
	echo 'success';

}
