<?php
/**
*
* lliure WAP
*
* @Versão 4.8.1
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

require_once("../includes/jf.funcoes.php"); 
switch(isset($ac) ? $ac : 'index'){
case 'index':
	session_start();
	?>
	<html>
	<head>
		<title>Intalador lliure</title>
		
		<link rel="stylesheet" type="text/css" href="../css/base.css">
		<link rel="stylesheet" type="text/css" href="css.css">
		
	</head>
	<body>
		<div class="container">
			<h1>Instalação do lliure</h1>
			<div class="instalador">
				<?php
				$ac = (isset($_GET['p']) ? $_GET['p'] : 'home');
				require('index.php');
				?>				
			</div>
		</div>
	</body>
	</html>
	<?php
break;

case 'home':
	?>
	<form method="post" action="index.php?p=instalar">
		<input type="hidden" name="token" value="<?php echo jf_token('novo'); ?>"/>
		<div class="testes">
			<h2>Revisão de configurações</h2>
			<?php		
			if(!is_writeable('../etc')){
				echo '	<span class="mens"><span class="erro ret">Erro</span> - A pasta <strong>/site/sistema/etc</strong> não tem permissão para escrita
							<span class="reso">Altere a permissão da pasta <strong>/.../sistema/etc</strong> para escrita e leitura do proprietário (700) </span>
						</span>';
			} else {
				echo '<span class="mens"><span class="ok ret">Ok</span> - A pasta <strong>/.../sistema/etc</strong> está configurada corretamente</span>
						<input type="hidden" name="etc" value="ok"/>
						';
			}
			
			if(!file_exists('../../uploads')){
				echo '	<span class="mens"><span class="erro ret">Erro</span> - A pasta <strong>/.../uploads</strong> não encontrada
							<span class="reso">Crie manualmente a pasta /.../uploads com permissão de escrita e leitura proprietário (700)</span>
						</span>';
			} else {
				if(!is_writeable('../../uploads')){
					echo '	<span class="mens"><span class="erro ret">Erro</span> - A pasta <strong>/.../uploads</strong> não tem permissão para escrita
								<span class="reso">Altere a permissão da pasta <strong>/site/uploads</strong>  para escrita e leitura (777)</span>
							</span>';
				} else {
					echo '<span class="mens"><span class="ok ret">Ok</span> - A pasta <strong>/.../uploads</strong> está configurada corretamente</span>
						<input type="hidden" name="uploads" value="ok"/>
						';
				}
			}
			?>
		</div>
	
	
		<h2>Configurações do banco de dados</h2>
		<fieldset>
			<div>
				<label>Host</label>
				<input name="host" value="localhost" />
			</div>
			<div>
				<label>Login</label>
				<input name="login" />
			</div>
			<div>
				<label>Senha</label>
				<input type="password" name="senha" />
			</div>			
			<div>
				<label>Tabela</label>
				<input name="tabela" />
			</div>		
			
			<div>
				<label>Prefixo</label>
				<input name="prefixo" value="ll_"/>
			</div>
				
		</fieldset>	
		<button type="submit">Instalar</button>
	</form>
	
	<?php
break;	


case 'instalar':
	$erro = false;
	
	if(!file_exists('../etc/bdconf.php')){				
		if(empty($_POST['host']) || empty($_POST['login']) || empty($_POST['tabela']) || !isset($_POST['uploads']) || !isset($_POST['etc']) || jf_token($_POST['token']) != true){
			echo 'Por favor preencha todos os campos, <a href="index.php">voltar</a>';
			break;
		}
		
		require_once("../includes/class.leitor_sql.php"); 
		$prefixo = $_POST['prefixo'];
			
		echo '<h2>Progresso de instalação</h2>';
	
		
		/*********************	CONECTA O BANCO	**/		
		$conexao = mysql_connect($_POST['host'], $_POST['login'], $_POST['senha']);
			
		if(mysql_select_db($_POST['tabela'], $conexao) == false){
			echo 'A tabela <strong>'.$_POST['tabela'].'</strong> não foi encontrada, por favor crie e atualize essa página';
			die();
		}
			

		/*********************	INSTALA AS TABELAS	**/
		$tp = new leitor_sql('bd.sql', 'll_', $prefixo);	
		
		
		/*********************	CRIA A PASTA UPLOADS	**/	
		$frase = '<span style="font-size: 13px;">- Criar pasta <strong>../../uploads/usuarios</strong>: <span style="color: #f00;">ERRO!</span> <br/></span>';
		if(!file_exists('../../uploads/usuarios')){
			if(@mkdir('../../uploads/usuarios', 0777))
				$frase =  '<span style="font-size: 13px;">- Criar pasta <strong>../../uploads/usuarios</strong>: <span style="color: #080;">OK!</span> <br/></span>';				
		} 
		echo $frase;
		
		$frase = '<span style="font-size: 13px;">- Copiar aquivo <strong>thumb.php</strong>: <span style="color: #f00;">ERRO! </span> <br/></span>';
		if(!file_exists('../../uploads/thumb.php')){
			if(@copy('sup/thumb.php', '../../uploads/thumb.php'))
				$frase =  '<span style="font-size: 13px;">- Copiar aquivo <strong>thumb.php</strong>: <span style="color: #080;">OK!</span> <br/></span>';
		}		
		echo $frase;
		
		$frase = '<span style="font-size: 13px;">- Copiar aquivo <strong>thumbs.php</strong>: <span style="color: #f00;">ERRO! </span> <br/></span>';
		if(!file_exists('../../uploads/thumbs.php')){
			if(@copy('sup/thumbs.php', '../../uploads/thumbs.php'))
				$frase =  '<span style="font-size: 13px;">- Copiar aquivo <strong>thumbs.php</strong>: <span style="color: #080;">OK!</span> <br/></span>';
		}		
		echo $frase;
		
		$frase = '<span style="font-size: 13px;">- Copiar aquivo <strong>uploads/.htaccess</strong>: <span style="color: #f00;">ERRO! </span> <br/></span>';
		if(!file_exists('../../uploads/.htaccess')){
			if(@copy('sup/thumbs_htaccess.php', '../../uploads/.htaccess'))
				$frase =  '<span style="font-size: 13px;">- Copiar aquivo <strong>uploads/.htaccess</strong>: <span style="color: #080;">OK!</span> <br/></span>';
		}
		echo $frase;
		
		/*********************	CRIA O ARQUIVO DE CONEXÃO	**/			
		$fd = fopen('conection_base.ll', "r");

		$in = "<?php\n";
		while(!feof( $fd )){
			$in .= fread( $fd, 1024 );
			
		}
		$in .= '?>';
		
		$in = str_replace(	array('.localhost.', '.root.', '.senha.', '.banco.','.prefixo.'),		
							array($_POST['host'], $_POST['login'], $_POST['senha'], $_POST['tabela'], $prefixo), $in );
		
		fclose($fd);	
		
		if(($fp = @fopen('../etc/bdconf.php', "w")) != false){
			fwrite($fp, $in);
			fclose($fp);
			echo '<span style="font-size: 13px;">- Criar arquivo <strong>bdconfig.php</strong>: <span style="color: #080;">OK!</span> <br/></span>';
		} else {
			echo '<span style="font-size: 13px;">- Criar arquivo <strong>conection.php</strong>: <span style="color: #f00;">ERRO! </span> <br/></span>
					<span>O sistema por algum motivo não consegiu criar o arquivo de configuração, crie manualmente um arquivo com o nome <strong>bdconf.php</strong> na pasta <strong>/.../sistema/etc</strong> com o seguinte conteúdo:</span>
					<textarea class="conteud" onclick="this.select()">'.$in.'</textarea>
					<span>Depois de criar o arquivo atualize está tela</span>
					';
		}
		
	} else {
		echo '<span style="font-size: 13px; color: #800; font-weight: bold;">O lliure já foi instalado!</span>';
		$erro = true;
	}
	
	if($erro == false)
		echo '<br/><br/> <h2 style="font-weight: normal;">O lliure foi instalado com sucesso, utilize o login <strong>dev</strong> e a senha <strong>dev</strong> para acessar o painel, não se esqueça de alterar sua senha para sua seguraça</h2>';
	?>
	
	
	
	<div style="padding-top: 10px;">
		<span style="font-size: 14px; font-weight: bold;"><a href="../index.php">Voltar para painel</a></span>
	</div>
	<?php
break;

default:
	echo 'página não encontrada';
break;
}
?>
