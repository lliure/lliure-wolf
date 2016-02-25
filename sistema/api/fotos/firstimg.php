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
require_once("../../includes/jf.funcoes.php"); 

jf_update(PREFIXO.$_GET['tabela'], array($_GET['campo'] => $_GET['foto']), array('id' => $_GET['fk']));
?>
