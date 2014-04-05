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

$retorna_page = '';

// VERIFICA SE EXISTE ARQUIVO LLCONF.LL , SE NÃO EXISTIR CRIA UM VAZIO
if(!file_exists('etc/llconf.ll')){
	$in = '<?xml version="1.0" encoding="iso-8859-1"?>'."\n"
			.'<configuracoes>'."\n"
				."\t".'<idiomas>'."\n"
					."\t"."\t".'<nativo>pt_br</nativo>'."\n"
				."\t".'</idiomas>'."\n"
			.'</configuracoes>';
	

	if(($fp = @fopen('etc/llconf.ll', "w")) != false)
		fwrite($fp, $in);
		
	fclose($fp);
	
	chmod('etc/llconf.ll', 0777); 
}


if(!empty($_SESSION['ll_url'])){
	if($_SESSION['ll_url'] != "?")
		$retorna_page = $_SESSION['ll_url'];
		
	unset($_SESSION['ll_url']);
}


header('location: index.php'.$retorna_page);
?>
