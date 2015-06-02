<?php

/* @var $midias midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once 'header.php';

$ars = array();
$arqs = array();
$arquivos = array();

function ordenDosArquivos($arq1, $arq2){
    if ($arq1->data == $arq2->data)
		return strcmp($arq1->nome, $arq2->nome);
	
    return ($arq1->data > $arq2->data)? -1: 1;
}

if(is_dir($pasta)){

	if(!isset($_GET['i'])){
	
		$diretorio = dir($pasta);
		
		while($arquivo = $diretorio->read())
			if(!($arquivo == '.' || $arquivo == '..') && !is_dir($pasta. DS. $arquivo))
				$arqs[] = $arquivo;
		
		$diretorio->close();
		
	}else{
		foreach ($_GET['i'] as $arquivo){
			$arqs[] = array_pop(explode('/', $arquivo));
		}
	}
		
	$d = $midias->listaDeArquivos();
			
	foreach ($arqs as $arquivo){
		
		$tipos = explode(' ', $midias->tipos());
		$etc = strtolower(pathinfo($pasta. '/'. $arquivo, PATHINFO_EXTENSION));
		
		if(in_array($etc, $tipos)){
			$selecionado = array_search($arquivo, $d);

			$data = filemtime($pasta. '/'. $arquivo);
			$size = filesize($pasta. '/'. $arquivo);

			$ars[] = (object) array(
				'data'		=> $data,
				'datas'		=> 'data-data="'. $data. '" data-size="'. $size. '" data-etc="'. $etc. '" data-nome="'. $arquivo. '"'. ($selecionado !== FALSE? ' data-pre-cele="true" data-cele-ord="'. ($selecionado + 1). '"' .((($cor = $midias->corteDados($arquivo)) && !empty($cor))?  ' data-corte="'. $cor. '"': '') : ''),
				'classe'	=> 'mark'. ($selecionado !== FALSE? ' celec': ''),
				'img'		=> (!array_search($etc, array('ico', 'png', 'jpg'))?
					'<img class="img-sem" src="api/navigi/img/ico.png">'
				:
					'<img class="img-ico" src="'. $pastaRef. '/'. $arquivo. '">'
				),
				'nome'		=> $arquivo
			);
		}
		
	}
	
	if(!isset($_GET['i']))
		usort($ars, 'ordenDosArquivos');
		
	foreach ($ars as $arquivo){
		$arquivos[] = '
			<div class="file" '. $arquivo->datas. '>
				<div class="ico">
					<div class="pos">
						<span class="'. $arquivo->classe. '">
							'. $arquivo->img. '
							<span class="checkbox"></span>
							<span class="deletar"></span>
							<span class="erro"></span>
						</span>
					</div>
				</div>
				<div class="nome">
					'. $arquivo->nome. '
				</div>
			</div>
		';
	}
}

echo json_encode(midias::preparaParaJson($arquivos));