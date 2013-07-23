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

switch(isset($_GET['r']) ? $_GET['r'] : 'login'){
case 'logout':
		session_destroy();
		header('location: nli.php');
	break;
	
case 'login':
	if(isset($_SESSION['logado'])){	
		header('location: nli.php?r=rotinas');
		
	} elseif(!empty($_POST)){
		$falha = true;
		
		if( (!empty($_POST['usuario'])) && (!empty($_POST['senha'])) && jf_token($_POST['token'])){
			
			$senha = md5($_POST['senha']."0800");
			$usuario = jf_anti_injection($_POST['usuario']);
			
			
			
			if(isset($llconf->senhaDev)){
				
				$senhaDev = trim(isset($llconf->senhaDev->senha) ? $llconf->senhaDev->senha : $llconf->senhaDev);
				$usuarioDev = isset($llconf->senhaDev->usuario) ? $llconf->senhaDev->usuario : 'dev';
				
				if($usuario == $usuarioDev){
					if(($senhaDev = @file_get_contents($senhaDev)) != false){
						
						$senhaDev = trim($senhaDev);							
						$senha = md5($_POST['senha']);			

						if($senhaDev == $senha){
							$dadosLogin = array(
									'id' => '0',
									'nome' => 'Desenvolvedor',
									'grupo' => 'dev',
									'tema' => 'default'
								);
						}							
					}
				}
			}		
			
		
			if(!isset($dadosLogin)){
				$dadosUser = mysql_query('select * from '.PREFIXO.'lliure_admin where login = "'.$usuario.'" and senha = "'.$senha.'" limit 1');
				
				if(mysql_num_rows($dadosUser) > 0){
					$dadosUser = mysql_fetch_assoc($dadosUser);
					$dadosLogin = $dadosUser;
				} 			
			}
			
			
			if(isset($dadosLogin)){

				$tema_default = $temo;
				
				if($dadosLogin['themer'] == 'default')
					$dadosLogin['themer'] = $tema_default;
				else 
					if(file_exists('temas/'.$dadosLogin['themer'].'/dados.ll') == false)
						$dadosLogin['themer'] = $tema_default;
		
				$_SESSION['logado'] = array(
							'id' => $dadosLogin['id'],
							'nome' => $dadosLogin['nome'],
							'grupo' => $dadosLogin['grupo'],
							'tema' => $dadosLogin['themer'],
							'token' => jf_token('novo')
							);
							
				$falha = false;
			}

		}
		
		if($falha == false)
			header('location: nli.php?r=rotinas');
		else	
			header('location: nli.php?rt=falha');
	}
	
	break;
	
default:
	break;
}
?>
