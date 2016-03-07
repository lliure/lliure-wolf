<?php

/* @var $midias Midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once 'header.php';

require_once 'diretorio.php';

echo json_encode(Midias::preparaParaJson($arquivos));