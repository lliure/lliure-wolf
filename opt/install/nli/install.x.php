<?php
/**
*
* lliure WAP
*
* @Versão 8.x
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/ ?>

<div class="container">
	<div class="row">
		<div class="sm-5-7 sm-offset-1-7 md-5-9 md-offset-2-9 lg-1-2 lg-offset-1-4">
			<div id="loginBox">

				<br><br>
				<div class="text-center">
					<div id="lliurelogo" style=" width: 180px; max-width: 100%; "><?php require $_ll['tema']['path']. 'layout/logo.svg'; ?></div>
				</div>
				<br><br>

				<h1>Instalação</h1>

				<?php switch(isset($_GET['ac'])? $_GET['ac']: 'home'){
					case 'home': ?>

						<form action="<?php echo $_ll['opt']['nli']['home']; ?>&ac=instalar" method="post">
							<input type="hidden" name="token" value="<?php echo Token::get(); ?>"/>

							<h2>Revisão de configurações</h2>

							<?php if(!is_writeable('etc')){ ?>
								<div class="panel panel-danger">
									<div class="panel-heading">
										<strong>Pasta:</strong> sistema/etc
									</div>
									<div class="panel-body">
										A pasta <strong>sistema/etc</strong> não tem permissão para escrita <br>
										Altere a permissão da pasta <strong>/.../sistema/etc</strong> para escrita e leitura do proprietário (755)
									</div>
								</div>
								<input type="hidden" name="etc" value="error"/>
							<?php } else { ?>
								<div class="panel panel-success">
									<div class="panel-heading">
										<strong>Pasta:</strong> sistema/etc
									</div>
									<div class="panel-body">
										A pasta <strong>sistema/etc</strong> está configurada corretamente
									</div>
								</div>
								<input type="hidden" name="etc" value="ok"/>
							<?php } ?>

							<?php if(!file_exists('../uploads')){ ?>
								<div class="panel panel-danger">
									<div class="panel-heading">
										<strong>Pasta:</strong> ../uploads
									</div>
									<div class="panel-body">
										A pasta <strong>../uploads</strong> não encontrada
										Crie manualmente a pasta /.../uploads com permissão de escrita e leitura proprietário (755)
									</div>
								</div>
								<input type="hidden" name="uploads" value="error-exists"/>
							<?php } else if(!is_writeable('../uploads')){ ?>
								<div class="panel panel-danger">
									<div class="panel-heading">
										<strong>Pasta:</strong> ../uploads
									</div>
									<div class="panel-body">
										A pasta <strong>../uploads</strong> não tem permissão para escrita
										Altere a permissão da pasta <strong>../uploads</strong>  para escrita e leitura (755)
									</div>
								</div>
								<input type="hidden" name="uploads" value="error-writeable"/>
							<?php } else { ?>
								<div class="panel panel-success">
									<div class="panel-heading">
										<strong>Pasta:</strong> ../uploads
									</div>
									<div class="panel-body">
										A pasta <strong>../uploads</strong> está configurada corretamente
									</div>
								</div>
								<input type="hidden" name="uploads" value="ok"/>
							<?php } ?>

							<h2>Configurações do banco de dados</h2>
							<fieldset>
								<div class="form-group">
									<div class="row">
										<label for="formInstalInputHost" class="col-sm-2 control-label">Host</label>
										<div class="col-sm-10">
											<input type="text" name="host" class="form-control" id="formInstalInputHost" placeholder="EXP: site.com ou 192.168.0.1">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<label for="formInstalInputLogin" class="col-sm-2 control-label">Login</label>
										<div class="col-sm-10">
											<input type="text" name="login" class="form-control" id="formInstalInputLogin" placeholder="EXP: root">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<label for="formInstalInputSenha" class="col-sm-2 control-label">Password</label>
										<div class="col-sm-10">
											<input type="password" name="senha" class="form-control" id="formInstalInputSenha">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<label for="formInstalInputTabela" class="col-sm-2 control-label">Tabela</label>
										<div class="col-sm-10">
											<input type="text" name="tabela" class="form-control" id="formInstalInputTabela" placeholder="EXP: lliure">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<label for="formInstalInputPrefixo" class="col-sm-2 control-label">Prefixo</label>
										<div class="col-sm-10">
											<input type="text" name="prefixo" class="form-control" id="formInstalInputPrefixo" value="ll_">
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<label for="formInstalInputPrefixo" class="col-sm-2 control-label"><br class="hidden-xs"/>Usuário</label>
										<div class="col-sm-10">
											<div class="radio">
												<label>
													<input type="radio" name="user[type]" value="defalt" checked>
													Usuário padrão: LOGIN: dev SENHA: dev
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="user[type]" value="custom">
													Usuário personalizado.
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="collapse" id="formInstalCollapseUser">
									<h2 style="margin-top: 0;">Usuário DEV</h2>
									<div class="form-group">
										<div class="row">
											<label for="formInstalInputLogin" class="col-sm-2 control-label">Login</label>
											<div class="col-sm-10">
												<input type="text" name="user[login]" class="form-control" id="formInstalInputLogin" placeholder="EXP: root">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<label for="formInstalInputSenha" class="col-sm-2 control-label">Password</label>
											<div class="col-sm-10">
												<input type="password" name="user[senha]" class="form-control" id="formInstalInputSenha">
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<br>
							<div class="form-group text-right">
								<button type="submit" class="btn btn-primary">Instalar</button>
							</div>
						</form>

						 <script>
							 (function($){
								 $(function(){
									 $(':input[name="user[type]"]').change(function(){
										 if( $(':input[name="user[type]"]:checked').val() == 'custom')
											 $('#formInstalCollapseUser').collapse('show');
										 else
											 $('#formInstalCollapseUser').collapse('hide');
									 });
								 });
							 })(jQuery)
						 </script>

					<?php break; case 'instalar':

						class lliureInstalling extends DB{

							public function __construct(){
								parent::__construct('');}

							public function run(){

								global $_ll;
								$msg = array();

								if(file_exists('etc/bdconf.php'))
									return array(array('success' => 'O lliure já foi instalado!'));

								if (empty($_POST)
								|| (empty($_POST['host']))
								|| (empty($_POST['login']))
								|| (empty($_POST['tabela']))
								|| (!isset($_POST['uploads']))
								|| (!isset($_POST['etc']))
								|| (Token::valid($_POST['token']) != true))
									return array(array('danger' => 'Por favor preencha todos os campos/requisitos, <a href="'. $_ll['opt']['nli']['home']. '">voltar</a>'));

								global
								$hostname_conexao,
								$username_conexao,
								$password_conexao,
								$banco_conexao;

								$hostname_conexao = $_POST['host'];
								$username_conexao = $_POST['login'];
								$password_conexao = $_POST['senha'];
								$banco_conexao    = self::antiInjection($_POST['tabela']);

								try {
									self::conectar();
								}catch (Exception $e){
									return array(array('danger' => 'Falha ao tentar configurar o banco de dados'));
								}

								$prefixo = (empty($_POST['prefixo']) || $_POST['prefixo'] == 'll_'? 'll_': $_POST['prefixo']);
								$tp = new leitor_sql($_ll['opt']['pasta']. 'sql/bd.sql', 'll_', $prefixo);
								foreach ($tp->getMsgs() as $m) $msg[] = $m;

								if($_POST['user']['type'] == 'defalt'){
									$tp = new leitor_sql($_ll['opt']['pasta']. 'sql/user.sql', 'll_', $prefixo);
									foreach ($tp->getMsgs() as $m) $msg[] = $m;
									$msg[] = array('info' => '<strong>Usuário padrão configurado</strong>, não se esqueça de alterar sua senha para sua seguraça.');

								}else{
									try{
										$t = parent::setTempTab($prefixo. 'lliure_admin')->insert(array(
											'id' => 1,
											'login' => $_POST['user']['login'],
											'senha' => Senha::create($_POST['user']['senha']),
											'nome' => 'Desenvolvedor',
											'email' => NULL,
											'twitter' => NULL,
											'foto' => NULL,
											'grupo' => 'dev',
											'themer' => 'default',
										));
										$msg[] = array('success' => '<strong>OK:</strong> INSERT INTO '. $prefixo. 'lliure_admin'. ($t > 0? ' | Affected rows: '. $t: ''));
									}catch (Exception $e){
										$msg[] = array('danger' => '<strong>ERROR:</strong> INSERT INTO '. $prefixo. 'lliure_admin | '. $e->getMessage());
									}
								}

								/** CRIA A PASTA UPLOADS **/
								if(file_exists($p = '../uploads/usuarios'))
									$msg[] = (array('warning' => 'Criar pasta <strong>'. $p. '</strong>: <strong>EXISTENTE! </strong>'));
								elseif (!@mkdir($p))
									$msg[] = (array('danger' => 'Criar pasta <strong>'. $p. '</strong>: <strong>ERRO! </strong>'));
								else
									$msg[] = (array('success' => 'Criar pasta <strong>'. $p. '</strong>: <strong>OK!</strong>'));

								/** copiar o aquivo thumb.php **/
								if(file_exists($p = '../uploads/thumb.php'))
									$msg[] = (array('warning' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>EXISTENTE! </strong>'));
								elseif (!@copy($_ll['opt']['pasta']. 'sup/sup.thumb.php.ll', $p))
									$msg[] = (array('danger' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>ERRO! </strong>'));
								else
									$msg[] = (array('success' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>OK!</strong>'));

								/** copiar o aquivo thumbs.php **/
								if(file_exists($p = '../uploads/thumbs.php'))
									$msg[] = (array('warning' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>EXISTENTE! </strong>'));
								elseif (!@copy($_ll['opt']['pasta']. 'sup/sup.thumbs.php.ll', $p))
									$msg[] = (array('danger' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>ERRO! </strong>'));
								else
									$msg[] = (array('success' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>OK!</strong>'));

								/** copiar o aquivo .htaccess **/
								if(file_exists($p = '../uploads/.htaccess'))
									$msg[] = (array('warning' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>EXISTENTE! </strong>'));
								elseif (!@copy($_ll['opt']['pasta']. 'sup/sup..htaccess.ll', $p))
									$msg[] = (array('danger' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>ERRO! </strong>'));
								else
									$msg[] = (array('success' => 'Copiar aquivo <strong>'. $p. '</strong>: <strong>OK!</strong>'));


								$in = file_get_contents($_ll['opt']['pasta']. 'sup/sup.bdconf.php.ll');
								$in = str_replace(
									array('.localhost.', '.root.', '.senha.', '.banco.','.prefixo.'),
									array($_POST['host'], $_POST['login'], $_POST['senha'], $_POST['tabela'], $prefixo)
								,$in);
								$in = "<?php\n\n". $in;

								if(file_put_contents($p = 'etc/bdconf.php', $in) !== false)
									$msg[] = (array('success' => 'Criar arquivo <strong>' . $p . '</strong>: <strong>OK!</strong> <br/>'));

								else
									$msg[] = (array('danger' => '
										Criar arquivo <strong>' . $p . '</strong>: <strong>ERRO!</span><br>
										O sistema por algum motivo não consegiu criar o arquivo de configuração, 
										crie manualmente um arquivo com o nome <strong>bdconf.php</strong> na pasta <strong>sistema/etc/</strong> com o seguinte conteúdo:<br>
										<textarea onclick="this.select()">'. $in .'</textarea><br>
										Depois de criar o arquivo atualize está tela
									'));

								return $msg;
							}
						}

						//echo '<pre>' . print_r($_SESSION, 1) . '</pre>';
						//echo '<pre>' . print_r($_POST, 1) . '</pre>';
						//echo '<pre>'; var_dump(empty($_POST)); echo '</pre>';
						//echo '<pre>'; var_dump(empty($_POST['host'])); echo '</pre>';
						//echo '<pre>'; var_dump(empty($_POST['login'])); echo '</pre>';
						//echo '<pre>'; var_dump(empty($_POST['tabela'])); echo '</pre>';
						//echo '<pre>'; var_dump(!isset($_POST['uploads'])); echo '</pre>';
						//echo '<pre>'; var_dump(!isset($_POST['etc'])); echo '</pre>';
						//echo '<pre>'; var_dump(ll::token($_POST['token']) != true); echo '</pre>';
						//echo '<pre>'; var_dump($_POST['user']['type'] == 'defalt'); echo '</pre>';

						$LI = new lliureInstalling();

						$warning = false;
						$danger = false;
						foreach($LI->run() as $k => $msg){
							list($status, $msg) = each($msg);
							$warning = ($status == 'warning'? true: $warning);
							$danger = ($danger == 'danger'? true: $danger);
							echo '<div class="alert well-sm alert-'. $status. '">'. $msg. '</div>';}

						if($danger){ ?>
							<p>Erro(s) que afetão o funcionamento do sistema ocoram. Confira o manual para ver posiveis soluções para ele(s).</p>
							<div class="text-right">
								<a href="<?php echo $_ll['opt']['nli']['home']; ?>" class="btn btn-primary">Voltar</a>
							</div>
						<?php }elseif($warning){ ?>
							<p>Aviso(s) que podem afetão o funcionamento do sistema apareceram. Confira o manual para ver posiveis soluções para ele(s).</p>
							<div class="text-right">
								<a href="<?php echo $_ll['opt']['nli']['home']; ?>" class="btn btn-default">Voltar</a>
								<a href="<?php echo $_ll['url']['endereco']; ?>" class="btn btn-primary">Login</a>
							</div>
						<?php }else{ ?>
							<p>O lliure foi instalado com sucesso, caso tenha usado o usuário padrão, não se esqueça de alterar sua senha para sua seguraça.</p>
							<div class="text-right">
								<a href="<?php echo $_ll['url']['endereco']; ?>" class="btn btn-primary">Login</a>
							</div>
						<?php }
					break;
					default: echo 'página não encontrada'; break; } ?>
			</div>
		</div>
	</div>
</div>