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
	Entrada de get
	?i=capa38.jpg:200:0:o	onde ?i="imagem":"largura":"altura":"tipo"
	
	largura valor fixo
	altura se for igual a largura basta colocar "0";
	tipo padrao é p , os tipos poden ser: 
			"o" = Objetiva, redimencina para o tamanho final (adciona bordas brancas para completar a medida menor)
			"p" = Proporcional, mantendo a medida maior da imagem igual a medida menor da thumb
			"c" = Corte, corta a imagem centralizada no tamanho escolhido
			"r" = Relativo, a medida que estiver faltando é redimencionada para o valor relativo a original
*/
$http = '';

if(strstr($_GET['i'], 'http://')){
	$_GET['i'] = substr($_GET['i'], 7);
	$http = "http://";
}

$dados = explode(':', $_GET['i']);

$imagemOriginal = $http.$dados['0'];
$wid = ($dados['1'] == 0? $dados['2'] : $dados['1']);
$hei = (!isset($dados['2']) || $dados['2'] == 0? $dados['1'] : $dados['2']);

$thumbType = (isset($dados['3'])?$dados['3']:'p');

// Cria uma nova imagem a partir de um arquivo ou URL
if(stristr($imagemOriginal, ".jpg") != false){
	header("Content-type: image/jpeg");
	$im = imagecreatefromjpeg($imagemOriginal); 
} elseif(stristr($imagemOriginal, ".png") != false){
	header('Content-Type: image/png');
	$im = imagecreatefrompng($imagemOriginal); 
} elseif(stristr($imagemOriginal, ".gif") != false){
	header('Content-Type: image/gif');
	$im = imagecreatefromgif($imagemOriginal); 
}

$origem_x = ImagesX($im);
$origem_y = ImagesY($im);

switch($thumbType){
	case 'r':
		if($dados['1'] > $dados['2']) {
			$widn = $dados['1'];			
			$percent = $dados['1']*100/$origem_x;
			
			$hein = $percent*$origem_y/100;
		} else {
			$hein = $dados['2'];
			$percent = $dados['2']*100/$origem_y;
			
			$widn = $percent*$origem_x/100;
		}
		
		$left = 0;
		$top = 0;
		
		$img = imagecreatetruecolor($widn, $hein);
		break;

	case 'o':
	case 'p':
		//VERIFICA VALOR MAIOR
		if($origem_x > $origem_y) {
			$percentual = $wid*100/$origem_x;
		} else {
			$percentual = $hei*100/$origem_y;
		}

		$widn = intval ($origem_x * $percentual/100);
		$hein = intval ($origem_y * $percentual/100);
		
		if($thumbType == 'p' && $origem_x < $widn && $origem_y < $hein){
			$widn = $origem_x;
			$hein = $origem_y;
		}
		
		
		//Certifica que os tamanhos estão corretos
		if(($hein > $hei) or ($widn > $wid)){
			if($hein > $hei){
				$percentual = $hei*100/$hein;
			} elseif($widn > $wid){
				$percentual = $wid*100/$widn;
			}

			$widn = intval ($widn * $percentual/100);
			$hein = intval ($hein * $percentual/100);
		}
		
		if($thumbType == 'p'){

			$left = 0;
			$top = 0;
			
			$img = imagecreatetruecolor($widn, $hein);
		} else {
			$left = ($wid-$widn)/2;
			$top = ($hei-$hein)/2;
			
			$img = imagecreatetruecolor($wid, $hei);

			// Troca o fundo da imagem
			$white = imagecolorallocate($im, 255, 255, 255);
			imagefill($img, 0, 0, $white); 
		}			

		break;
	
	
	case 'c':
		//VERIFICA VALOR MAIOR
		if($wid > $hei) {
			$percentual = $wid*100/$origem_x;
		} else {
			$percentual = $hei*100/$origem_y;
		}

		$widn = intval ($origem_x * $percentual/100);
		$hein = intval ($origem_y * $percentual/100);
		
		//Certifica que os tamanhos estão corretos
		if(($hein < $hei) or ($widn < $wid)){
			if($hein < $hei){
				$percentual = $hei*100/$hein;
			} elseif($widn < $wid){
				$percentual = $wid*100/$widn;
			}

			$widn = intval ($widn * $percentual/100);
			$hein = intval ($hein * $percentual/100);
		}
		
		
		$left = ($wid-$widn)/2;
		$top = ($hei-$hein)/2;
		
		$img = imagecreatetruecolor($wid, $hei);
		
		break;
}

imagecopyresampled($img, $im, $left, $top, 0, 0, $widn, $hein, $origem_x, $origem_y);

if(stristr($imagemOriginal, ".jpg") != false){
	imagejpeg($img, NULL, 100);
} elseif(stristr($imagemOriginal, ".png") != false){
	imagepng($img);
} elseif(stristr($imagemOriginal, ".gif") != false){
	imagegif($img);
}

?>