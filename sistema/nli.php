<?php
/**
*
* lliure WAP
*
* @Versão 6.4
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @LicenÃ§a http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/* NLI (not logged in) */
if(!file_exists("etc/bdconf.php"))
	header('location: index.php;');

require_once("etc/bdconf.php"); 
require_once("includes/functions.php"); 


if(isset($_GET['nli'])){
	if(file_exists('app/'.$_GET['nli'].'/nli.php'))
		$require = 'app/'.$_GET['nli'].'/nli.php';	
	
} elseif(isset($_GET['r'])){
	$direkti = array(
				'logout' => 'opt/loguser.php',
				'login' => 'opt/loguser.php',
				'rotinas' => 'opt/rotinas.php'
				);
				
	if(array_key_exists($_GET['r'], $direkti))
		$require =  $direkti[$_GET['r']];
}

if(!isset($require) || isset($_GET['r'])){
	require_once('includes/carrega_conf.php');
		
	$temo = 'lliure';
	if(isset($_ll['conf']->temoDefaulto))
		if(file_exists('temas/'.$_ll['conf']->temoDefaulto.'/dados.ll'))
		$temo = (string) $_ll['conf']->temoDefaulto;
		
	if(!isset($require)){		
		/*
		if (!in_array("mod_rewrite", apache_get_modules()))
			echo '<div class="mensagem"><span><strong>mod_rewrite</strong> não está ativo nesse servidor, por favor ative para que a segurança do lliure funcione corretamente</span></div>';
		*/
		$require = 'temas/'.$temo.'/login.php';
	}
}

require_once($require);

?>
