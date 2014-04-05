<?php
define('LLPATH', realpath(dirname(__FILE__).'/../../../').'/');
require_once LLPATH.'etc/bdconf.php';
require_once LLPATH.'includes/jf.funcoes.php';

$whiteList = array(
	'close',
	'clear',
	'about',
	'commands'
);

function LLBeforeCom($command){
	global $whiteList;
	
	if(!isset($_SESSION['logado']) && !in_array($command, $whiteList))
		die('Você deve estar logado para executar um comando');
}

Terminal::$onBeforeCommand = 'LLBeforeCom';
?>
