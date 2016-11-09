<?php /**
* Exibição de informações basicas do lliure
*
* @Versão do lliure 9.0
* @Pacote lliure
* @Sub-pacote stirpanelo
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*/ ?>

<div class="container-fluid">
	<div id="llSobre">

		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>

		<?php /*<h1><img src="opt/mensagens/img/logo_sobre.png" alt="lliure" /></h1> */ ?>

		<h1><div id="lliurelogo" style="width: 200px; max-width: 100%;"><?php require $_ll['tema']['path']. 'layout/logo.svg'; ?></div></h1>

		<span class="sigla">Web Application Platform</span>
		<span class="versao">Versão <?php echo  $_ll['conf']->versao; ?></span>

		<h2 class="h3">Obrigado por escolher o lliure</h2>
		<br>
		<br>

		<div class="rodape">
			<div class="container-fluid">
				<span><a href="http://www.lliure.com.br/aplicativos">Aplicativos</a></span>
				<span><a href="http://www.lliure.com.br/hub">Fórum</a></span>
				<span><a href="http://newsmade.lliure.com.br/lliure">Tutoriais</a></span>
				<span><a href="http://www.lliure.com.br/hospedagem">Hosted by lliureHost</a></span>
			</div>
		</div>
		<br>
	</div>
</div>

