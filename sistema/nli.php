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

/* NLI (not logged in) */
if(!file_exists("etc/bdconf.php"))
	header('location: index.php;');

require_once("etc/bdconf.php"); 
require_once("includes/jf.funcoes.php"); 

require_once('includes/carrega_conf.php');

$temo = 'lliure';
if(isset($_ll['conf']->temoDefaulto))
	if(file_exists('temas/'.$_ll['conf']->temoDefaulto.'/dados.ll'))
	$temo = (string) $_ll['conf']->temoDefaulto;
	
$require = 'temas/'.$temo.'/login.php';

if(isset($_GET['r'])){
	$direkti = array(
				'logout' => 'opt/loguser.php',
				'login' => 'opt/loguser.php',
				'rotinas' => 'opt/rotinas.php'
				);
				
	if(array_key_exists($_GET['r'], $direkti))
		$require =  $direkti[$_GET['r']];
}

require($require);

?>
