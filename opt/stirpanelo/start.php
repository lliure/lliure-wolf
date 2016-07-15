<?php
/**
*
* lliure WAP
*
* @Versão 8.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

ll_historico('reinicia');

$botoes = array(
	array('href' => $backReal, 'fa' => 'fa-chevron-left', 'title' => $backNome)
	);

echo app_bar('Painel de controle', $botoes);
?>

<div class="painelCtrl">
	<div class="bloco">
		<h2>Configurações</h2>
		
		<div class="listp">
			<div class="inter">
				<a href="?opt=user"><img src="opt/stirpanelo/img/users.png" alt="" /></a>
				<a href="?opt=user"><span>Usuários</span></a>
			</div>
		</div>	
		
		<?php
		if(ll_tsecuryt()) {
			?>
			<div class="listp">
				<div class="inter link_idioma">
					<a href="opt/idiomas/idiomas.php"><img src="opt/stirpanelo/img/language.png" alt="" /></a>
					<a href="opt/idiomas/idiomas.php"><span>Idiomas</span></a>
				</div>
			</div>
			<?php
		}
		?>	
			
	
		<div class="listp">
			<div class="inter">
				<a href="opt/mensagens/sobre.php" class="llSobre"><img src="opt/stirpanelo/img/info.png" alt="" /></a>
				<a href="opt/mensagens/sobre.php" class="llSobre"><span>Sobre</span></a>
			</div>
		</div>	
			
	</div>
	
	<div class="bloco">
		<h2>Aplicativos</h2>
		<?php
		$appFolder = array();
		$erroAbrirDir = false;
		$consulta = "select * from ".PREFIXO."lliure_apps";
		$query = mysql_query($consulta);
		
		if(mysql_num_rows($query) > 0)
			while($dados = mysql_fetch_array($query))
			$aplicativos[$dados['pasta']] = $dados['nome'];
				
		if ($handle=opendir("app")) {
			while (false !== ($file = readdir($handle))) 
				if (strstr($file, '.') == false) 
						$appFolder[] = $file;
			 
			closedir($handle);
		} else {
			$erroAbrirDir = true;
		}		
		
		natcasesort($appFolder);
		
		if($erroAbrirDir == false) {
			foreach($appFolder as $chave => $file){
				if(isset($aplicativos[$file])){
					$icon = 'opt/stirpanelo/icon_defaulto.png';
					if(file_exists('app/'.$file.'/sys/ico.png'))
						$icon = 'app/'.$file.'/sys/ico.png';
					?>
					<div class="listp">
						<div class="inter">
							<a href="?app=<?php echo $file?>"><img src="<?php echo $icon; ?>" alt="<?php echo $file?>" /></a>
							<a href="?app=<?php echo $file?>"><span><?php echo $aplicativos[$file]?></span></a>
						</div>
					</div>
					<?php
					unset($appFolder[$chave]);
				}
				
			}
			
			
			foreach($appFolder as $chave => $file)
				if(ll_tsecuryt()) {
					$config = 'app/'.$file.'/sys/config.ll';
					
					$app_nome = $file;
					if(file_exists($config) && $appConfig = simplexml_load_file($config))
						$app_nome = $appConfig->nome;

					?>
					<div class="listp">
						<div class="inter">
							<a href="opt/stirpanelo/install.php?app=<?php echo $file?>" class="install"><img src="opt/instalilo/ico.png" alt="<?php echo $app_nome?>" /></a>
							<a href="opt/stirpanelo/install.php?app=<?php echo $file?>" class="install"><span><?php echo $app_nome; ?></span></a>
						</div>
					</div>
					<?php
				}
		} else {
			echo '<script type="text/javascript"> jfAlert("Houve um erro ao tentar abrir o diretório de aplicativos") </script>';			
		}
		?>
	</div>
</div>

<script type="text/javascript">
	$('.menu_rapido a, .install').jfbox({width: 420, height: 440});
	
	$('.link_idioma a').jfbox({width: 420, height: 440, addClass: 'idiomaBox'});
	
	$('.llSobre').jfbox({width: 420, height: 200, addClass: 'llSobre_box'});
</script>
