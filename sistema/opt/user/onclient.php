<?php
/**
 *
 * lliure WAP
 *
 * @Versão 7.0
 * @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
 * @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

switch (isset($_GET['ac']) ? $_GET['ac'] :  ''){
case 'new':
	echo jf_insert(PREFIXO.'lliure_admin', array('nome' => 'Novo usuario'));
	break;	
}