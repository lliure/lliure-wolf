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
*	
*	Entrada de get
*	para: o, p, c, r, a.
*	/300-200-p-o/teste.jpg					onde /"largura"-"altura"-["efeito 1"-["efeito 2"-]...]-"tipo"/"imagem"
*	
*	para: m.
*	/300-200-150-10-600-400-m/teste.jpg		onde /"largura"-"altura"-"Corte x"-"Corte y"-"Corte largura"-"Corte altura"-["efeito 1"-["efeito 2"-]...]-m/"imagem"
* 
*	Tipo padrao é p.
*	
*	Os tipos poden ser: 
*	"o" = Objetiva, redimencina para o tamanho final (adciona tranparencia para completar a medida menor)
*	"p" = Proporcional, mantendo a medida maior da imagem igual a medida menor da thumb
*	"c" = Corte, corta a imagem centralizada no tamanho escolhido
*	"r" = Relativo, a medida que estiver faltando é redimencionada para o valor relativo a original
*	"a" = Ajustado, Ajusta as dimensões da imagem para as do corte.
*	"m" = Manual, Configure manualmente o tamanho do corte.
*	
*	Efeitos implementados.
*	"p" = deixa a imagem preto e banca
*	
*/

/* @var $camCom string	Caminho completo*/
/* @var $imgNom string	Imagem nome*/
/* @var $params string	Parametros da thumb*/
/* @var $pasUpl string	Nome da pasta de uploads*/
/* @var $camBas string	Caminho basico da imagem a partir da pasta de uploads*/
/* @var $camImg string	Caminho da imagem com o nome dela*/
/* @var $tmbTps array	Tipos de thumbs disponiveis*/
/* @var $typTmb string	Tipo da thumb usando
/* @var $manTop int		Manual Top*/
/* @var $manLef int		Manual Left*/
/* @var $manWid int		Manual largula*/
/* @var $manHei int		Manual altura*/
/* @var $basWid int		dimancao base de largura*/
/* @var $basHei int		dimencao base de altura*/
/* @var $parEfc array	Efeitos a imagem*/
/* @var $imgEts string	estensao da imagam*/
/* @var $oriImg object	Objeto da imagem original*/
/* @var $oriWid float	Altura da imagem original*/
/* @var $oriHei float	Largura da imagem original*/
/* @var $basPro float	indice da proporcao entre largura e altura das dimensoes basicas */
/* @var $oriPro float	indice da proporcao entre largura e altura da imagem original */
/* @var $indRed float	indice usado para recalcular o valor da imagem */
/* @var $relWid float	relacao emtre a largura da largura basica e a largura do corte manual */
/* @var $relHei float	relacao emtre a altura da altura basica e a altura do corte manual */
/* @var $novLef float	posicao final do corte em x */
/* @var $novTop float	posicao final do corte em y */
/* @var $novWid float	largura final do corte em x */
/* @var $novHei float	largura final do corte em y */
/* @var $corTra cor		cor tranparente para faser o fundo tramparente */

if(!defined('DS'))
	/** Abrevicao de DIRECTORY_SEPARATOR*/
	define ('DS', DIRECTORY_SEPARATOR);

$camCom = $_SERVER['REQUEST_URI'];
$camCom = explode('/', $camCom);
$imgNom = urldecode(array_pop($camCom));
$params = array_pop($camCom);
$params = explode('-', $params);
$camCom = implode(DS, $camCom);
$pasUpl = 'uploads';
$camBas = substr($camCom, (strpos($camCom, $pasUpl) + strlen($pasUpl) + 1));
$camImg = $camBas. DS. $imgNom;

$tmbTps = array('p', 'o', 'c', 'a', 'r', 'm');
$typTmb = array_pop($params);
$typTmb = (in_array($typTmb, $tmbTps)? $typTmb: $tmbTps[0]);

switch ($typTmb){
	case 'm':
		$manLef = (int) $params[2];
		$manTop = (int) $params[3];
		$manWid = (int) $params[4];
		$manHei = (int) $params[5];
		unset($params[2], $params[3], $params[4], $params[5]);

	default:
		$basWid = (int) ($params[0] == 0 && !empty($params[1]) ? $params[1] : $params[0]);
		$basHei = (int) ($params[1] == 0 && !empty($params[0]) ? $params[0] : $params[1]);
		unset($params[0], $params[1]);
		
	break;
}

$parEfc = array_values($params);
$imgEts = strtolower(pathinfo($camImg, PATHINFO_EXTENSION));

// Cria uma nova imagem a partir de um arquivo ou URL
switch ($imgEts) {
	case 'jpg':
		$oriImg = imagecreatefromjpeg($camImg);
	break;

	case 'png':
		$oriImg = imagecreatefrompng($camImg);
		imagealphablending($oriImg, false);
		imagesavealpha($oriImg, true);
	break;

	case 'gif':
		$oriImg = imagecreatefromgif($camImg);
		imagealphablending($oriImg, false);
		imagesavealpha($oriImg, true);
	break;
}

$oriWid = ImagesX($oriImg);
$oriHei = ImagesY($oriImg);

$basPro = $basWid / $basHei;
$oriPro = $oriWid / $oriHei;

switch($typTmb){

	case 'c':
	case 'r':
	
		if($basPro > $oriPro)
			$indRed = $basWid / $oriWid;
		
		else
			$indRed = $basHei / $oriHei;
		
		$novWid = $oriWid * $indRed;
		$novHei = $oriHei * $indRed;
		
		if($typTmb == 'r'){

			$novLef = 0;
			$novTop = 0;
			$basWid = $novWid;
			$basHei = $novHei;
			
		}else{
			
			$novLef = ($basWid - $novWid) / 2;
			$novTop = ($basHei - $novHei) / 2;
			
		}	
		
	break;

	case 'o':
	case 'p':
	
		if($basPro < $oriPro)
			$indRed = $basWid / $oriWid;
		
		else
			$indRed = $basHei / $oriHei;
		
		$novWid = $oriWid * $indRed;
		$novHei = $oriHei * $indRed;
		
		if($typTmb == 'p'){

			$novLef = 0;
			$novTop = 0;
			$basWid = $novWid;
			$basHei = $novHei;
			
		}else{
			
			$novLef = ($basWid - $novWid) / 2;
			$novTop = ($basHei - $novHei) / 2;
			
		}	
		
	break;

	case 'a':

		$novLef = 0;
		$novTop = 0;

		$novWid = $basWid;
		$novHei = $basHei;

	break;

	case 'm':

		$relWid = $basWid / $manWid;
		$relHei = $basHei / $manHei;

		$novLef = - ($relWid * $manLef);
		$novTop = - ($relHei * $manTop);
		$novWid =	($relWid * $oriWid);
		$novHei =	($relHei * $oriHei);

	break;

}

if ($imgEts == 'png' or $typTmb == 'o'){

	header('Content-Type: image/png');
	
	$novImg  = imagecreatetruecolor($basWid, $basHei);
	imagealphablending($novImg, false);
	$corTra = imagecolorallocatealpha($novImg, 0, 0, 0, 127);
	imagefill($novImg, 0, 0, $corTra);
	imagesavealpha($novImg, true);
	imagealphablending($novImg, true);
	
	imagecopyresampled($novImg, $oriImg, $novLef, $novTop, 0, 0, $novWid, $novHei, $oriWid, $oriHei);
    
    if (in_array('p', $parEfc))
        imagefilter($novImg, IMG_FILTER_GRAYSCALE);
	
	imagepng($novImg);
	
}else if ($imgEts == 'jpg'){

	header("Content-type: image/jpeg");
	
	$novImg  = imagecreatetruecolor($basWid, $basHei);
	imagecopyresampled($novImg, $oriImg, $novLef, $novTop, 0, 0, $novWid, $novHei, $oriWid, $oriHei);
    
    if (in_array('p', $parEfc))
        imagefilter($novImg, IMG_FILTER_GRAYSCALE);
	
	imagejpeg($novImg, NULL, 100);
	
}else if ($imgEts == 'gif'){
	
	header('Content-Type: image/gif');
	
	$novImg  = imagecreatetruecolor($basWid, $basHei);
	imagealphablending($novImg, false);
	$corTra = imagecolorallocatealpha($novImg, 0, 0, 0, 127);
	imagefill($novImg, 0, 0, $corTra);
	imagesavealpha($novImg, true);
	imagealphablending($novImg, true);
	
	imagecopyresampled($novImg, $oriImg, $novLef, $novTop, 0, 0, $novWid, $novHei, $oriWid, $oriHei);
    
    if (in_array('p', $parEfc))
        imagefilter($novImg, IMG_FILTER_GRAYSCALE);
	
	imagegif($novImg);
	
}