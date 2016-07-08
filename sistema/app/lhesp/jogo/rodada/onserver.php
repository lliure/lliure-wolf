<?php
print_r($_POST);

if(isset($_GET['gin'])){
	jf_update(appTabela.'estadios', $_POST, array('Id' => $_GET['gin']));
	
	ll_alert('Ginásio alterado com sucesso');
} else {
	jf_insert(appTabela.'estadios', $_POST);
	global $jf_ultimo_id;
	ll_historico('return');
	
	ll_alert('Ginásio adicionado com sucesso');
	$_GET['gin'] = $jf_ultimo_id;
}

header('location: '.$this->sapm->home.'&p=step&gin='.$_GET['gin']);
