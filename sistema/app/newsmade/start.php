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
require_once('sys/config.php');

$botoes = array(
	array('href' => $backReal, 'fa' => 'fa-chevron-left', 'title' => $backNome),
	array('href' => $llHome.'&amp;p=blog', 'fa' => 'fa-rss', 'title' => 'Blog'),
	array('href' => $llHome.'&amp;p=midia', 'fa' => 'fa-book ', 'title' => 'Mídias')
);

echo app_bar('Newsmade', $botoes);

$pagina = 'blog';

if(isset($_GET['p']))
	if(file_exists($_ll['app']['pasta'].$_GET['p'].'.php'))
		$pagina = $_GET['p'];

require_once($pagina.'.php');
?>

