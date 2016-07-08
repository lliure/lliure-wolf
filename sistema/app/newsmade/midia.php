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

<script type="text/javascript">
	$(document).ready(function(){		
		$(".criaAlbum").click(function(){
			ll_load($(this).attr('href'), function(){
				$(document).jfaviso('Novo album criado com sucesso!', 1);
				navigi_start();
			});
		
			return false;
		});
		
	});
</script>

<div class="contBlog">
	<div class="menuBlog">
		<ul>
			<li class="top">Opções de fotos</li>
			<li><a href="<?php echo $_ll['app']['onserver'].'&ac=md_novoAlbum'?>" class="criaAlbum"><i class="fa fa-picture-o"></i> Criar álbum</a></li>
			<li><a href="<?php echo $llHome.'&amp;p=midia'?>"><i class="fa fa-th"></i> Listar albuns</a></li>
		</ul>
	</div>

	<?php
	if(!isset($_GET['album'])){ //////////////////////////////	LISTANDO POSTAGENS			
		$navegador = new navigi();
		$navegador->tabela = PREFIXO.'newsmade_albuns';
		$navegador->query = 'select * from '.$navegador->tabela.' order by nome asc' ;

		$navegador->delete = true;
		$navegador->rename = true;
		
		
		$navegador->config = array(	'link' => $llHome.'&p=midia&album=',
									'ico' => $_ll['app']['pasta'].'img/folder_image.png'
									);

		$navegador->monta();
		
	} else { //////////////////////////////	NOVA POSTAGEM
		if(!empty($_GET['album'])){
			$consulta = "select * from ".PREFIXO."newsmade_albuns where id =".$_GET['album'];
			$dados = mysql_fetch_assoc(mysql_query($consulta));
		}
		?>
		
		<div class="limitBlog">
			<h2>Dados do album</h2>		
			<form method="post" class="form" action="<?php echo $_ll['app']['onserver'].'&ac=md_salvarAlbum&album='.$_GET['album']?>">
				<fieldset>
					<div>
						<label>Nome</label>
						<input type="text" value="<?php echo (isset($dados['nome'])?$dados['nome']:'')?>" name="nome" />
						<span class="ex">Este é o nome do seu album. <strong>Campo obrigatório</strong></span>
					</div>	
				</fieldset>
				
				<div class="botoes">	
					<button type="submit" class="confirm" name="salvar">Salvar</button>
					<button type="submit" name="salvar-edit">Salvar e continuar editando</button>
				</div>
			</form>
			
			<div class="fotos">
				<h2>Fotos</h2>		
				<?php
				$galeriaAPI['tabela'] = "newsmade_albuns";				
				$galeriaAPI['ligacaoCampo'] = 'album';
				$galeriaAPI['ligacaoId'] = $_GET['album'];
				
				$galeriaAPI['dir'] = "../uploads/album";
				$galeriaAPI['ordem'] = true;
				
				$galeriaAPI['capaCampo'] = "capa";
				$galeriaAPI['capaFoto'] = (!empty($dados['capa'])?$dados['capa']:"");
				
				require_once('api/fotos/index.php');
				?>	
			</div>
			
			
			<div class="videos">
				<h2>Vídeos</h2>
				<form id="videoAdd" action="<?php echo $_ll['app']['pasta'].'ajax.videos.php?add='.$_GET['album']?>" method="post">
					<div>
						<span>Adicionar vídeo</span>
						<input type="text" name="url" id="urlVideo" value=""/>
						<span class="botao"><button type="submt">Adicionar</button></span>
						<span class="ex">Cole aqui a url do YouTube</span>
					</div>
				</form>
					
				<div class="videosMini">
					<?php
					$sql = mysql_query('select * from '.PREFIXO.'newsmade_albuns_videos where album = "'.$_GET['album'].'"');
					while($dados = mysql_fetch_assoc($sql)){
						?>
						<div>
							<a href="<?php echo $_ll['app']['pasta'].'ajax.videos.php?del='.$dados['id']?>" class="del"><img src="api/fotos/delete.png"></a>
							<img src="includes/thumb.php?i=http://i1.ytimg.com/vi/<?php echo $dados['video']?>/default.jpg:96:55:c"/>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>	
		
		<script type="text/javascript">
		$(function(){
				$('.videosMini div').bind({
				mouseenter :function(){
					($(this).children('.del')).stop(true, true).fadeIn(150);
				},
				
				mouseleave :function(){
					($(this).children('.del')).fadeOut(150);
				}
			});
			
			$('.del').click(function(){
				$(this).parent('.videosMini div').fadeOut(150);
			}).jfbox({abreBox: false});			
			
			$('.addVideo').jfbox({width: 350, height: 200});
			
			$('#videoAdd').jfbox({abreBox: true});
		});
		</script>
		<?php
	}
	?>
</div>
