<?php
/**
*
* Inicialização do lliure sem autenticação
*
* @Versão do lliure 8.0
* @Pacote lliure
*
* Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
 * modo de entrada nli (no login) usado quando a requisição não
 * presisar ou não querer identificaão de login
 */

$_GET = array_merge(array('nli' => 'nli'), $_GET);
require_once('index.php');