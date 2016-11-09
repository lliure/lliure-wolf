<?php
/**
*
* lliure WAP
*
* @Versão 6.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

//ll::valida('admin');

ll::opt('sobre');
ll::api('jfbox');
ll::api('appbar');
ll::api('navigi');
ll::usr('basecss');
ll::opt('instalilo');

ll::add(__DIR__. '/estilo.css', 'css', 5);