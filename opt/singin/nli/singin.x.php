<?php /* if(isset($_GET['rt']) && $_GET['rt'] == 'falha') echo '<span class="mensagem">Login e/ou senha incorreto(s). Tente novamente</span>'; */

//jf_token('novo'); ?>


<div class="centerVertical">
	<div class="container">
		<div class="row">
			<div class="sm-3-7 sm-offset-2-7 md-1-3 md-offset-1-3 lg-1-4 lg-offset-3-8">
				<div id="loginBox">
					<div class="text-center">
						<div id="lliurelogo" style=" width: 180px; max-width: 100%; "><?php require $_ll['tema']['path']. 'layout/logo.svg'; ?></div>
					</div>
					<br><br>
					<form action="<?php echo $_ll['opt']['nli']['onserver']. '&ac=login'; ?>" method="post">
						<input type="hidden" name="token" value="<?php echo Token::get(); ?>">
						<div class="form-group">
							<label for="formLoginInputUsuario">Usuário</label>
							<input type="text" name="usuario" class="form-control" id="formLoginInputUsuario">
						</div>
						<div class="form-group">
							<label for="formLoginInputSenha">Senha</label>
							<input type="password" name="senha" class="form-control" id="formLoginInputSenha">
						</div>
						<div class="text-right">
							<button type="submit" class="btn btn-primary">login</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.user').focus();

		<?php if(isset($_GET['rt']) && $_GET['rt'] == 'falha'){ ?>
			var tempo = 666;
			var balanco = 100;

			$("#loginBox").animate({ left:  (balanco / 2)}, (tempo * 0.25), function(){
			$(this)       .animate({ left: -(balanco / 2)}, (tempo * 0.25), function(){
			$(this)       .animate({ left:  (balanco / 2)}, (tempo * 0.17), function(){
			$(this)       .animate({ left: -(balanco / 2)}, (tempo * 0.17), function(){
			$(this)       .animate({ left:  0},             (tempo * 0.16)   )})})})});

		<?php } else { ?>
			gsqul();
		<?php } ?>
	});
</script>
