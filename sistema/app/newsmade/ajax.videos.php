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
header("Content-Type: text/html; charset=ISO-8859-1",true);

require_once("../../etc/bdconf.php"); 
require_once("../../includes/jf.funcoes.php"); 

$case = array_keys($_GET);

switch($case[0]){

	case 'del':
		jf_delete(PREFIXO.'newsmade_albuns_videos', array('id' => $_GET['del']));
	break;

	case 'add':
		$pos = strripos($_POST['url'], '?')+1;
		
		$url = explode('&', substr($_POST['url'], $pos));
		$url =  substr($url[0], 2);
		
		jf_insert(PREFIXO.'newsmade_albuns_videos', array('video' => $url, 'album' => $_GET['add']));
		
		$carrega = '<div style="display: none;">'.
					'<a href="plugins/newsmade/ajax.videos.php?del='.$ml_ultmo_id.'" class="del"><img src="api/fotos/delete.png"></a>'.
						'<img src="includes/thumb.php?i=http://i1.ytimg.com/vi/'.$url.'/default.jpg:96:55:c"/>'.
					'</div>';
		?>
		<script type="text/javascript">
			$().ready(function(){
				$('.videosMini').append('<?php echo $carrega?>');
				$('.videosMini div:last').fadeIn();
				$('#urlVideo').val('');
			});
			
			
			$('.videosMini div').bind({
				mouseenter :function(){
					($(this).children('.del')).stop().fadeIn(150);
				},
				
				mouseleave :function(){
					($(this).children('.del')).fadeOut(150);
				}
			});
			
			$('.del').click(function(){
				$(this).parent('.videosMini div').fadeOut(150);
			}).jfbox({abreBox: false});		
		</script>
		<?php
	break;
}
?>
