<?php

require_once 'inicio.php';

/* @var $midias Midias */
$midias = unserialize(jf_decode($_SESSION['ll']['user']['token'], $_GET['m']));