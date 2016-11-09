<?php
/**
*
* Rotinas p�s autentica��o
*
* @Vers�o do lliure 8.0
* @Pacote lliure
*
* Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* Licen�a http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$retorna_page = $_ll['url']['endereco'];

// VERIFICA SE EXISTE ARQUIVO LLCONF.LL , SE N�O EXISTIR CRIA UM VAZIO
if(!file_exists($FC = 'etc/llconf.ll')){

	//Configura��es basicas da instala��o
	//$installData = file_get_contents('opt/install/install.ll', 0, null, null);
	//$installData = json_decode($installData);

	//Configura��es basicas da instala��o
	ll::complila_conf($in = array(
		'versao' => '9 wolf',
		'idiomas' => array(
			'nativo' => 'pt_br',
		),
		'tema_default' => 'persona',
		'temas' => array(
			'persona' => 'usr/persona/',
		),
		'grupo' => array(
			'default' => array(
				'local' => 'default',
				'template' => 'persona',
				'execucao' => 'URL_NORMAL',
				'home_wli' => 'opt=desktop',
				'home_nli' => 'opt=singin',
			),
		),
	));

	//echo '<pre>'; var_dump($in); echo '</pre>';
	//echo '<pre>' . print_r($in, 1) . '</pre>';


	/*$in =
	//"<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n".
	//"<configuracoes>\n".
	//	"\t<idiomas>\n".
	//		"\t\t<nativo>pt_br</nativo>\n".
	//	"\t</idiomas>\n".
	//	"\t<tema_default>persona</tema_default>\n".
	//	"\t<versao>9 wolf</versao>\n".
	//	"\t<temas>\n".
	//		"\t\t<persona>usr/persona/</persona>\n".
	//	"\t</temas>\n".
	//	"\t<grupo>\n".
	//		"\t\t<default>\n".
	//			"\t\t\t<local>default</local>\n".
	//			"\t\t\t<template>persona</template>\n".
	//			"\t\t\t<execucao>URL_NORMAL</execucao>\n".
	//			"\t\t\t<home_wli>opt=desktop</home_wli>\n".
	//			"\t\t\t<home_nli>opt=singin</home_nli>\n".
	//		"\t\t</default>\n".
	//	"\t</grupo>\n".
	//"</configuracoes>";

	//file_put_contents($FC, $in);*/

	/*$in = '<?xml version="1.0" encoding="utf-8"?>'."\n"
			.'<configuracoes>'."\n"
				."\t".'<idiomas>'."\n"
					."\t\t".'<nativo>pt_br</nativo>'."\n"					
				."\t".'</idiomas>'."\n\n"
				
				."\t".'<tema_default>'.$installData->tema.'</tema_default>'."\n"
				."\t".'<versao>'.$installData->temaNome.'</versao>'."\n\n"
				
				."\t".'<temas>'."\n"
					."\t\t".'<'.$installData->tema.'>opt/install/'.$installData->tema.'/</'.$installData->tema.'>'."\n"
				."\t".'</temas>'."\n"
			.'</configuracoes>';
	
	if(($fp = @fopen('etc/llconf.ll', "w")) != false)
		fwrite($fp, utf8_encode($in));
		
	fclose($fp);
	
	chmod('etc/llconf.ll', 0777);
	
	$tema_default = $installData->tema;
	$tema_path = 'opt/install/'.$installData->tema.'/' ;*/

} else {
	/* carrega as configura��es basicas do sistema */
	//require_once('usr/stuff/carrega_conf.php');
	//require_once('usr/persona/persona.php');
}


/***********************************************	SETA O TEMA PADRAO	*/
/*if(isset($_ll['conf']->grupo) && isset($_ll['conf']->grupo->{$_SESSION['ll']['user']['grupo']}->tema))
	if(file_exists($_ll['conf']->temas->{$tema_default})){
		$tema_default = $_ll['conf']->grupo->{$_SESSION['ll']['user']['grupo']}->tema;
		$tema_path = (string) $_ll['conf']->temas->{$tema_default};
	}


if($_SESSION['ll']['user']['tema'] == 'default'){
	$_SESSION['ll']['user']['tema'] = array('id' => $tema_default);
	$_SESSION['ll']['user']['tema']['path'] = $tema_path;
} else {
	if(file_exists('temas/'.$_SESSION['ll']['user']['tema'].'/dados.ll') == false)
		$_SESSION['ll']['user']['tema'] = $tema_default;										
}*/


if(!empty($_SESSION['ll']['retorno'])){
	$retorna_page = $_SESSION['ll']['retorno'];
	unset($_SESSION['ll']['retorno']);}

header('location: '. $retorna_page); die();