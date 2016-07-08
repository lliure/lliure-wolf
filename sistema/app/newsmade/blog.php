<?php
/**
*
* Newsmade | lliure 8.x
*
* @Versão 5.0
* @Pacote lliure
* @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

switch(isset($_GET['ac']) ? $_GET['ac'] : 'home' ){
	case 'home':
		?>

		<div class="contBlog">
			<div class="menuBlog">
				<ul>
					<li class="top">Opções do blog</li>
					<li><a href="<?php echo $_ll['app']['onserver'].'&ac=b_criar&blog='.$_GET['blog']; ?>"><i class="fa fa-file-text-o"></i> Postar</a></li>
					<li><a href="<?php echo $_ll['app']['home'].'&amp;p=blog'?>"><i class="fa fa-list-ol"></i> Listar postagens</a></li>
					<?php
					if(ll_tsecuryt('admin'))
						echo '<li><a href="'.$_ll['app']['home'].'&amp;p=g_blogs"><i class="fa fa-newspaper-o"></i> Gerenciar blogs</a></li>';
					?>
				</ul>
			</div>
			
			<?php
			$query = mysql_query('select * from '.PREFIXO.'newsmade_blogs');
			while($dados = mysql_fetch_assoc($query)){
				$abas[$dados['id']] = $dados['nome'];
			}
			
			if(!empty($abas)){
				echo '<div class="abas">';
					foreach($abas as $key => $valor){
						echo '<span class="aba'.($_GET['blog'] == $key ?  ' ativo' : '').'"><a href="'.$_ll['app']['home'].'&blog='.$key.'">'.$valor.'</a></span>';
					}
					
					$default = @mysql_result(mysql_query('select id from '.PREFIXO.'newsmade_postagens where blog is NULL limit 1'));
					
					if(!empty($default))
						echo '<span class="aba'.($_GET['blog'] == 'default' ?  ' ativo' : '').'"><a href="'.$_ll['app']['home'].'&blog=default">Default</a></span>';
						
				echo '</div>';
			}
			
			$navigi = new navigi();
			$navigi->tabela = PREFIXO.'newsmade_postagens';
			$navigi->query = 'select *, 
					if(publicar = "1", titulo , concat("[Rascunho] - ", IFNULL(titulo, "NULL") ) ) as titulo, 
					if(titulo is null, 0, 1) as idd from '.$navigi->tabela.' where blog '.($_GET['blog'] != 'default' ? '= "'.$_GET['blog'].'"' : 'is NULL').' order by idd asc, id desc';
			$navigi->delete = true;
			$navigi->paginacao = 10;
			$navigi->exibicao = 'lista';
			$navigi->config = array(
				'link' => $_ll['app']['home'].'&amp;p=blog&amp;ac=editar&amp;id=',
				'coluna' => 'titulo'				
				);			
			$navigi->monta();			
			?>
		</div>

		<?php		
		break;
		
	case 'editar':
		$consulta = 'select * from '.PREFIXO.'newsmade_postagens where id ="'.jf_anti_injection($_GET['id']).'" limit 1';
		
		$dados = mysql_fetch_assoc(mysql_query($consulta));

		?>
		<div class="contBlog">
			<div class="menuBlog postInter">
				<ul>
					<li class="top">Gerenciar</li>
					<li><a class='midasBox' href="<?php echo $_ll['app']['onclient']."&p=ajax.gen_midias&notic=".$_GET['id']?>"><i class="fa fa-file-image-o"></i>Adicionar mídias</a></li>
					<?php /*<li><a class='jfbox' href="<?php echo $_ll['app']['pasta']."ajax.referencias.php?notic=".$_GET['id']?>"><img src=<?php echo $_ll['tema']['icones'];?"/globe_2.png"> Referências</a></li> */?>
				</ul>

				<div class="topicos">
					<div class="padding">	
						<span class="titulo">Tópicos</span>
					
						<div id="relacionados"></div>
						
						<form id="topicosForm" action="<?php echo $_ll['app']['pasta'].'ajax.topicos.php?id='.$_GET['id']?>" >
							<input type="text" name="topico" autocomplete="off" id="pesquisa"/>
							<div id="sugestao"></div>						
							<span class="botao"><button type="submit">Adicionar</button></span>
						</form>	
						
						
						<div class="both"></div>
					</div>
				</div>
			</div>
			
			<div class="limitBlog">
				<form method="post" class="form" id="formBlog" action="<?php echo $_ll['app']['onserver'].'&ac=b_alterar&id='.$_GET['id']; ?>">
				
					<div class="controles">
						<?php 
						
						$botao_alterar = ($dados['publicar'] == 0 ? 'Salvar rascunho' : 'Salvar');
						
						echo '<span class="botao"><button type="submit" name="salvar">'.$botao_alterar.'</button></span>';
						echo $dados['publicar'] == 0 
								? '<span class="botao"><button type="submit" class="confirm" name="public">Publicar</button></span>' 
								: '<span class="atualizado">Atualizado em '.date('d/m/Y', $dados['data_up']).'</span>';
							
						?>
					</div>
					
					<fieldset>
						<div id="url_box" <?php echo empty($dados['titulo']) ? 'style="display: none;"' : '' ?> >
							<?php echo '<span class="fras">Endereço permanente da postagem: <span id="url">'.$dados['url'].'</span></span> <input name="url" value="'.$dados['url'].'">'; ?>
						</div>
						
						<div class="column">
							<div>
								<label>Título</label>
								<input type="text" id="titulo" value="<?php echo stripslashes($dados['titulo']); ?>" name="titulo" />
							</div>	
							
							<div class="width" style="width: 160px;">
								<label>Data da postagem</label>
								<input type="text"id="data" value="<?php echo date('d/m/Y H:i',$dados['data']); ?>" name="data"/>
							</div>						
						</div>
					
						<div class="column">
							<div>
								<label>Subtítulo</label>
								<input type="text" value="<?php echo stripslashes($dados['subtitulo']); ?>" name="subtitulo" />
							</div>

							<div class="width" style="width: 160px;">
								<label>Modo de texto</label>
								<select name="modo" class="modo">
									<option value="1">Wysiwyg</option>
									<option value="0" <?php echo ($dados['modo'] == '0' ? 'selected' : ''); ?>>MarkDown</option>
								</select>
							</div>
						</div>		
						
						<div>
							<label>Introdução</label>
							<textarea name="introducao" class="intro tinymce"><?php echo stripslashes($dados['introducao']); ?></textarea>
						</div>
						
						<div>
							<label>Texto</label>
							<textarea name="texto" class="texto tinymce"><?php echo stripslashes($dados['texto']);?></textarea>
						</div>
					</fieldset>
				</form>				
			</div>
		</div>
		
		<script type="text/javascript">
			$(function(){
				$('#data').mask('99/99/9999 99:99');				
				
				
				$('.modo').change(function(){
					window.location.href = '<?php echo $_ll['app']['onserver'].'&ac=alt_modo&id='.$_GET['id'].'&modo='?>'+$(this).val();
				});
				
				<?php
				if($dados['modo'] == '1'){
					?>
					tinymce.init({
						selector: ".tinymce",
						plugins: [
								"advlist autolink autosave link lists hr",
								"code fullscreen nonbreaking"
						],

						toolbar1: "bold italic underline strikethrough removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | link unlink | code",
						
						menubar: false,
						toolbar_items_size: 'small'
					});
					<?php
				}
				
				if($dados['publicar'] == 0){
					?>
					$('#titulo').focusout(function(){
						var titulo = $(this).serializeArray();
						
						$.post('<?php echo $_ll['app']['onserver'].'&ac=b_gera_url&id='.$dados['id']; ?>', titulo, function(url){
							$('#url_box').show();
							$('#url').html(url);
							$('#url_box input').val(url);
						});
					});
					<?php
				}
				?>
				
				$(".jfbox").jfbox({width: 439, height: 400}); 
				$(".midasBox").jfbox({width: 600, height: 400, addClass: 'ajax-gen_midias'}); 
			
				$('#relacionados').load('<?php echo $_ll['app']['pasta'].'ajax.topicos.php?id='.$dados['id'];?>');
				$('#pesquisa').keyup(function(event){
					event.stopPropagation();
					
					if(event.keyCode != 13 && event.keyCode != 32){
						topico = false;
						$('#sugestao').hide();

						var termo = new Array();
						
						termo = $(this).val().split(',').reverse();
						termo = termo[0].replace(/^\s+|\s+$/g,"").replace(/ /gi, '+');
						
						if(termo.length > 2 && termo != '')
							$('#sugestao').load('<?php echo $_ll['app']['pasta'].'ajax.topicos.php?ac=consult&pesquisa='?>'+termo, function(){
								
								if(topico == true)
									$('#sugestao').stop(true, true).fadeIn(500);
							});	
					}
				});
			});
			
			$('#topicosForm').submit(function(){
				var campos =  $(this).serializeArray();
				
				$('#relacionados').load('<?php echo $_ll['app']['pasta'].'ajax.topicos.php?id='.$_GET['id']?>', campos, function(){
					$('#pesquisa').val(''); 
					$('#sugestao').hide();
				});
				
				return false;
			});
			

		</script>
		
		<?php
		break;
}
?>
