<?php
/**
*
* lliure WAP
*
* @Versão 7.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if(empty($_GET['user'])){
	$botoes[] =	array('href' => $backReal, 'img' => $_ll['tema']['icones'].'br_prev.png', 'title' => $backNome);
	$botoes[] =	array('href' => $_ll['opt']['onclient'].'&ac=new', 'img' => $_ll['tema']['icones'].'user.png', 'title' => 'Criar usuário', 'attr' => 'class="criar"');
} else {
	$botoes[] =	array('href' => '?'.(isset($_GET['minhaconta']) ? 'desk' : 'usuarios') , 'img' => $_ll['tema']['icones'].'br_prev.png', 'title' => 'Voltar');
}

echo app_bar('Painel de usuários', $botoes);


if(empty($_GET['user'])){
	$navegador = new navigi();	
	$navegador->tabela = PREFIXO.'lliure_admin';
	$navegador->query = 'select * from '.$navegador->tabela.' where id != "'.$_SESSION['logado']['id'].'"'.(ll_tsecuryt() ? '' : ' and grupo != "dev"').' order by nome ASC';
	$navegador->delete = true;

	$navegador->config = array(
			'link' => $_ll['opt']['home'].'&user=',
			'ico' => 'imagens/layout/user.png'
			);
	$navegador->monta();
	?>
	<script type="text/javascript">
		$(document).ready(function(){			
			$(".criar").click(function(){
				ll_load($(this).attr('href'), function(){
					jfAlert('Novo usuário criado com sucesso!', 1);
					navigi_start();
				});
				return false;
			}); 
		});
	</script>
	<?php
} else {

	$consulta = 'select * from '.PREFIXO.'lliure_admin where id = "'.$_GET['user'].'" limit 1';
	
	$dados = mysql_fetch_assoc(mysql_query($consulta));
	
	extract($dados);
	?>

	<div class="boxCenter editUsuario">
		<form method="post" action="<?php echo $_ll['opt']['onserver'].'&ac=grava&id='.$_GET['user'].(isset($_GET['en']) && $_GET['en'] == 'minhaconta' ? '&en=minhaconta' : '' );?>"  enctype="multipart/form-data">
			<fieldset>
				<legend>Dados pessoais</legend>
				
				<div>
					<label>Nome <span>(obrigatorio)</span></label>
					<input type="text" value="<?php echo $nome?>" name="nome" />
				</div>
			
				<div>
					<label>E-mail</label>
					<input type="text" value="<?php echo $email?>" name="email" />
				</div>	
			
				<div>
					<label>Twitter</label>
					<input type="text" value="<?php echo $twitter?>" name="twitter" />
				</div>		
			
				<div class="user-fileup">
					<label>Foto</label>
					<?php
					$file = new fileup;
					$file->titulo = '';
					$file->rotulo = 'Selecionar imagem'; 
					$file->registro = $foto;
					$file->campo = 'foto'; 
					$file->extencao = 'png jpg';
					$file->form();
					?>
				</div>				
			</fieldset>
			
			<fieldset>
				<legend>Dados de acesso</legend>
				<div>
					<label>Login <span>(obrigatorio)</span></label>
					<input type="text" <?php echo !empty($login) ? 'value="'.$login.'" readonly class="inatv"' : ''; ?> name="login" />
					<span class="ex">Não poderá ser alterado posteriormente.</span>
				</div>
				
				<div>
					<label>Senha</label>
					<input type="password" value="" name="senha" />
					<span class="ex">Deixe em branco para manter a senha atual.</span>
				</div>	
			
				<?php	
				if(ll_tsecuryt('admin') && $_GET['user'] != $_SESSION['logado']['id']){
					?>
					<div>
						<label>Grupo de usuário</label>
						<select name="grupo">
							<?php
							$grupos = array('admin' => 'Administrador', 'user' => 'Usuário');
							ll_tsecuryt() ? $grupos['dev'] = 'Desenvolvedor' : '';
							
							
							if($llconf){
								$grupos_add = jf_iconv("UTF-8", "ISO-8859-1", (array) $llconf->usua_grup);
								
								if(!empty($grupos_add)){
									echo '<optgroup label="Sub-grupos">';
									foreach($grupos_add as $indice => $valor)
										echo '<option value="'.$indice.'" '.($grupo == $indice?'selected':'').'>'.$valor.'</option>';
									
									echo '</optgroup>';
								}
							}
							
							echo '<optgroup label="Grupos principais">';
							foreach($grupos as $indice => $valor)
								echo '<option value="'.$indice.'" '.($grupo == $indice?'selected':'').'>'.$valor.'</option>';					
							echo '</optgroup>';
							?>
						</select>
					</div>
					<?php
				}
				?>
			</fieldset>
			
			<span class="botao"><a href="<?php echo  $backReal;?>">Voltar</a></span>
			
			<span class="botao"><button type="submit" class="confirm">Gravar</button></span>
		</form>
	</div>
	<?php	
}
?>
