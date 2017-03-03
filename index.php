<?php
// カウント数取得関数
function get_count($id) {
	$filename = 'data.json';
	// $fp = @fopen($filename, 'r');
	// if ($fp) {
	// 	$vote = fgets($fp, 9182);
	// } else {
	// 	$vote = 0;
	// }

	$json = file_get_contents($filename);
	if ($json === false) {
	    throw new \RuntimeException('file not found.');
	}
	$data = json_decode($json, true);

	if ($id == 'vote_01') {
		$vote = $data['vote']['vote_01'];
	}elseif ($id == 'vote_02') {
		$vote = $data['vote']['vote_02'];
	}else{
		$vote = 0; 
	}

	return $vote;
}
?>
<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>大統領投票</title>
<link rel="stylesheet" href="css/style.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script>
$(function() {
	allowAjax = true;

	restriction = false;

	if ($.cookie("id")&&restriction) {
		console.log($.cookie("id"))
		$('#'+$.cookie("id")).toggleClass('on');
	};

	$('.btn_vote').click(function() {
		if ($.cookie("voted") == 'true' && restriction) {
			alert('投票済みです')
		}else{
			if (allowAjax) {
				allowAjax = false;
				$(this).toggleClass('on');
				var id = $(this).attr('id');
				$(this).hasClass('on') ? Vote(id, 'plus') : Vote(id, 'minus');
			}
		}
	});
});
function Vote(id, plus) {
	cls = $('.' + id);
	cls_num = Number(cls.html());
	$.post('vote.php', {'id': id, 'plus': plus}, function(data) {
		console.log(data)
		if (data == 'success'){
			if (plus == 'plus') {cls.html(cls_num+1)};
			if (plus == 'minus') {cls.html(cls_num-1)};
		} 
		$.cookie("voted", "true", { expires: 1 });
		$.cookie("id", id, { expires: 1 });
		setTimeout(function() {
			allowAjax = true;
		}, 1000);
	});
}
</script>
</head>
<body>

<h1>大統領選挙</h1>

<article>

<section>

<p><img src="img/hilaray.png" width="100%" alt="ヒレイリー"></p>

<div class="btn_area">

<h2>ヒレイリー</h2>

<p><span class="vote_01"><?= get_count('vote_01') ?></span>票</p>

<p class="btn_vote" id="vote_01"></p>

</div><!-- /btn_area -->

</section>

<section>

<p><img src="img/tremp.png" width="100%" alt="トランプ"></p>

<div class="btn_area">

<h2>トレンプ</h2>

<p><span class="vote_02"><?= get_count('vote_02') ?></span>票</p>

<p class="btn_vote" id="vote_02"></p>

</div><!-- /btn_area -->

</section>

</article>

<footer>

</footer>

</body>
</html>