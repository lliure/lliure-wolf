<?php
/**
*
* Rotinas pós autenticação
*
* @Versão do lliure 8.0
* @Pacote lliure
*
* Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$retorna_page = '';
// VERIFICA SE EXISTE ARQUIVO LLCONF.LL , SE NÃO EXISTIR CRIA UM VAZIO
if(!file_exists('etc/llconf.ll')){
	//Configurações basicas da instalação
	$installData = file_get_contents('opt/install/install.ll',0,null,null);
	$installData = json_decode($installData);
	
	$in = '<?xml version="1.0" encoding="utf-8"?>'."\n"
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
	$tema_path = 'opt/install/'.$installData->tema.'/' ;
} else {
	/* carrega as configurações basicas do sistema */
	require_once('includes/carrega_conf.php');
	require_once('opt/persona/persona.php');
}

if(!empty($_SESSION['ll_url'])){
	if($_SESSION['ll_url'] != "?")
		$retorna_page = $_SESSION['ll_url'];
		
	unset($_SESSION['ll_url']);
}


/***********************************************	SETA O TEMA PADRAO	*/
if(isset($_ll['conf']->grupo) && isset($_ll['conf']->grupo->{$_SESSION['ll']['user']['grupo']}->tema))
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
}


header('location: '.$_ll['url']['endereco'].$retorna_page);
?>
