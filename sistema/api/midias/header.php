<?php

define('MIDIAS_BASEPATH', realpath(dirname(__FILE__). '/../../'));
define('DS', DIRECTORY_SEPARATOR);

require_once MIDIAS_BASEPATH. '/etc/bdconf.php';
require_once MIDIAS_BASEPATH. '/includes/functions.php';
require_once 'inicio.php';

/* @var $midias midias */
$midias = unserialize(jf_decode($_SESSION['logado']['token'], $_GET['m']));

$pasta = realpath($midias->rais(). DS . ($dir = $midias->diretorio() && !empty($dir)? $dir. DS: ''));
$pastaRef = str_repeat('../', count(explode('/', SISTEMA))). str_replace('\\', '/', substr($pasta, (strlen(MIDIAS_BASEPATH) - strlen(SISTEMA))));

