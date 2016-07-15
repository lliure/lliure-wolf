<?php
/**
*
* Consulta de usuário no banco
*
* @Versão do lliure 8.0
* @Pacote lliure
* @Sub-pacote singin
*
* Entre em contato com o desenvolvedor <lliure@glliure.com.br> http://www.lliure.com.br/
* Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


switch(isset($_GET['r']) ? $_GET['r'] : 'login'){
case 'logout':
	lliure::desautentica();
	header('location: '.$_ll['url']['endereco']);
	break;
	
case 'login':
	//session_destroy(); 	die();

	if(isset($_SESSION['ll']['user'])){	
		header('location: nli.php?r=rotinas');
	} elseif(!empty($_POST)){
		$falha = true;
				
		if( (!empty($_POST['usuario'])) && (!empty($_POST['senha'])) && jf_token($_POST['token'])){
			
			$senha = md5($_POST['senha']."0800");
			$usuario = jf_anti_injection($_POST['usuario']);
			/*
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
			*/
			if(!isset($dadosLogin)){
				$dadosUser = mysql_query('select * from '.PREFIXO.'lliure_admin where login = "'.$usuario.'" and senha = "'.$senha.'" limit 1');
				
				if(mysql_num_rows($dadosUser) > 0)
					$dadosLogin = mysql_fetch_assoc($dadosUser);
					
			}
			
			if(isset($dadosLogin)){
				$falha = false;
				
				if(lliure::autentica($dadosLogin['login'], $dadosLogin['nome'], $dadosLogin['grupo'], $dadosLogin['themer']) == false){
					$falha = true;
					echo 'Erro: Falha na autenticação de usuário. usr lliure::autentica';
					die();
				}
					
		
				
				
			}
		}
	
		$retorno = 'nli.php?';
		if(isset($_POST['retorno']))
			$retorno = $_POST['retorno']. (strpos($_POST['retorno'], '?') !== false || strpos($_POST['retorno'], '&') !== false ? '&' : '?' );
		
		if($falha == false)
			header('location: '.$retorno.'r=rotinas');
		else	
			header('location:'.$retorno.'rt=falha');
	}
	
	break;
	
default:
	break;
}
?>
