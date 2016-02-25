<?php
/**
*
* API navigi - lliure
*
* @Versão 6.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once("../../etc/bdconf.php"); 
require_once("../../includes/jf.funcoes.php"); 

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
} else{
	echo 403;
}
?>