<?php
/**
*
* lliure WAP
*
* @Versão 4.7.1
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Colaborção    Rodirgo Dechen <rodrigo@grapestudio.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/*
	Entrada de get
	/300-200-o/teste.jpg	onde /"largura"-"altura"-"tipo"/"imagem"
	
	largura valor fixo
	altura se for igual a largura basta colocar "0";
	tipo padrao é p , os tipos poden ser: 
		"o" = Objetiva, redimencina para o tamanho final (adciona tranparencia para completar a medida menor)
		"p" = Proporcional, mantendo a medida maior da imagem igual a medida menor da thumb
		"c" = Corte, corta a imagem centralizada no tamanho escolhido
		"r" = Relativo, a medida que estiver faltando é redimencionada para o valor relativo a original
	
*/

$arrUrl = $_SERVER['REQUEST_URI'];
$arrUrl = explode('/', $arrUrl);

$imagem = array_pop($arrUrl);
$dimencao = array_pop($arrUrl);
$dimencao = explode('-', $dimencao);

$arrUrl = implode('/', $arrUrl);
$caminho = substr($arrUrl, strpos($arrUrl, 'uploads')+8);

$imagemOriginal = $caminho.'/'.$imagem;

$wid = ($dimencao[0] == 0 ? $dimencao[1] : $dimencao[0]);
$hei = (!isset($dimencao[1]) || $dimencao[1] == 0 ? $wid : $dimencao[1]);

$thumbType = (isset($dimencao[2]) ? $dimencao[2] : 'p');


// Cria uma nova imagem a partir de um arquivo ou URL
if(stristr($imagemOriginal, ".jpg") != false){
	$im = imagecreatefromjpeg($imagemOriginal);
	$type = 'jpg';
	
} elseif(stristr($imagemOriginal, ".png") != false){
	$im = imagecreatefrompng($imagemOriginal);
	//configunado a imagem para salvar utilisando a tranparencia
	imagealphablending($im, false);
	imagesavealpha($im, true);
	$type = 'png';
	
} elseif(stristr($imagemOriginal, ".gif") != false){
	$im = imagecreatefromgif($imagemOriginal);
	//configunado a imagem para salvar utilisando a tranparencia
	imagealphablending($im, false);
	imagesavealpha($im, true);
	$type = 'gif';
}

$origem_x = ImagesX($im);
$origem_y = ImagesY($im);

switch($thumbType){

	case 'r':
	
		if($dimencao[0] > $dimencao[1]) {
		
			$widn = $dimencao[0];			
			$percent = $dimencao[0]*100/$origem_x;
			$hein = $percent*$origem_y/100;
			
		} else {
		
			$hein = $dimencao[1];
			$percent = $dimencao[1]*100/$origem_y;
			$widn = $percent*$origem_x/100;
			
		}
		
		$wid = $widn;
		$hei = $hein;
		
		$left = 0;
		$top = 0;
		
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
		
			$wid = $widn;
			$hei = $hein;
			
		} else {
			$left = ($wid-$widn)/2;
			$top = ($hei-$hein)/2;
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
		
	break;

}

/*echo $wid." x ".$hei."<br/>";
echo $widn." x ".$hein;

die();*/

if ($type == 'png' or $thumbType == 'o'){

	header('Content-Type: image/png');
	
	$img  = imagecreatetruecolor($wid, $hei);
	imagealphablending( $img, false );
	$transparent = imagecolorallocatealpha( $img, 0, 0, 0, 127 );
	imagefill( $img, 0, 0, $transparent );
	imagesavealpha( $img,true );
	imagealphablending( $img, true );
	
	imagecopyresampled($img, $im, $left, $top, 0, 0, $widn, $hein, $origem_x, $origem_y);
	
	imagepng($img);
	
}else if ($type == 'jpg'){

	header("Content-type: image/jpeg");
	
	$img  = imagecreatetruecolor($wid, $hei);
	
	imagecopyresampled($img, $im, $left, $top, 0, 0, $widn, $hein, $origem_x, $origem_y);
	
	imagejpeg($img, NULL, 100);
	
}else if ($type == 'gif'){
	
	header('Content-Type: image/gif');
	
	$img  = imagecreatetruecolor($wid, $hei);
	imagealphablending( $img, false );
	$transparent = imagecolorallocatealpha( $img, 0, 0, 0, 127 );
	imagefill( $img, 0, 0, $transparent );
	imagesavealpha( $img,true );
	imagealphablending( $img, true );
	
	imagecopyresampled($img, $im, $left, $top, 0, 0, $widn, $hein, $origem_x, $origem_y);
	
	imagegif($img);
	
}
?>