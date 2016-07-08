<?php
/**
*
* Newsmade | lliure 5.x - 6.x
*
* @Versão 4
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if((!isset($_GET['p']) || $_GET['p'] == 'blog') && !isset($_GET['ac']) && !isset($_GET['blog'])){
	$coluna = jf_result(PREFIXO.'newsmade_blogs', '1=1', 'id');
	
	$coluna = empty($coluna) ? 'default' : $coluna ;
	
	header('location: '.$_ll['app']['home'].'&blog='.$coluna);
}

if(isset($_GET['p']) && $_GET['p'] == 'blog' && isset($_GET['ac']) && $_GET['ac'] == 'editar')
	lliure::loadJs('js/jquery.maskedinput.js');

$apigem = new api; 
$apigem->iniciaApi('navigi');
?>