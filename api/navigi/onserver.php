<?php
switch(isset($_GET['ac']) ? $_GET['ac'] : '' ){
	case 'pesquisa':

		$pesquisa = ((!empty($_POST['pesquisa']))? $pesquisa = '&pesquisa=' . $_POST['pesquisa']: '');
		header('location: '. $_POST['url']. $pesquisa);

	break;
	default:

		
		
	break;
}