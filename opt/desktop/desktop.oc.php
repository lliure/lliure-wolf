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

switch(isset($_GET['ac']) ? $_GET['ac'] : 'erro'){ case 'addDesktop': ?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title ll_color">Adicionar ao desktop</h4>
	</div>
	<form class="jfbox" action="onserver.php?opt=desktop&ac=addDesktop">
		<div class="modal-body">
			<label>Nome</label>
			<input type="text" class="form-control" name="nome">
			<small>Adicione o link da pagina atual no desktop usando o nome acima.</small>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			<button type="submit" class="btn btn-primary btn-lliure">Criar</button>
		</div>
	</form>

<?php break; case 'addDesktopError': ?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title ll_color">Adicionar ao desktop</h4>
	</div>
	<div class="modal-body">
		<p>Ouve um erro ao tentar add esta pagina no desktop.</p>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	</div>

<?php break; case 'addDesktopSuccess': ?>

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title ll_color">Adicionar ao desktop</h4>
	</div>
	<div class="modal-body">
		<p>Esta pagina foi adicionado ao desktop com sucesso.</p>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
	</div>

<?php break; default: case 'erro':

break;}
