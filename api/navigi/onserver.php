<?php
switch(isset($_GET['ac']) ? $_GET['ac'] : '' ){
	case 'pesquisa':

		$pesquisa = ((!empty($_POST['pesquisa']))? $pesquisa = '&pesquisa=' . $_POST['pesquisa']: '');
		header('location: '. $_POST['url']. $pesquisa);

	break;
	case 'delete':

		$navigi = unserialize(jf_decode($_SESSION['ll']['user']['token'], $_POST['token']));

		if($navigi['delete']){
			$tabela = $navigi['tabela'];
			$id = mysql_real_escape_string(substr($_POST['id'], 5));

			jf_delete($tabela, array('id' => $id));

			if(mysql_error() != ''){
				if($navigi['debug'])
					echo mysql_error();
				else
					echo 412;
			}
		} else
			echo 403;

	break;
	case 'rename':

		$navigi = unserialize(jf_decode($_SESSION['ll']['user']['token'], $_POST['token']));

		$seletor = 0;
		if($navigi['configSel'] != false)
			$seletor = $_POST['seletor'];

		if($navigi['rename'] || (isset($navigi['config'][$seletor]['rename']) && $navigi['config'][$seletor]['rename'])){
			$_POST = jf_iconv2($_POST);

			$tabela = $navigi['config'][$seletor]['tabela'];
			$dados[$navigi['config'][$seletor]['coluna']] = mysql_real_escape_string($_POST['texto']);
			$id = mysql_real_escape_string($_POST['id']);

			jf_update($tabela, $dados, array('id' => $id));

		} else
			echo 403;

	break;
	default:

		
		
	break;
}