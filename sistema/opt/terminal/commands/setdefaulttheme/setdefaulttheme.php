<?php
function setdefaulttheme($args){
	$llpath = LLPATH;
	
	if(empty($args[0]))
		die('Parâmetros inválidos.');
		
	if(!file_exists($llpath.'temas/'.$args[0]))
		die('Tema inválido.');
	
	$conf_file_location = $llpath.'etc/llconf.ll';
	$conf_backupfile_location = null;
	
	if(isset($args['safe'])){
		$conf_backupfile_location = $conf_file_location.'.'.date('dmY-H-i-s').'.safe';
		copy($conf_file_location, $conf_backupfile_location);
	}
	
	$conf_file = simplexml_load_file($conf_file_location);
	$conf_file = xml2array($conf_file);
	$conf_file['temoDefaulto'] = $args[0];
	//print_r($conf_file);
	
	$jxml = new jf_xml('<?xml version="1.0" encoding="iso-8859-1"?><configuracoes/>');
	$jxml->jf_array2xml($conf_file);
	//echo '<pre>'.htmlentities($jxml->jf_pretty_xml()).'</pre>';
	
	if(file_put_contents($conf_file_location, $jxml->jf_pretty_xml()) === false){
		echo 'Não foi possivel gravar as alterações';
	}else{
		echo 'Configurações salvas com sucesso';
		if(isset($args['safe']))
			echo '<br/>Arquivo de backup criado em: '.$conf_backupfile_location;
	}
}
?>