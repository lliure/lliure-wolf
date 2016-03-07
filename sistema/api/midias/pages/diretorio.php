<?php

/* @var $midias Midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once dirname(__FILE__). '/../load.php';

$arquivos = array();

function ordenDosArquivos($arq1, $arq2){
    if ($arq1->data == $arq2->data)
		return strcmp($arq1->nome, $arq2->nome);
	
    return ($arq1->data > $arq2->data)? -1: 1;
}

function montaDiretorio($midias, &$arquivos){
	if (is_dir($midias->pasta())){

		$ars = array();
		$arqs = array();

		if (!isset($_GET['i'])) {

			$diretorio = dir($midias->pasta());

			while ($arquivo = $diretorio->read())
				if (!($arquivo == '.' || $arquivo == '..') && !is_dir($midias->pasta() . DS . $arquivo))
					$arqs[] = $arquivo;

			$diretorio->close();

		} else {
			foreach ($_GET['i'] as $arquivo) {
				$aa = explode('/', $arquivo);
				$arqs[] = array_pop($aa);
			}
		}

		$d = $midias->listaDeArquivos();

		foreach ($arqs as $arquivo) {

			$tipos = explode(' ', $midias->tipos());
			$etc = strtolower(pathinfo($midias->pasta() . '/' . $arquivo, PATHINFO_EXTENSION));

			if (in_array($etc, $tipos) || $tipos[0] == null) {
				$selecionado = array_search($arquivo, $d);

				$f = $midias->pasta() . '/' . $arquivo;

				$data = filemtime($f);
				$cria = filectime($f);
				$size = filesize($f);

				if($img = ((function_exists('mime_content_type') && preg_match('/image\/(png|jpeg|gif|jpg)/', mime_content_type($f))) ||
					(!function_exists('mime_content_type') && preg_match('/(png|jpeg|gif|jpg)/', $etc))));
					list($width, $height, $type, $attr) = getimagesize($f);

				$ico = ($img? $midias->pastaRef() . '/' . $arquivo: 'api/navigi/img/ico.png');

				$ars[] = (object)array(
					'data' => $data,
					'cria' => $cria,
					'datas' => (
						' data-data="' . $data . '"'.
						' data-cria="' . $cria . '"'.
						' data-size="' . $size . '"'.
						' data-etc="' . $etc . '"'.
						' data-nome="' . $arquivo . '"'.
						($img? ' data-dimencoes="'. $width.'x'.$height . '"': '').
						($img? ' data-mimetype="'. image_type_to_mime_type($type) . '"': '').
						($img? ' '. $attr: '').
						($selecionado !== FALSE ? ' data-pre-cele="true" data-ordem="' . ($selecionado + 1) . '"'.
						((($cor = $midias->getCorte($arquivo)) && !empty($cor)) ? ' data-corte="' . $cor . '"' : '') : '')
					),
					'style' => 'background-image: url(\'' . $ico . '\');'. ($img && $width >= 150 && $height >= 150? ' background-size: cover;': ''),
					'classe' => ($selecionado !== FALSE ? ' celec' : ''). ($img? ' isImage' : ' isFile'),
					'img' => $ico,
					'nome' => $arquivo
				);
			}

		}

		if (!isset($_GET['i']))
			usort($ars, 'ordenDosArquivos');

		foreach ($ars as $arquivo) {
			$arquivos[] =
			'<button type="button" class="file' . $arquivo->classe . '" ' . $arquivo->datas . '>
				<div class="ico">
					<div class="pos">
						<div class="img-thumb" style="' . $arquivo->style . '"></div>
					</div>
				</div>
				<div class="nome">
					' . $arquivo->nome . '
				</div>
				<span class="mark">
					<span class="checkbox">
						<i class="unchecked fa fa-square-o"></i>
						<i class="checked fa fa-check-square-o"></i>
					</span>
					<span class="deletar">
						<i class="fa fa-trash-o"></i>
					</span>
					<span class="erro">
						<i class="fa fa-bug"></i>
					</span>
				</span>
			</button>';
		}
	}
}montaDiretorio($midias, $arquivos);