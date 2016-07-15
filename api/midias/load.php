<?php

require_once 'inicio.php';

/* @var $midias Midias */
$midias = unserialize(jf_decode($_SESSION['ll']['user']['token'], $_GET['m']));

if(!isset($load)) die();

require_once (dirname(__FILE__). '/pages/'. $_GET['p']. '.php');