<?php
function adduser($args, $part){
	$llpath = LLPATH;
	
	switch($part){
		default:
			$_part = 'username';
			
			if(!empty($args[0])){
				$_part = 'password';
				$_SESSION['adduser_data']['user'] = trim($args[0]);
				Terminal::ask('Digite a senha: ', 'passwordconfirm');
			}else{
				Terminal::ask('Digite o login: ', 'password');
			}
		break;
		
		case 'password':
			$args = trim($args);
			if(!empty($args)){
				$_SESSION['adduser_data']['user'] = $args;
				Terminal::ask('Digite a senha: ', 'passwordconfirm', true);
			}else{
				Terminal::setCommandPart('username');
			}
		break;
		
		case 'passwordconfirm':
			$args = trim($args);
			if(!empty($args)){
				$_SESSION['adduser_data']['password'] = md5($args.'0800');
				Terminal::ask('Confirme a senha: ', 'database', true);
			}else{
				Terminal::setCommandPart(Terminal::DEFAULT_PART);
			}
		break;
		
		case 'database':
			$_SESSION['adduser_data']['passwordconfirm'] = md5(@$args.'0800');
			
			if($_SESSION['adduser_data']['passwordconfirm'] !== $_SESSION['adduser_data']['password']){
				echo 'As senha informada não corresponponde à original.';
				Terminal::ask('Digite a senha: ', 'passwordconfirm', true);
			}
			
			$dados = array(
				'login' => $_SESSION['adduser_data']['user'],
				'senha' => $_SESSION['adduser_data']['passwordconfirm']
			);
			
			if($erro = jf_insert(PREFIXO.'lliure_admin', $dados))
				echo $erro;
			else
				echo 'O usuário <span class="color2">'.$dados['login'].'</span> foi adicionado com successo';
				
			unset($_SESSION['adduser_data']);
			Terminal::endCommand();
		break;
	}
}
?>