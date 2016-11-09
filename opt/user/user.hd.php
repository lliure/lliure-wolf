<?php
/**
 *
 * lliure WAP
 *
 * @Vers�o 7.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * @Licen�a http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

$user = new User();

if(isset($_GET['en']) && $_GET['en'] == 'minhaconta'){
	$_GET['user'] = $_ll['user']['id'];

} elseif(!ll_tsecuryt('admin')){
	$_ll['mensagens'] = 'detido';
	$_ll['opt']['pagina'] = "opt/mensagens/mensagens.x.php";}