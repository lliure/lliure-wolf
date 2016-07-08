<?php
/**
*
* Newsmade | lliure 5.x - 6.x
*
* @Versão 4
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
?>

<div class="boxCenter g_blogs">
<?php
	switch(isset($_GET['ac']) ? $_GET['ac'] : 'home'){
	case 'home':
		?>
		<div class="menu">
			<span class="botao">
				<a href="<?php echo $_ll['app']['onserver'], '&ac=gb_add';?>">Criar blog</a>
			</span>
		</div>
		
		<div class="listagem">
			<?php
			$navegador = new navigi();
			$navegador->tabela = PREFIXO . 'newsmade_blogs';
			$navegador->query = 'select * from ' . $navegador->tabela . ' order by nome asc';
			$navegador->exibicao = 'lista';
			$navegador->delete = true;
			$navegador->rename = false;
			$navegador->config = array(
						'link' => $_ll['app']['home'] . '&p=g_blogs&ac=edit&id='
						);
			$navegador->monta();
			?>
		</div>
		<?php
		break;
		
	case 'edit':
		$query = 'select * from '.PREFIXO.'newsmade_blogs where id = "'.$_GET['id'].'" limit 1';
		$blog = mysql_fetch_array(mysql_query($query));
		?>	
		<form action="<?php echo $_ll['app']['onserver'].'&ac=gb_edit&id='.$_GET['id'];?>" method="post" class="form">
			<fieldset>
				<div>
					<label>Nome</label>
					<input type="text" name="nome" value="<?php echo $blog['nome']?>"/>
					<span class="ex">id dinâmico: <strong><?php echo $blog['url']?></strong></span>
				</div>
			</fieldset>
			
			<div class="botoes">
				<button type="submit" class="confirm">Gravar</button>
				
				<a href="<?php echo $backReal;?>">Voltar</a>
			</div>
		</form>		
		<?php
		break;
	}
	?>
</div>