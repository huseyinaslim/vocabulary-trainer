<?php ob_start(); session_start();
require_once 'connect.php';
header('Content-type: application/json; charset=UTF-8');

$db->where('status', '0');
$remainingWord = $db->getValue('words', 'COUNT(id)');

if(isset($_SESSION['previd']) && $remainingWord > 1)
	$db->where('id', $_SESSION['previd'], '!=');

if(isset($_POST['id']))
	$db->where('id', $_POST['id'], '!=');

$db->where('status', '0');
$db->orderBy('RAND()');
$newWord = $db->getOne('words', 'id, word');

if($newWord === NULL)
	session_destroy();


if($remainingWord > 1)
	$_SESSION['previd'] = $newWord['id']??NULL;

function tr_strtolower($text)
{
    $search=array("Ç","İ","I","Ğ","Ö","Ş","Ü");
    $replace=array("ç","i","ı","ğ","ö","ş","ü");
    $text=str_replace($search,$replace,$text);
    $text=strtolower($text);
    return $text;
}
if(!isset($_POST['id']))
{
	echo json_encode($newWord);
}
else
{
	if(!isset($_POST['id']) || !isset($_POST['answer']) || empty($_POST['answer']))
	{
		echo json_encode(['t' => 'Hata!', 'm' => 'Geçersiz ID veya Boş Cevap', 's' => 'warning']);
		return;
	}

	$db->where('id', $db->escape($_POST['id']));
	$data = $db->getOne('words');

	$status = str_contains(tr_strtolower($data['mean']), tr_strtolower($_POST['answer']));

	$db->where('id', $db->escape($_POST['id']));
	$db->update('words', ['status' => (($status)?'1':'2')]);

	echo json_encode([$data, 'status' => $status],JSON_UNESCAPED_UNICODE);
}