<?php require_once 'connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
</head>
<body>
	<?php
	if(isset($_POST['word']) && !empty($_POST['word']) && isset($_POST['means']) && !empty($_POST['means']))
	{
		$data = [
			'word'	=>	$db->escape($_POST['word']),
			'mean'	=>	$db->escape($_POST['means']),
		];
		try {
			$db->insert('words', $data);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
	?>
	<form action="" method="POST">
		Kelime: <input type="text" name="word">
		Anlamları: <input type="text" name="means">
		<input type="submit" value="EKLE">
	</form>
</body>
</html>