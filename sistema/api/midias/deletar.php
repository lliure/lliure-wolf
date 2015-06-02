<?php

/* @var $midias midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once 'header.php';

if(!is_dir($pasta. DS. $_GET['ap']))
	unlink($pasta. DS. $_GET['ap']);


echo json_encode(midias::preparaParaJson(array('ok' => 'ok')));

