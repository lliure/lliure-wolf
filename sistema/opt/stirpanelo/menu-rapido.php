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

header("Content-Type: text/html; charset=ISO-8859-1",true);
require_once("../../etc/bdconf.php"); 
require_once("../../includes/jf.funcoes.php");

$caminho = 'opt/stirpanelo/menu-rapido.php';

if(isset($_GET['d'])){
	jf_delete(PREFIXO.'lliure_start', array('idPlug' => $_GET['d']));

	?>
	<script type="text/javascript">
		$(function(){
			$('#appR-<?php echo $_GET['d']?>').remove();

			if($('#appRapido').find('li').size() == 0)
					$('#menu_rapido').css({'display': 'none'});
		});
	</script>
	<?php
}
if(isset($_GET['a'])){
	jf_insert(PREFIXO.'lliure_start', array('idPlug' => $_GET['a']));
	
	$dados = mysql_fetch_array(mysql_query('select * 
						from '.PREFIXO.'lliure_apps 
						where id = "'.$_GET['a'].'"
						limit 1'));
	?>
	<script type="text/javascript">
		$(function(){
			$('#appRapido').append('<li id="appR-<?php echo $_GET['a']?>"><a href="?app=<?php echo $dados['pasta']?>" title="<?php echo $dados['nome']?>"><img src="app/<?php echo $dados['pasta']?>/sys/ico.png" alt="" /></a></li>');
			
			$('#menu_rapido').css({'display': 'block'});			
		});
	</script>
	<?php
}
?>

<h1>Acesso rápido</h1>

<h2>Na Barra</h2>
<div class="ARTem ARbox">
	<?php
	$query = mysql_query('select b.*
						from '.PREFIXO.'lliure_start a
						
						left join '.PREFIXO.'lliure_apps b
						on b.id = a.idPlug
						order by b.nome');
	if(mysql_num_rows($query) > 0){
		while($dados = mysql_fetch_array($query)){
			$icone = 'app/'.$dados['pasta'].'/sys/ico.png';
			
			/***/
			if(!file_exists('../../'.$icone))
				$icone = 'plugins/'.$dados['pasta'].'/sys/ico.png';			
			/***/
			
			?>
			<span class="app">
				<img src="<?php echo $icone; ?>" alt="" />
				<a href="<?php echo $caminho.'?d='.$dados['id']?>" class="jfbox"><img src="imagens/layout/delete.png"></a>
			</span>
			<?php
		}
		$bar = true;
	} else {
		echo "(vazia)";
		$bar = false;
	}
	?>
	<div class="both"></div>
</div>

<h2>Disponíveis</h2>
<div class="ARTem ARbox">
	<?php
	$query = mysql_query('select * 
						from '.PREFIXO.'lliure_apps 
						where id not in((select idPlug from '.PREFIXO.'lliure_start))
						order by nome');
	if(mysql_num_rows($query) > 0){
		while($dados = mysql_fetch_array($query)){
			$icone = 'app/'.$dados['pasta'].'/sys/ico.png';
			
			/***/
			if(!file_exists('../../'.$icone))
				$icone = 'plugins/'.$dados['pasta'].'/sys/ico.png';			
			/***/
			?>
			<span class="app">
				<img src="<?php echo $icone;?>" alt="" />
				<a href="<?php echo $caminho.'?a='.$dados['id']?>" class="jfbox"><img src="imagens/layout/checkmark.png"></a>
			</span>
			<?php
		}
	} else {
		echo $bar == true ?'Todos aplicativos disponíveis já foram adcionados' : 'Você não possui aplicativos instalados';
	}
	?>
	<div class="both"></div>
</div>

<script type="text/javascript">
	$('.app').mouseover(function(){
		$(this).children('a').show();
	}).mouseout(function(){
		$(this).children('a').hide();
	});
</script>
