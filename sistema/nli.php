<?php
/**
*
* lliure WAP
*
* @Vers„o 6.4
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licen√ßa http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/* NLI (not logged in) */
if(!file_exists("etc/bdconf.php"))
	header('location: index.php;');

require_once("etc/bdconf.php"); 
require_once("includes/functions.php"); 

$uArray = $_SERVER['REQUEST_URI'];
$_ll['url']['endereco'] = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
$_ll['url']['real'] = $_ll['url']['endereco'].substr($_SERVER['PHP_SELF'], 0, -7);

$_ll['url']['endereco'] = explode("/", $_ll['url']['endereco'].$uArray);

$uArray = explode("/", $uArray);
$nReal = explode('/', $_ll['url']['real']);

for($i = 0; $i <= count($nReal)-4; $i++)
	unset($uArray[$i]);

$uArray = array_values($uArray);

$_ll['url']['endereco'] = array_slice($_ll['url']['endereco'], 0, count($uArray) * -1);
$_ll['url']['endereco'] = implode('/', $_ll['url']['endereco']).'/';

if(isset($_GET['r'])){
	$direkti = array(
			'logout' => 'opt/loguser.php',
			'login' => 'opt/loguser.php',
			'rotinas' => 'opt/rotinas.php'
	);

	if(array_key_exists($_GET['r'], $direkti))
		$require =  $direkti[$_GET['r']];
} elseif(isset($_GET['nli'])){
	if(file_exists('app/'.$_GET['nli'].'/nli/nli.php'))
		$require = 'app/'.$_GET['nli'].'/nli/nli.php';	
	
} 

if(!isset($require) || isset($_GET['r'])){
	require_once('includes/carrega_conf.php');
		
	$temo = 'lliure';
		
	if(isset($_ll['conf']->tema_default))
		if(file_exists('temas/'.$_ll['conf']->tema_default.'/dados.ll'))
			$temo = (string) $_ll['conf']->tema_default;
		
	if(!isset($require)){
		$require = 'temas/'.$temo.'/login.php';
	}
}


require_once($require);

?>
