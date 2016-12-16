<?php
switch(isset($_GET['ac']) ? $_GET['ac'] : '' ){
	case 'pesquisa':

		$pesquisa = ((!empty($_POST['pesquisa']))? $pesquisa = '&pesquisa=' . $_POST['pesquisa']: '');

		if(isset($_POST['filter']) && (isset($_POST['s']) || isset($_POST['o']))){

            $o = $f = [];

            if(isset($_POST['s'])) foreach($_POST['s'] as $k => $v)
                if(!empty($v)) $f['s'][$k] = rawurlencode($v);

            if(isset($_POST['o'])) foreach($_POST['o'] as $k => $v)
                if(!empty($v) && !!($v = (explode(':', $v)))) $o[$v[0]] = [$k => $v[1]];

            ksort($o);
            if(!empty($o)){ $f['o'] = [];
                foreach($o as $k => $v) $f['o'] = array_merge($f['o'], $v); }

            $pesquisa = ((!!($f = rawurldecode( http_build_query($f) )))? "&$f": '');
        }

		header('location: '. $_POST['url']. $pesquisa);

	break;
	case 'delete':

		$navigi = unserialize(jf_decode($_SESSION['ll']['user']['token'], $_POST['token']));

		if($navigi['delete']){
			$tabela = $navigi['tabela'];
			$id = mysql_real_escape_string($_POST['id']);

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

		} else echo 403;

	break;
	default:

		
		
	break;
}