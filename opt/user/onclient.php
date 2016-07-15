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

switch (isset($_GET['ac']) ? $_GET['ac'] :  ''){
case 'new':
	echo jf_insert(PREFIXO.'lliure_admin', array('nome' => 'Novo usuario'));
	break;	
}