<?php
/**
*
* lliure WAP
*
* @Vers�o 6.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licen�a http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once("../../etc/bdconf.php");
require_once('../../usr/stuff/jf.funcoes.php');
	
$tabela = PREFIXO.$_GET['tabela'];

$id = $_GET['id'];
$arquivo = $_GET['arquivo'];

$alter['id'] = $id;

@unlink($arquivo);

jf_delete($tabela, $alter);
?>
