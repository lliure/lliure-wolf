<?php

/* @var $midias Midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once 'header.php';

if(isset($_GET['removidos']))
	$midias->removidos($_GET['removidos']);

if(isset($_GET['inseridos']))
	$midias->inseridos($_GET['inseridos']);

if(isset($_GET['corte']))
	$midias->setCortes($_GET['corte']);

//echo '<pre>'. print_r($midias, true). '</pre>';

require_once 'diretorio.php';?>

<div id="api_midias" data-pagina="midias"<?php echo $midias->enbled();?>>
	<div class="topo">
		<h2><?php echo $midias->titulo();?></h2>
		<form id="midias-form-topo" class="form">
			<input id="upload-input" class="upload-input" type="file" name="img" multiple/>
			<fieldset>
				<div class="direrio">
					<input class="div" readonly="readonly" type="test" value="<?php echo $midias->pastaRef();?>"/>
				</div>
				<div class="pesquisa">
					<input id="midias-pesquisa" type="text" placeholder="Pesquisa" />
				</div>
				<div class="upload botoes">
					<button class="confirm" type="button">UpLoad</button>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="meio">
		<div class="main">
			<div class="colu center left">
				<div class="content">
					<div id="api_midias_files">
						<div class="solte-aqui" style="display: none;">
							<div class="line"><img class="ausilio" src="imagens/layout/blank.gif"/>Solte Aqui!</div>
						</div>
						<div class="files scroll">
							<?php foreach ($arquivos as $arquivo) echo urldecode($arquivo);?>
						</div>						
					</div>
				</div>
			</div>
			<div class="colu right">
				<div id="midias-arquivos-selecionadas">
					<div class="icones files"></div>
					<div class="total"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="baixo">
		<div class="linha">
			<?php echo $midias->dica();?>
			<form class="form">
				<div class="botoes">
					<button id="midias-botao-cancelar" type="button">Cancelar</button>
					<?php if($midias->corte() !== NULL){?>
						<button id="midias-botao-proximo" class="arquivos comCortes" type="button" disabled="disabled">Proximo</button>
					<?php }else{?>
						<button id="midias-botao-encerrar" class="confirm arquivos fim" type="button">Encerrar</button>
					<?php }?>
				</div>
			</form>
		</div>
	</div>
	<div id="midias-msg-del">
		<div class="box">
			<h2>Apagando arquivo!</h2>
			<p>Apagar este arquivo pode prejudicar outros dados, pôs ele pode estar ligados a eles.</p>
			<form class="form">
				<fieldset></fieldset>
				<div class="botoes" style="text-align: right;">
					<button class="canselar" type="button">Canselar</button>
					<button class="confirm apagar" type="button">Apagar</button>
				</div>
			</form>
		</div>
	</div>
	<?php echo $midias->construirSelecionados();?>
</div>

<script type="text/javascript">
	$('#api_midias').midias();
</script>
