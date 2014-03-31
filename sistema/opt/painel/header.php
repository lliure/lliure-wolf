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


lliure::inicia('navigi');


$pagina = $_ll['app']['pasta']. 'home.php';;
if(!empty($_GET['p']) && file_exists($_ll['app']['pasta']. $_GET['p']. '.php'))
    $pagina = $_ll['app']['pasta']. $_GET['p']. '.php';
        
$_ll['app']['pagina'] = $pagina;