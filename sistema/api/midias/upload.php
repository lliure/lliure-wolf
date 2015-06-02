<?php

/* @var $midias midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once 'header.php';

$e = explode('.', $_FILES['file']['name']);
$ets = array_pop($e);
$nam = implode('.', $e);

$i = -1;

$n = $_FILES['file']['name'];
$name = $pasta. DS. $_FILES['file']['name'];

while (file_exists(($name = $pasta. DS. $nam. ((++$i) > 0? ' ('. $i. ')': ''). '.'. $ets)));

$erros = array(
	0 => 'Não houve erro, o upload foi bem sucedido.',
	1 => 'O arquivo no upload é maior do que o limite definido em upload_max_filesize no php.ini.',
	2 => 'O arquivo ultrapassa o limite de tamanho em MAX_FILE_SIZE que foi especificado no formulário HTML.',
	3 => 'O upload do arquivo foi feito parcialmente.',
	4 => 'Não foi feito o upload do arquivo.'
);

$t = move_uploaded_file($_FILES['file']['tmp_name'],$name);

if ($t !== FALSE){
	
	$data = filemtime($name);
	$size = filesize($name);
	
	echo json_encode(midias::preparaParaJson(array(
		'data' => $data,
		'size' => $size,
		'etc'  => $ets,
		'nome' => $n
	)));
	
}else {
	echo json_encode(midias::preparaParaJson(array(
		'erro' => !$t,
		'cod' => $_FILES['file']['error'],
		'msg' => $erros[$_FILES['file']['error']]
	)));
}
