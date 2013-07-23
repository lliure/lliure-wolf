<?php
header('Content-type: text/html;charset=UTF8');
define('TCMD_PATH', realpath(dirname(__FILE__).'/../commands/'));
require_once 'terminal.php';
require_once 'boot.php';

Terminal::setCommandPart($_POST['commandPart']);
Terminal::setCurrentCommand($_POST['commandName']);
 
Terminal::runCommand($_POST['command']);
?>
