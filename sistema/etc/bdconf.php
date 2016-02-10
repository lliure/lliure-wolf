<?php
$hostname_conexao = "localhost";
$username_conexao = "root";
$password_conexao = "theterminator";
$banco_conexao = "lliure";
session_name(md5($banco_conexao));
session_start();

define("PREFIXO", "ll_");
define("SISTEMA", "sistema");


$conexao = mysql_connect($hostname_conexao, $username_conexao, $password_conexao) or die("Site em manutenção");
	
mysql_select_db($banco_conexao, $conexao);

$DadosLogado  = (isset($_SESSION['logado'])? $_SESSION['logado'] : '');
?>
