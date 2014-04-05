<?php
/**
*
* API navigi - lliure
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
header("Content-Type: text/html; charset=ISO-8859-1", true);
require_once("../../etc/bdconf.php"); 
require_once("../../includes/jf.funcoes.php"); 

$navigi = unserialize(jf_decode($_SESSION['logado']['token'], $_POST['token']));

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
} else {
	echo 403;
}
?>
