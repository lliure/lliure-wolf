<?php

require_once 'inicio.php';

/* @var $midias Midias */
$midias = unserialize(jf_decode($_SESSION['logado']['token'], $_GET['m']));