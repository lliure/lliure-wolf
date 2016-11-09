<?php global $_ll;
/**
 * lliure WAP
 *
 * @Versão 9.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 */

switch (isset($_GET['ac']) ? $_GET['ac'] :  ''){
case 'grava':

	if(empty($_POST['senha'])) unset($_POST['senha']);
	else $_POST['senha'] = Senha::create($_POST['senha']);
	
	$file = new fileup; $file->diretorio = '../uploads/usuarios/'; $file->up();

	if(ll::valida() || $_SESSION['ll']['user']['id'] == $_POST['id']) {
		$user->upd($_POST);

		if(ll::valida() && isset($_POST['liberacoes'])){

			$liberacao = new Liberacao(); $delete = $insert = array();
			foreach( $liberacao->get(array('login' => $_POST['login'])) as $del)
				$delete["{$del['operation_type']}/{$del['operation_load']}"] = $del;

			foreach ($_POST['liberacoes'] as $v){
				if(isset($delete[$v]))
					unset($delete[$v]);
				else{
					list($operation_type, $operation_load) = explode('/', $v);
					$insert[] = array(
						'operation_type' => $operation_type,
						'operation_load' => $operation_load,
						'login' => $_POST['login'],
					);
				}
			}

			if(!empty($delete)) foreach ($delete as $del) $liberacao->del($del);
			if(!empty($insert)) foreach ($insert as $ins) $liberacao->set($ins);

		}
	}

	ll_alert("Alteração realizada com sucesso!");
	header('location: '.$_ll['opt']['home'].(isset($_GET['en']) && $_GET['en'] == 'minhaconta' ? '&en=minhaconta' : '' ));

break;}