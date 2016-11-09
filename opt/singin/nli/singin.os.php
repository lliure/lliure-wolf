<?php global $_ll;
/**
*
* Consulta de usuário no banco
*
* @Versão do lliure 9.0
* @Pacote lliure
* @Sub-pacote singin
*
* Entre em contato com o desenvolvedor <lliure@glliure.com.br> http://www.lliure.com.br/
* Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

switch(isset($_GET['ac']) ? $_GET['ac']: (isset($_GET['r']) ? $_GET['r']: null)){
case 'login':

	if(!empty($_POST)){
		$falha = false;

		if( (!empty($_POST['usuario'])) && (!empty($_POST['senha'])) && Token::valid($_POST['token'])) {
			$user = new User();
			if (($dados = $user->exist($_POST['usuario'], $_POST['senha'])) === false) $falha = true;
			elseif (($auth = lliure::autentica($dados['login'], $dados['nome'], $dados['grupo'])) === false) $falha = true;}

		if(!$falha) header('Location: '. $_ll['url']['endereco']. '?opt=rotinas');
		else header('Location: nli.php?rt=falha');}

break;
case 'logout':
	
	lliure::desautentica();
	header('location: '. $_ll['url']['endereco']);

break;
default: break; }