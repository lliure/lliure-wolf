<?php
/**
*
* lliure WAP
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*
por padrao a tabela listada pela galeria é formada se seguinte forma ['id', '$galeriaAPI['ligacaoCampo']', 'foto', 'descricao'];

$galeriaAPI['tabela'] é a tabela pai
$galeriaAPI['ligacaoCampo'] é o campo em que fica o id de referencia a tabela pai
$galeriaAPI['ligacaoId'] é o id que faz referencia a tabela pai

$galeriaAPI['capaCampo'] é o campo que está na tabela pai que faz referencia a foto principal

$galeriaAPI['dir'] é o diretório onde vão ser armazenadas as imagens
*/


$galeriaAPI['tabela_app'] = $galeriaAPI['tabela'];
$galeriaAPI['tabela'] = $galeriaAPI['tabela'].'_fotos';
?>
<style>
	@import "api/fotos/estilo.css";
</style>

<script type="text/javascript" src="api/fotos/swfobject.js"></script>
<script type="text/javascript" src="api/fotos/jquery.uploadify.js"></script>

<div class="api-fotos">
	<div class="seletor" id="foto_file_"></div>
	<div id="api_fotos_galeria"></div>
	<div class="both"></div>
</div>

<script>
function carregaFotos(){
	$('#api_fotos_galeria').load('<?php echo 'api/fotos/fotos.php?tabela='.$galeriaAPI['tabela'].'&tabela_app='.$galeriaAPI['tabela_app'].'&campo='.$galeriaAPI['ligacaoCampo'].'&id='.$galeriaAPI['ligacaoId'].'&dir='.$galeriaAPI['dir'].(isset($galeriaAPI['capaCampo']) ? '&capa_campo='.$galeriaAPI['capaCampo'].'&capa_foto='.$galeriaAPI['capaFoto'] :''); ?>');
}

$(function(){
	carregaFotos();		
	
	$('#foto_file_').uploadify({
		'uploader'  : 'api/fotos/uploadify.swf',
		'script'    : 'api/fotos/uploadify.php?array=<?php echo $galeriaAPI['tabela'].'*'.$galeriaAPI['ligacaoCampo'].'*'.$galeriaAPI['ligacaoId']; ?>',
		'cancelImg' : 'api/fotos/cancel.png',
		'folder'    : '<?php echo $galeriaAPI['dir']; ?>',
		'auto'      : true,
		'fileExt'     : '*.jpg;*.png',
		'fileDesc'    : 'Arquivos de imagem (.jpg e .png)',
		'buttonText'  : 'Selecionar fotos',
		'multi'     : true,
		'onAllComplete'  : function(event,data) {
			carregaFotos();
		}
	});
});
</script>
