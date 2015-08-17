<?php

/* @var $midias Midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once 'header.php';

if(!is_dir($midias->pasta(). DS. $_GET['ap']))
	unlink($midias->pasta(). DS. $_GET['ap']);

echo json_encode(Midias::preparaParaJson(array('ok' => 'ok')));