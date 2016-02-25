<?php
/**
*
* lliure WAP
*
* @Versão 6.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once("../../etc/bdconf.php"); 
require_once("../../includes/functions.php"); 
	
$tabela = PREFIXO.$_GET['tabela'];

$id = $_GET['id'];
$arquivo = $_GET['arquivo'];

$alter['id'] = $id;

@unlink($arquivo);

jf_delete($tabela, $alter);
?>
