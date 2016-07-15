<?php
switch(isset($_GET['ac']) ? $_GET['ac'] : '' ){
case 'pesquisa':	

	$pesquisa = '';
	if(!empty($_POST['pesquisa']))
		$pesquisa = '&pesquisa=' . $_POST['pesquisa'];
	

	header('location: index.php'.$_POST['url'].$pesquisa);
	break;


default:
	break;
}