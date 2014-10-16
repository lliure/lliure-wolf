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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="SHORTCUT ICON" href="imagens/layout/favicon.ico" type="image/x-icon" />
	<meta name="author" content="Jeison Frasson" />
	<meta name="DC.creator.address" content="jomadee@lliure.com.br" />
	<meta name="DC.creator " content="Jeison Frasson" />

	<?php
	lliure::loadCss();	
	echo (isset($_GET['app']) && !empty($_GET['app'])  && file_exists($_ll['app']['pasta'].'estilo.css') ?
		'<link rel="stylesheet" type="text/css" href="'.$_ll['app']['pasta'].'estilo.css">'
		: '' )
		
	.'<link rel="stylesheet" type="text/css" href="temas/'.$ll_tema->id.'/estilo.css">';
	
	
	lliure::loadJs();
	
	?>
	<title>lliure WAP</title>
</head>

<body>
<div id="tudo">
	<div id="topo">
		<span class="borda-esquerda"></span>
		<span class="borda-direita"></span>
		<div class="left">
			<a href="index.php" class="logoSistema"><img src="imagens/layout/blank.gif"/></a>
			<?php
			if(!empty($_GET) &&  ll_tsecuryt()){
				$keyGet = array_keys($_GET);
				if($keyGet['0'] == 'app' && !empty($_GET['app'])){
					?>
					<a href="javascript: void(0);" class="addDesktop" title="Adicionar essa página ao desktop"><img src="imagens/layout/add_desktop.png" alt="" /></a>
					<?php 
				}
			} 
			?>
		</div>
		

		<div class="right">			
			<div class="menu">
				<ul>
					<?php
					echo '<li><a href="index.php">Home</a></li>'
						.'<li><a href="?minhaconta">Minha conta</a></li>'
						.(ll_tsecuryt('admin') ? '<li><a href="?painel">Painel de controle</a></li>' : '')						
						.'<li><a href="nli.php?r=logout">Sair</a></li>';
					?>					
				</ul>
			</div>
			<?php 
			
			if(ll_tsecuryt('admin')){
				$consulta = "select b.* from 
							".PREFIXO."lliure_start as a
							
							left join ".PREFIXO."lliure_apps as b
							on a.idPlug = b.id	";
				$query = mysql_query($consulta);
				
				?>
				<div class="start" id="menu_rapido"  <?php echo (mysql_num_rows($query) == 0 ? 'style="display: none;"' : '' );?>>
					<div class="width">
						<span class="icone"></span>
						<ul id="appRapido">
							<?php
							while($dados = mysql_fetch_array($query)){								
								$icone = 'app/'.$dados['pasta'].'/sys/ico.png';
								
								?>
								<li id="appR-<?php echo $dados['id']?>">
									<a href="?app=<?php echo $dados['pasta']?>" title="<?php echo $dados['nome']?>">
										<img src="<?php echo $icone; ?>" alt="" />
									</a>
								</li>
								<?php
							}
							?>
						</ul>
					</div>
				</div>
				<?php				
			} 
			?>
		</div>
	</div>

	<div id="conteudo">
		<?php 
		$carrega = 'opt/stirpanelo/ne_trovi.php';
		if(file_exists($_ll['app']['pagina']))
			$carrega = $_ll['app']['pagina'];
			
		require_once($carrega);
		?>
		<div class="both"></div>
	</div>
	
	
	<div id="ll_rodape">
		<a href="http://www.lliure.com.br">lliure 6 Shiba-inu</a>
	</div> 
</div> 
</body>

<head>
	<script type="text/javascript">
		$(function(){
			<?php
			ll_alert();
			?>
		
			$('.addDesktop').click(function(){
				ll_addDesk();
			});
	
			ll_load('load');
			ll_sessionFix();

			$('#topo .right div.start').mouseenter(function(){		
				var size = $("#appRapido").find("li").size()*52;
				$("#appRapido").css({width: size});

				$(this).stop().animate({width: size+20}, 500, 'easeInOutQuart');
			}).mouseleave(function(){
			  $(this).stop().animate({width: '20'}, 500, 'easeInOutQuart');
			});		
		});
	</script>
</head>

</html>
