<?php ob_start(); session_start();
	require_once 'connect.php';
	if(isset($_SESSION['previd']))
		$db->where('id', $_SESSION['previd'], '!=');

	$db->where('status', '0');
	$db->orderBy('RAND()');
	$first = $db->getOne('words');

	$db->where('status', '0');
	$remainingWord = $db->getValue('words', 'COUNT(id)');

	if($remainingWord > 1)
		$_SESSION['previd'] = $first['id'];

	if(isset($_GET['reset']))
	{
		$db->update('words', ['status' => '0']);
		session_destroy();
		header('Location: index.php');
	}
	if(isset($_GET['anan']))
	{
		$db->delete('words');
		session_destroy();
		header('Location: index.php');
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<style type="text/css">
		* {
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			outline: 0!important;
			box-shadow: none!important;
		}
		.vocabulary { letter-spacing: 15px; }

	</style>
</head>
<body class="bg-dark pt-5">
	<div class="px-4 py-5 my-5 text-center text-white">
		<div class="col-lg-4 mx-auto">
			<h1 class="fs-1 fw-bolder text-uppercase vocabulary" data-word-id="<?php echo $first['id']??NULL; ?>"><?php echo $first['word']??'<a href="?reset=1" class="text-white text-decoration-none">Yeniden Başlat</a>'; ?></h1>
			<h2 class="fs-6 fw-light text-uppercase means mb-5 mt-0 text-danger">&#9829;</h2>
			<?php
			if($first !== null)
			{
				echo '
			<form action="" method="POST">
				<input type="text" name="answer" class="form-control px-3 py-3 rounded-0 fs-3" autocomplete="off">
			</form>';
			}
			?>
		</div>
	</div>
	<audio controls id="correctSound" src="correct.mp3" type="audio/mpeg" class="d-none"></audio>
	<audio controls id="incorrectSound" src="incorrect.mp3" type="audio/mpeg" class="d-none"></audio>
	<audio controls id="lastSound" src="seeyou.mp3" type="audio/mpeg" class="d-none"></audio>
	<script type="text/javascript">

		jQuery(document).on('submit', 'form', function(event) {
			event.preventDefault();
			let answer = jQuery('[name=answer]').val();
			let wordid = jQuery('.vocabulary').attr('data-word-id');
			let audioCorrect 	= document.getElementById('correctSound');
			let audioIncorrect 	= document.getElementById('incorrectSound');
			let audioLast 		= document.getElementById('lastSound');

			const wait = ms => new Promise(resolve => setTimeout(resolve,ms));
			const vocabulary = new Promise((resolve, reject) => {
				$.ajax({
					url: 'ajax.php',
					type: 'POST',
					dataType: 'json',
					data: {id: wordid, answer: answer},
				})
				.done(function(response) {
					resolve(response);
				})
				.fail(function() {
					reject();
				})
			});

			vocabulary
			.then((response) => {
				if(response.status)
				{
					audioCorrect.currentTime=0;
					jQuery('body').removeClass('bg-dark').addClass('bg-success');
					audioCorrect.addEventListener('canplay', function() {
						audioCorrect.play();
					})
				    audioCorrect.play();
				}
				else
				{
					audioIncorrect.currentTime=0;
					jQuery('body').removeClass('bg-dark').addClass('bg-danger');
					audioIncorrect.addEventListener('canplay', function() {
						audioIncorrect.play();
					})
				    audioIncorrect.play();
				}
				return response;
			})
			.then((response) => {
				jQuery('.means').empty().html(response[0].mean).removeClass('text-danger');
				return wait(1500);
			})
			.catch((error) => {
				console.log(error);
			})
			.finally(() => {

				$.getJSON('ajax.php', function(data, textStatus) {
					if(data !== null)
					{
						jQuery('.vocabulary').attr('data-word-id', data.id);
						jQuery('.vocabulary').empty().html(data.word);
						jQuery('.means').empty().html('&#9829;').addClass('text-danger');
					}
					else
					{

						audioLast.currentTime=0;
						jQuery('body').removeClass('bg-dark').addClass('bg-danger');
						audioLast.addEventListener('canplay', function() {
							audioLast.play();
						})
						audioLast.addEventListener('ended', function() {
							audioLast.play();
						})
					    audioLast.play();
						jQuery('form').remove();
						jQuery('.vocabulary').empty().html('<iframe src="https://giphy.com/embed/4MEncZe27LjVZoWQ5V" width="480" height="480" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><a href="check.php" class="btn btn-info">Sonuç Listesi</a>');
						jQuery('.means').empty();
					}
					jQuery('[name=answer]').val('');
				});
				jQuery('body').removeClass('bg-success').removeClass('bg-danger').addClass('bg-dark');
			});



			return false;
		});
	</script>
</body>
</html>