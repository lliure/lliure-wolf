<?php
/**
*
* lliure WAP
*
* @Versão 8.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

//$_ll['operation_mode'] = 'oc';

$_GET = array_merge(array('onclient' => 'onclient'), $_GET);
require_once('nli.php');