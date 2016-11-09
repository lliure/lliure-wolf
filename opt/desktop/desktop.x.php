<?php
/**
* lliure WAP
*
* @Versão 9.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/ ?>
<br>
<div class="container-fluid">
	<div class="bodyhome">

		<?php $navegador = new navigi();
		$navegador->tabela = PREFIXO."lliure_desktop";
		$navegador->query = 'select * from '.$navegador->tabela.' order by nome asc';
		$navegador->config = array('link_col' => 'link', 'ico_col' =>  'imagem');

		if(ll_tsecuryt('user')) {
			$navegador->rename = true;
			$navegador->delete = true;
		}

		$navegador->monta(); ?>

	</div>
</div>