<?php

/* @var $midias Midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once 'header.php';

if(isset($_GET['removidos']))
	$midias->removidos($_GET['removidos']);

if(isset($_GET['inseridos']))
	$midias->inseridos($_GET['inseridos']);

//echo '<pre>'. print_r($midias, true). '</pre>';

$cortes = array(
	'c' => 'Corte',
	'o' => 'Objetivo',
	'p' => 'Relativo',
	'r' => 'Ajustado',
	'a' => 'Proporcional',
	'm' => 'Manual'
);

$corSet = $midias->cortes();

/* $_GET['i'] lista de arquivos exibidos */
$_GET['i'] = $midias->listaDeArquivos();
require_once 'diretorio.php';?>

<div id="api_midias" data-pagina="corte"<?php echo $midias->enbled();?>>

	<div class="topo">
		<div id="menu-corte">
			<h2><?php echo $midias->titulo();?></h2>

			<form id="midias-form-topo" class="form">
				<fieldset>
					<div class="direrio">
						Corte:&nbsp;
						<select id="midias-selec-corte">
							<?php foreach ($cortes as $corte => $nome){?>
								<?php if(array_search($corte, $corSet) !== FALSE){?>
									<option value="<?php echo $corte;?>"><?php echo $nome;?></option>
								<?php }?>
							<?php }?>
						</select>
						<span class="midias-comandos-axiliar" data-axilio="c" style="display: none;">
							&nbsp;| Corta a imagem centralizada no tamanho escolhido.
						</span>
						<span class="midias-comandos-axiliar" data-axilio="o" style="display: none;">
							&nbsp;| Redimencina para o tamanho final (adciona tranparencia para completar a medida menor).
						</span>
						<span class="midias-comandos-axiliar" data-axilio="p" style="display: none;">
							&nbsp;| Mantendo a medida maior da imagem igual a medida menor do corte.
						</span>
						<span class="midias-comandos-axiliar" data-axilio="r" style="display: none;">
							&nbsp;| Mantendo a medida menor da imagem igual a medida maior do corte.
						</span>
						<span class="midias-comandos-axiliar" data-axilio="a" style="display: none;">
							&nbsp;| Ajusta as dimensões da imagem para as do corte.
						</span>
						<span class="midias-comandos-axiliar" data-axilio="m" style="display: none;">
							&nbsp;| <label><input id="midias-auxilio-relacionado" type="checkbox" data-defalt="false"/>&nbsp;Relacionado</label>
							&nbsp;| Configure manualmente o tamanho do corte.
						</span>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

	<div class="meio">

		<div class="main">

			<div class="colu left">
				
				<div id="api-midias-icosToCortar" class="scroll">
					<div class="files">
						<?php foreach ($arquivos as $arquivo) echo urldecode($arquivo);?>
					</div>
				</div>
				
			</div>

			<div class="colu center">
				
				<div id="api-midias-areaCorte">
					<div class="area-de-corte">
						<div class="area-de-corte-main">
							<div class="area-de-corte-main-main">
								<div class="imgToCorte"></div>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="colu right">
				
				<div id="api-midias-icosCortardos" class="scroll">
					<div class="files">
						<?php foreach ($arquivos as $arquivo) echo urldecode($arquivo);?>
					</div>
				</div>

			</div>

		</div>
		
	</div>

	<div class="baixo">
		<div class="linha">
			Formate o corte das imagens selecionadas.
			<form class="form">
				<div class="botoes">
					<button id="midias-botao-cancelar" type="button">Cancelar</button>
					<?php if(!isset($_GET['socorte'])){?>
					<button id="midias-botao-anterior" type="button">Anterior</button>
					<?php }?>
					<button id="midias-botao-encerrar" class="confirm cortes comCortes <?php echo ((isset($_GET['socorte']))? 'socorte': 'fim');?>" type="button">Encerrar</button>
				</div>
			</form>
		</div>
	</div>
	
</div>

<script type="text/javascript">
	$('#api_midias').midias();
</script>