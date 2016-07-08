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
switch(isset($_GET['ac']) ? $_GET['ac'] : '' ){

default:
	?>
	<div class="contBlog">
		<?php
		$consulta = "select a.*, b.titulo as postTitl
				from ".PREFIXO."newsmade_postagens_comentarios a
				
				left join ".PREFIXO."newsmade_postagens b
				on b.id = a.postagem
				
				order by a.id desc ";
		$query = mysql_query($consulta);
		$tr = mysql_num_rows($query); 

		$total_reg = "10";

		if (!isset($_GET['pagina'])) {
			$pc = "1";
		} else {
			$pc = $_GET['pagina'];
		} 
		
		$inicio = $pc - 1;
		$inicio = $inicio * $total_reg; 
				
		$tp = ceil($tr / $total_reg); 
		
		$limite = mysql_query($consulta." LIMIT $inicio,$total_reg ");
		?>

			
		<div class="menuBlog">
			<ul>
				<li class="top">Opções de Comentários</li>
				<li><a href="<?php echo $llHome.'&amp;p=blog'?>"><img src="<?php echo $_ll['tema']['icones'];?>/list_num.png"> Listar postagens</a></li>
			</ul>
		</div>
		
			
		<table class="table">
			<tr>
				<th>Comentário</th>		
				<th style="width: 218px;">Resposta à</th>		
				<th class="ico">Status</th>		
				<th class="ico">Excluir</th>		
			</tr>
			<?php
			$i = 1;
			while($dados = mysql_fetch_assoc($limite)){
				$alterna = ($i%2?'0':'1');
				?>
				<tr class="alterna<?php echo $alterna?>">
					<td>
						<div class="coment">
							<strong><?php echo $dados['titulo']?></strong></br>
							<p><?php echo nl2br(jf_substr($dados['comentario']))?></p>
						</div>
					</td>
					<td><a href="?plugin=newsmade&p=blog&id=<?php echo $dados['postagem']?>"><?php echo $dados['postTitl']?></td>
					<td class="ico"><a href="<?php echo $_ll['app']['pasta'].'comentarios.php?ac=status&amp;id='.$dados['id']?>" class="atdtComent" rel="<?php echo $dados['status']?>"><img src="<?php echo $_ll['tema']['icones'];?>/checkbox_<?php echo $dados['status'] == 0 ? 'un' : ''?>checked.png" alt="editar"/></a></td>
					
					<td class="ico"><a href="<?php echo $_ll['app']['pasta'].'comentarios.php?ac=delete&amp;id='.$dados['id']?>" title="excluir" class="excluir"><img src="<?php echo $_ll['tema']['icones'];?>/trash.png" alt="excluir"/></a></td>
				</tr>
				<?php		
				$i++;
			}
			?>
			</table>

			<div class="paginacao">
				<?php
				$anterior = $pc -1;
				$proximo = $pc +1;
				
				$url = "?plugin=newsmade&amp;p=comentarios";
				
				if($tp > 1){
					$tm = 3;
					
					$ini = $pc-$tm;
					if($ini < 1){
						$ini = 1;
					}

					$ult = $pc+$tm;
					if($ult > $tp){
						$ult = $tp;
					}
				
					for($i = $ini; $i <= $ult; $i++){
						echo ($i > 1?'<span>|</span>':'');
						echo "<span><a href='".$url."&amp;pagina=".$i."'".($i == $pc?"class='atual'":"").">".$i."</a></span>";
					}
				}
				?>
			</div>
			<?php
			if($tr < 1){
				?>
				<script type="text/javascript">
					jfAlert('Nenhum comentário encontrado', 1);
				</script>
				<?php
			}
			?>
	</div>

	<script type="text/javascript">
		$(function() {
			$('.atdtComent').click(function() {
				var link = $(this);
				if($(this).attr('rel') == 0){
					$().jfbox({carrega: link.attr('href')+'&status=1', abreBox: false}, function(){
						$(link).find('img').attr('src', 'imagens/icones/preto/checkbox_checked.png');
						$(link).attr('rel', '1');
					});
				} else {
					$().jfbox({carrega: link.attr('href')+'&status=0', abreBox: false}, function(){
						$(link).find('img').attr('src', 'imagens/icones/preto/checkbox_unchecked.png');
						$(link).attr('rel', '0');
					});
				}
				return false;
			});
			
			$('.excluir').click(function() {
				return confirmAlgo('esse comentário');
			});
		});
	</script>
	<?php
break;

case 'status':
	require_once("../../etc/bdconf.php"); 
	require_once("../../includes/jf.funcoes.php"); 
	
	jf_update(PREFIXO.'newsmade_postagens_comentarios', array('status' => $_GET['status']), array('id' => $_GET['id']));	
break;

case 'delete':
	require_once("../../etc/bdconf.php"); 
	require_once("../../includes/jf.funcoes.php"); 
	jf_delete(PREFIXO.'newsmade_postagens_comentarios', array('id' => $_GET['id']));
	
	$_SESSION['aviso'] = array('Comentário excluído com sucesso!', 1);
	header('location: ../../index.php?plugin=newsmade&p=comentarios'.(isset($_GET['pagina'])?'&pagina='.$_GET['pagina']:''));
break;

}
?>
