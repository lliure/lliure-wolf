<?php

/**
*
* API Aplimo - lliure
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/* 

Documentação

// O nome Aplimo origina-se da junção das palavras aplikajô e temo 


## Utilização

	$aplikajo = new aplimo();
	$aplikajo->nome = 'Aplimo';

	$aplikajo->menu('Página incial', 'home');

	$aplikajo->sub_menu('Configuração');
	$aplikajo->sub_menu_item('Clientes', 'clientes');
	$aplikajo->sub_menu_item('Contratos', 'contato');

	$aplikajo->menu('Faturas', 'faturas');
	$aplikajo->menu('Audiências', 'audiencias');
	$aplikajo->menu('Consultas', 'consultas');

	$aplikajo->monta();	
	
	
	**************    Explicação    **************
	
	Iniciando a classe
	$aplikajo = new aplimo();
	
	Define o nome do aplicativo (que vai aparecer na barra superiror)
	$aplikajo->nome = 'Teste';
	
	Define um link no menu
	$aplikajo->menu('Página incial', 'home');
	
	Define inicio de um sub-menu
	$aplikajo->sub_menu('Configuração');
	
	Define os links do sub-menu criado
	$aplikajo->sub_menu_item('Clientes', 'clientes');

*/


class aplimo{
	var $nome = '';
	var $menu = array();
	var $h_menu = array();
	var $smalt = null;
	var $js = null;
	
	function __construct() {		

	}
	
	function menu($titulo, $url){
		$this->menu[] = array(
						'titu' => $titulo,
						'link' => $url
						);
	}
	
	function sub_menu($titulo, $folder = null){
		$array = array(
						'titu' => $titulo,
						'link' => array()
						);
						
		if(empty($folder))
			$this->menu[] = $array;
		else
			$this->menu[$folder] = $array;			
		
		$this->smalt = array_keys($this->menu);
		$this->smalt = array_pop($this->smalt );
	}
	
	function sub_menu_item($titulo, $url, $mark = null){
		
		$this->menu[$this->smalt]['link'][] = array(
					'titu' => $titulo,
					'link' => $url,
					'mark' => (!empty($mark) ? explode(',', $mark.','.$url)  : array($url))
					);
	}
	
	function hc_menu($texto, $mod, $tipo = 'botao', $orientacao = null, $class = null, $compl = null){
		$name = null;
		//$mod = null;
		
		switch($tipo){
		case 'botao':
			$mod = '"url": "'.$mod.'",';
			break;
		
		case 'input':
			$name = '"name": "'.$class.'",';
			
		case 'botao_js':
			$mod = '"js": "'.jf_encode('aplimo', $mod).'",';
			break;
		}
		
		$data = '{	"texto": "'.$texto.'", 
					'.$mod.$name.'
					"orientacao": "'.$orientacao.'",
					"adjunct": "'.$compl.'",
					"class": "'.$class.'"
				}';

		
		$this->hc_menu_item($tipo, $data);
	}
	
	function hc_menu_item($type = 'a', $data = null){			
			/*	$this->hc_menu_item('a', '{"texto": "teste", "url": "http://google.com"}');	*/
			
			$data = json_decode($data, true);
			
			$this->hc_menu[] = array(
					'tipo' => $type,
					'texto' => $data['texto'],
					'url' => isset($data['url']) ? $data['url'] : null,
					'align' => isset($data['align']) ? $data['align'] : 'right',
					'class' => isset($data['class']) ? $data['class'] : null,
					'adjunct' => isset($data['adjunct']) ? $data['adjunct'] : null,
					'name' => isset($data['name']) ? $data['name'] : null,
					'js' => isset($data['js']) ? $data['js'] : null
					);
	
			$tmp_menu = array_keys($this->menu);				
			return array_shift($tmp_menu);
		}
		
	function monta_hc_menu(){	
		echo '<div class="aplm_subheader">';		
		
		foreach($this->hc_menu as $key => $valor){
			if(isset($valor['js']))
			$valor['js'] = trim(jf_decode('aplimo', $valor['js']));
			
			echo $this->js;
			
			switch($valor['tipo']){
				case 'botao':
				case 'a':
					echo '<a href="'.$valor['url'].'" class="alg_'.$valor['align'].' aplm_botao '.$valor['class'].'">'.$valor['texto'].'</a>';
					break;
					
				case 'botao_js':
					echo '<a href="javascript: void(0)" '.$valor['adjunct'].' class="alg_'.$valor['align'].' aplm_botao '.$valor['class'].'">'.$valor['texto'].'</a>';
					
					$this->js .= $valor['js'];					
					break;
					
				case 'input':
					echo '<form action="'.$valor['url'].'" autocomplete="off" method="post" class="alg_'.$valor['align'].'  '.$valor['class'].' aplm_input"><input class=" aplm_input_'.$key.'" rel="'.$valor['texto'].'" name="'.$valor['name'].'" value="'.(isset($_GET[$valor['name']]) ? $_GET[$valor['name']] : '').'"/></form>';
					
					$this->js .= $valor['js'];	
					
					$this->js .= '$(".aplm_input_'.$key.'").jf_inputext();';				
					break;
			}
		}
		echo '</div>';	
	}
	
	function header(){
		global $_ll;
		
		$aktivigi_class = ' aktivigi ll_border-color ll_color';
		
		 if(!isset($_GET['apm'])){			
			$tmp_menu = array_keys($this->menu);
			$class = array_shift($tmp_menu);				
			
			$class_li[$class] = $aktivigi_class;
			
			if(!is_array($this->menu[$class]['link']))
				$_GET['apm'] = $this->menu[$class]['link'];
		}
		
		if(isset($_GET['sapm']) && file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/header.php'))
				require_once($_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/header.php');
				
		elseif(isset($_GET['apm']) && file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/header.php'))
				require_once($_ll['app']['pasta'] . $_GET['apm'] . '/header.php');
	}
	
	function onserver(){
		global $_ll;
		
		if(isset($_GET['sapm']) && file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/onserver.php'))
			$apm_load = $_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/onserver.php';
		elseif(file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/onserver.php'))
			$apm_load = $_ll['app']['pasta'] . $_GET['apm'] . '/onserver.php';
		
		require_once($apm_load);
	}
	
	function monta(){
		global $_ll;
		
		$total_reg = 30;
		$tr = 10;
		
		$aktivigi_class = ' aktivigi ll_border-color ll_color';
				
		$class_sub = null;
		$class_li = null;
		
		foreach($this->menu as $key => $valor){
			if(is_array($valor['link'])){
				$class_li[$key] = null;
				$class_sub[$key] = null;
				 
				foreach($valor['link'] as $lok => $defin){
					
					$class_li[$key.'-'.$lok] = null;
						
					if(	 	(isset($_GET['apm']) && $defin['link'] == $_GET['apm'])
						|| (isset($_GET['sapm']) && $key == $_GET['apm'] && in_array($_GET['sapm'], $defin['mark'])) ) {
							
						$class_li[$key.'-'.$lok] = $aktivigi_class;
						$class_sub[$key] = 'open_sub';
					}
				}
			} else {
				$class_li[$key] = null;
				
				if(isset($_GET['apm']) && $valor['link'] == $_GET['apm'] ){
					$class_li[$key] = $aktivigi_class;
					
					if(file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/header.php'))
						require_once($_ll['app']['pasta'] . $_GET['apm'] . '/header.php');
				}				
			}
		}	
		?>
		<div class="container cabecalho">
			<div class="menu">
				<h1><a href="<?php echo $_ll['app']['home'];?>"><?php echo $this->nome;?></a></h1>
			</div>
			
			<div class="centro">
				<div class="align">
					
				</div>
			</div>
			<div class="both"></div>
		</div>
		
		
		<div class="container corpo">
			<div class="menu">
				<ul>
					<?php					
					foreach($this->menu as $key => $valor){
						
						echo '<li class="'.$class_li[$key].'">';
	
						if(is_array($valor['link'])){
							?>
							<li> 
								<span <?php echo 'class="'.$class_sub[$key].'"' ?>><?php echo $valor['titu']; ?></span> 
								<ul <?php echo 'class="'.$class_sub[$key].'"' ?>>								
									<?php
									foreach($valor['link'] as $lok => $defin){
										$link = $_ll['app']['home'].'&apm='.$defin['link'];
									
										if(!empty($key))
											$link = $_ll['app']['home'].'&apm='.$key.'&sapm='.$defin['link'];
															
										echo '<li class="'.$class_li[$key.'-'.$lok].'">'
												.'<a href="'.$link.'">'.$defin['titu'].'</a>'
											.'</li>';	
									}
									?>									
								</ul>
							</li>
							<?php
						} else {
							echo '<a href="'.$_ll['app']['home'].'&apm='.$valor['link'].'" class="link1">'.$valor['titu'].'</a>';
						}			
						echo '</li>';
					}

					?>
				</ul>
			</div>
			
			<div class="centro">
				<div class="align">			
					<?php
					if(!empty($this->hc_menu))		/**************************		Monta menu superior	**/		
						$this->monta_hc_menu();					
							
					$apm_load  = 'api/aplimo/ne_trovi.php';
                    
                    if(isset($_GET['sapm']) && file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/' . $_GET['sapm'] . '.php'))
						$apm_load = $_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/' . $_GET['sapm'] . '.php';
					elseif(isset($_GET['apm']) && file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/' . $_GET['apm'] . '.php'))
						$apm_load = $_ll['app']['pasta'] . $_GET['apm'] . '/' . $_GET['apm'] . '.php';
					elseif(!isset($_GET['sapm']) && file_exists($_ll['app']['pasta'] . 'home/home.php'))
						$apm_load = $_ll['app']['pasta'] . 'home/home.php';
				
					require_once($apm_load);
					?>

				</div>
			</div>
			<div class="both"></div>
		</div>
		
		
		<script src="js/jquery.jf_inputext.js" type="text/javascript" /></script>
		<script>		
			$('.menu ul li span').click(function(){	
				var box = $(this).closest('li').find('ul');

				
				if($(box).css('display') == 'none')
					$(this).addClass('open_sub');
				else 
					$(this).removeClass('open_sub');
				
				$(box).slideToggle();
			});
			
			$('#tudo').css('background', '#fff');
			
			<?php echo $this->js; ?>
		</script>
		<?php
	}
}
?>
