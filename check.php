<?php require_once 'connect.php';
$db->where('status', '1');
$correctWords = $db->getValue('words', 'COUNT(id)');
$db->where('status', '2');
$incorrectWords = $db->getValue('words', 'COUNT(id)');
$db->where('status', '0');
$unaskedWords = $db->getValue('words', 'COUNT(id)');
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body class="bg-dark bg-opacity-10">
	<div class="container my-5 pt-5">
		<div class="row">
			<div class="col-md-2 offset-3">
				<div class="card bg-success text-white text-center">
				  <div class="card-body">
				  	<p class="fs-1"><?php echo $correctWords; ?></p>
				  	<p class="fs-4">Doğru Cevap</p>
				  </div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="card bg-danger text-white text-center">
				  <div class="card-body">
				  	<p class="fs-1"><?php echo $incorrectWords; ?></p>
				  	<p class="fs-4">Yanlış Cevap</p>
				  </div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="card bg-info text-white text-center">
				  <div class="card-body">
				  	<p class="fs-1"><?php echo $unaskedWords; ?></p>
				  	<p class="fs-4">Sorulmayan</p>
				  </div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 mx-auto">
				<table class="table mt-5">
					<thead>
						<tr class="bg-">
							<th scope="col">#</th>
							<th scope="col">Kelime</th>
							<th scope="col">Anlam</th>
							<th scope="col">Durum</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$words = $db->get('words');
							foreach ($words as $key => $word)
							{
								$statuses = [
									'Sorulmadı',
									'Doğru Cevaplandı',
									'Yanlış Cevaplandı'
								];
								$statusesClasses = [
									'bg-info',
									'bg-success',
									'bg-danger'
								];
								echo '<tr class="'.$statusesClasses[$word['status']].' text-white">
										<td>'.$word['id'].'</td>
										<td>'.$word['word'].'</td>
										<td>'.($word['mean']??'-').'</td>
										<td>'.$statuses[$word['status']].'</td>
									</tr>';
							}
						?>
					</tbody>
				</table>
				<a href="index.php" class="btn btn-info">Anasayfa</a>
			</div>
		</div>
	</div>
</body>
</html>