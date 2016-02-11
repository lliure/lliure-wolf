<?php
/**
 *
 * lliure WAP
 *
 * @Versão 7.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

lliure::inicia('navigi');
lliure::loadcss($_ll['opt']['pasta'].'usuarios.css');



if(isset($_GET['en']) && $_GET['en'] == 'minhaconta'){
	$_GET['user'] = jf_result(PREFIXO.'lliure_admin', array('login' => $_ll['user']['login']), 'id');

} elseif(!ll_tsecuryt('admin')){
	$_ll['opt']['pagina'] = "opt/mensagens/permissao.php";
}

?>