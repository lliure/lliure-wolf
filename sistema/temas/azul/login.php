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
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/base.css" />
		<link rel="stylesheet" type="text/css" href="temas/azul/login/login.css" />
		
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/funcoes.js"></script>
		
		<title>lliure Web Application Platform</title>
	</head>

	<body>
	<div id="login">
		<img src="temas/azul/login/logo.png" class="logo" alt="lliure" />
		<?php
		if(isset($_GET['rt']) && $_GET['rt'] == 'falha')
			echo '<span class="mensagem">Login e/ou senha incorreto(s). Tente novamente</span>';
		
		$_SESSION['token'] = uniqid(md5(time()));
		jf_token('novo');
		?>
		
		<div id="loginBox">
			<form method="post" action="nli.php?r=login" id="form">
				<input type="hidden" name="token" value="<?php echo jf_token('exibe'); ?>">
				<fieldset>
					<div>
						<label>Usuário</label>
						<input type="text" name="usuario" class="user" autocomplete="off" />
					</div>	
					
					<div>
						<label>Senha</label>
						<input type="password" name="senha" />
					</div>
				</fieldset>
				<span class="botao"><button type="submit">Entrar</button></span>
			</form>
			<div class="both"></div>
		</div>
	</div>	

	</body>

	<head>
		<script type="text/javascript">
		$(document).ready(function(){
			$('.user').focus();
			
			<?php		
			if(isset($_GET['rt']) && $_GET['rt'] == 'falha'){
				echo '
					var tempo = 150;
					var left = -50;
					var right = 50;

					$("#loginBox").animate({
						left: right+"px"
						},tempo, function() {
							$(this).animate({
								left: left+"px"
								},tempo, function() {
								  $(this).animate({
										left: right+"px"
										},tempo-50, function() {
											$(this).animate({
												left: left+"px"
												},tempo-50, function() {
													$(this).animate({
														left: "0px"
														},tempo-50)
													})
											})
								})
					});
				';
			} else {
				echo 'gsqul();';
			}
			?>
		});
		</script>
	</head>
</html>
