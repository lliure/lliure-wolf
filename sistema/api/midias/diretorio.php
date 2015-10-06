<?php

/* @var $midias Midias */
header ('Content-type: text/html; charset=ISO-8859-1'); require_once 'header.php';

$arquivos = array();

function ordenDosArquivos($arq1, $arq2){
    if ($arq1->data == $arq2->data)
		return strcmp($arq1->nome, $arq2->nome);
	
    return ($arq1->data > $arq2->data)? -1: 1;
}

function montaDiretorio($midias, &$arquivos){
	if (is_dir($midias->pasta())) {

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

				$data = filemtime($midias->pasta() . '/' . $arquivo);
				$size = filesize($midias->pasta() . '/' . $arquivo);

				$ars[] = (object)array(
					'data' => $data,
					'datas' => 'data-data="' . $data . '" data-size="' . $size . '" data-etc="' . $etc . '" data-nome="' . $arquivo . '"' . ($selecionado !== FALSE ? ' data-pre-cele="true" data-ordem="' . ($selecionado + 1) . '"' . ((($cor = $midias->getCorte($arquivo)) && !empty($cor)) ? ' data-corte="' . $cor . '"' : '') : ''),
					'classe' => 'mark' . ($selecionado !== FALSE ? ' celec' : ''),
					'img' => (!array_search($etc, array('ico', 'png', 'jpg')) ?
						'<img class="img-sem" src="api/navigi/img/ico.png">'
						:
						'<img class="img-ico" src="' . $midias->pastaRef() . '/' . $arquivo . '">'
					),
					'nome' => $arquivo
				);
			}

		}

		if (!isset($_GET['i']))
			usort($ars, 'ordenDosArquivos');

		foreach ($ars as $arquivo) {
			$arquivos[] = '
			<div class="file" ' . $arquivo->datas . '>
				<div class="ico">
					<div class="pos">
						<span class="' . $arquivo->classe . '">
							' . $arquivo->img . '
							<span class="checkbox"></span>
							<span class="deletar"></span>
							<span class="erro"></span>
						</span>
					</div>
				</div>
				<div class="nome">
					' . $arquivo->nome . '
				</div>
			</div>
		';
		}
	}
}montaDiretorio($midias, $arquivos);