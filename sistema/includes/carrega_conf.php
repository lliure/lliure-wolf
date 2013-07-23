<?php

if(($_ll['conf'] = @simplexml_load_file(realpath(dirname(__FILE__).'/../etc/llconf.ll'))) == false)
	$_ll['conf'] = false;
	
?>
