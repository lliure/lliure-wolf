<?php
define('TCMD_PATH', realpath(dirname(__FILE__).'/../commands/'));

$nAttempts = $_REQUEST['attempts'];
$filename = $_REQUEST['filename'];
$c_dir = dir(TCMD_PATH);
$commandList = array();
$searchExpression = '/^'.$filename.'/';
while($file = $c_dir->read()){
    if(
        preg_match($searchExpression , $file )
        && $file != '.'    
        && $file != '..'    
    )
        $commandList[]= str_replace ('.php', '', $file);
}

if(isset($commandList[$nAttempts]))
    echo $commandList[$nAttempts];
?>
