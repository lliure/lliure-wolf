<?php
function setpwd($args){
	$llpath = LLPATH;
	
	if(empty($args[0]) || empty($args[1]))
		die('Parâmetros inválidos.');
	
	$dados = array(
		'senha' => md5($args[1].'0800')
	);
	
	if($erro = jf_update(PREFIXO.'admin', $dados, array('login' => $args[0]))){
		echo $erro;
	}else{
		$nalt = mysql_affected_rows();
		$regist = $nalt > 1? 'registros' : 'registro';
		$afet = $nalt > 1? 'afetados' : 'afetado';
		
		if($nalt == 0)
			$nalt = 'nenhum';
		
		echo 'Operação concluida <span class="color2">'.$nalt.' '.$regist.'</span> '.$afet;
	}
}
?>