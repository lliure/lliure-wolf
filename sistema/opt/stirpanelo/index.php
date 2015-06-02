<?php
/**
*
* lliure WAP
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


$pagina = 'home';
if(!empty($_GET['painel']) && file_exists('painel/'.$_GET['painel'].'.php'))
	$pagina = $_GET['painel'];
	
require_once('opt/stirpanelo/'.$pagina.'.php');
?>
