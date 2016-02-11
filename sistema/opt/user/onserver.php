<?php
/**
 *
 * lliure WAP
 *
 * @Versão 7.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

switch (isset($_GET['ac']) ? $_GET['ac'] :  ''){
case 'grava':
	require_once("api/fileup/inicio.php"); 

	if(empty($_POST['senha'])){
		unset($_POST['senha']);
	} else{
		$_POST['senha'] = md5($_POST['senha']."0800");
	}
	
	$file = new fileup; 								// incia a classe
	$file->diretorio = '../uploads/usuarios/';			// pasta para o upload (lembre-se que o caminho é apartir do arquivo onde estiver sedo execultado)
	$file->up(); 										// executa a classe
	
	if(isset($_POST['imagem']))
		unset($_POST['imagem']);
		
	
	jf_update(PREFIXO."lliure_admin", $_POST, array('id' => $_GET['id']));

	($_SESSION['Logado']['id'] == $_GET['id'] ? $_SESSION['Logado']['themer'] = $_POST['themer'] : '');

	$_SESSION['aviso'][0] = "Alteração realizada com sucesso!";
	header('location: '.$_ll['opt']['home'].(isset($_GET['en']) && $_GET['en'] == 'minhaconta' ? '&en=minhaconta' : '' ));
	break;
}