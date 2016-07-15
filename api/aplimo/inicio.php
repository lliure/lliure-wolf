<?php
/**
*
* API Aplimo - lliure
*
* @Versão 6.2
* @Pacote lliure
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

ll::add('api/aplimo/estilo.css', 'css');
ll::add('api/aplimo/script.js', 'js');


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

	
	
	
	***********	Exemplo HC Menu
	
	$this->hc_menu_item('a', array('url' => $_ll['app']['home'].'&apm=jogo&sapm=estadio', 'texto' => 'Listar todos'));
*/


class aplimo{
	var $nome = '';
	var $menu = array();
	var $h_menu = array();
	var $smalt = null;
	var $js = null;
	private static $basePath = null;
		
	function __construct() {
		global $_ll;		
		self::basePath($_ll['app']['pasta']);
	}
	
	static function basePath($pasta){
		self::$basePath = $pasta;
	}
	
	function menu($titulo, $url, $fa = null){
		$array = array(
						'titu' => $titulo,
						'link' => $url
						);
						
		if($fa != null)
			$array['fa'] = $fa;
		
		$this->menu[] = $array;
	}
	
	function sub_menu($titulo, $folder = null, $fa = null){
		$array = array(
					'titu' => $titulo,
					'link' => array()
					);
					
		if($fa != null)
			$array['fa'] = $fa;
		
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
					'basePath' => self::$basePath,
					'mark' => (!empty($mark) ? explode(',', $mark.','.$url)  : array($url))
					);

	}
	
	function hc_menu($texto, $mod, $tipo = 'botao', $orientacao = null, $class = null, $compl = null){
		$name = null;
		
		switch($tipo){
		case 'botao':
			$data['url'] = $mod;
			break;
		
		case 'input':
			$data['name'] = $class;
			$data['url'] = $compl;
			
		case 'botao_js':
			$data['js'] = jf_encode('aplimo', $mod);
			break;
		}
		
		$data['texto'] = $texto;
		$data['orientacao'] = $orientacao;
		$data['adjunct'] = $compl;
		$data['class'] = $class;
		
		//$data = json_encode($data, true);
		
		$this->hc_menu_item($tipo, $data);
	}
	
	
	/**
	 * 
	 * Exemplo de utlização
	 * $this->hc_menu_item('a', '{"texto": "teste", "url": "http://google.com"}');
	 * 
	 * $this->hc_menu_item('a', array("texto" => "teste", "url" => "http://google.com"));
	 * 
	 * $type: passe o tipo do menu pode ser 
	 * 			   a: link comum
	 * 		botao_js: para um botão com ação javascrip
	 * 		   input: para criar um input
	 */
	function hc_menu_item($type = 'a', $data = null){			
			
			if(!is_array($data)){
				$data = utf8_encode($data); 
				$data = json_decode($data, true);
			}
			
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
			//$valor = jf_iconv2($valor);
			if(isset($valor['js']))
				$valor['js'] = trim(jf_decode('aplimo', $valor['js']));

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
					echo '<form action="'.$valor['url'].'" autocomplete="off" method="post" class="alg_'.$valor['align'].'  '.$valor['class'].' aplm_input"><input class=" aplm_input_'.$key.'" placeholder="'.$valor['texto'].'" name="'.$valor['name'].'" value="'.(isset($_GET[$valor['name']]) ? $_GET[$valor['name']] : '').'"/></form>';
					
					$this->js .= $valor['js'];				
					break;
			}
		}
		echo '</div>';	
	}
	
	function header(){
		global $_ll;
		$aktivigi_class = ' aktivigi';
		$_ll['titulo'] = strip_tags($this->nome);
	
		$this->class_sub = null;
		$this->class_li = null;
		
		if(!isset($_GET['apm'])){			
			$tmp_menu = array_keys($this->menu);
			$class = array_shift($tmp_menu);
			
			if(!is_array($this->menu[$class]['link']))
				$_GET['apm'] = $this->menu[$class]['link'];
		}
		
		
		foreach($this->menu as $key => $valor){
			
			if(is_array($valor['link'])){
				$this->class_li[$key] = null;
				$this->class_sub[$key] = null;
				$this->class_sub_titulo[$key] = null;
				 
				foreach($valor['link'] as $lok => $defin){
					
					$this->class_li[$key.'-'.$lok] = null;
					
					/* if(	(isset($_GET['apm']) && $defin['link'] == $_GET['apm']) */
					
					if((isset($_GET['sapm']) && $key == $_GET['apm'] && in_array($_GET['sapm'], $defin['mark'])) ) {
							
						self::$basePath = $defin['basePath'];
						$this->class_li[$key.'-'.$lok] = $aktivigi_class;
						$this->class_sub[$key] = 'open_sub';
						$this->class_sub_titulo[$key] = $aktivigi_class.' open_sub ll_background';
					}
				}
			} else {
				$this->class_li[$key] = null;
				
				if(isset($_GET['apm']) && $valor['link'] == $_GET['apm'] )
					$this->class_li[$key] = $aktivigi_class.' ll_background ll_border-color';
			}
		}
		
		if(isset($_GET['apm']) && file_exists(self::$basePath . $_GET['apm'] . '/header.php')) {
			$this->home = $_ll['app']['home'].'&apm='.$_GET['apm'];
			$this->onserver = $_ll['app']['onserver'].'&apm='.$_GET['apm'];
			$this->onclient = $_ll['app']['onclient'].'&apm='.$_GET['apm'];
				
			require_once(self::$basePath . $_GET['apm'] . '/header.php');
		}
		
		if(isset($_GET['sapm']) && file_exists(self::$basePath . $_GET['apm'] . '/'. $_GET['sapm'] .'/header.php')){
			$this->home = $_ll['app']['home'].'&apm='.$_GET['apm'].'&sapm='.$_GET['sapm'];
			$this->onserver = $_ll['app']['onserver'].'&apm='.$_GET['apm'].'&sapm='.$_GET['sapm'];
			$this->onclient = $_ll['app']['home'].'&apm='.$_GET['apm'].'&sapm='.$_GET['sapm'];
			
			
			require_once(self::$basePath . $_GET['apm'] . '/'. $_GET['sapm'] .'/header.php');	
		} 
	}
	
	function onserver(){
		global $_ll;
		
		if(isset($_GET['sapm']) && file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/onserver.php'))
			$apm_load = $_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/onserver.php';
		elseif(file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/onserver.php'))
			$apm_load = $_ll['app']['pasta'] . $_GET['apm'] . '/onserver.php';
		
		if(!isset($apm_load)){
			echo 'Arquivo <strong>onserver.php</strong> não encontrado em seu aplicativo';
			die();
		}
		require_once($apm_load);
	}
	function onclient(){
		global $_ll;
		
		if(isset($_GET['sapm']) && file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/onclient.php'))
			$apm_load = $_ll['app']['pasta'] . $_GET['apm'] . '/'. $_GET['sapm'] .'/onclient.php';
		elseif(file_exists($_ll['app']['pasta'] . $_GET['apm'] . '/onclient.php'))
			$apm_load = $_ll['app']['pasta'] . $_GET['apm'] . '/onclient.php';
		
		if(!isset($apm_load)){
			echo 'Arquivo <strong>onclient.php</strong> não encontrado em seu aplicativo';
			die();
		}
		require_once($apm_load);
	}
	
	function require_page(){
		global $_ll;		
		
		$apm_load  = 'api/aplimo/ne_trovi.php';
		
		if(isset($_GET['p']))
			$page = $_GET['p'];
		elseif(isset($_GET['sapm']))
			$page = $_GET['sapm'];
		else
			$page = $_GET['apm'];
		
        if(isset($_GET['sapm']) && file_exists(self::$basePath . $_GET['apm'] . '/'. $_GET['sapm'] .'/' . $page . '.php')){
			$apm_load = self::$basePath . $_GET['apm'] . '/'. $_GET['sapm'] .'/' . $page . '.php';
		} elseif(isset($_GET['apm']) && file_exists(self::$basePath . $_GET['apm'] . '/' . $page . '.php')) {
			$apm_load = self::$basePath . $_GET['apm'] . '/' . $page . '.php';
		} elseif(!isset($_GET['sapm']) && file_exists(self::$basePath . 'home/home.php')){
			$apm_load = self::$basePath . 'home/home.php';
		}
			
		
		require_once($apm_load);
	}
	
	function monta(){
		global $_ll;
		
		if(!isset($this->class_li)){
			echo 'Método header() não foi instanciado!';
			die();
		}
		
		$total_reg = 30;
		$tr = 10;

		?>
		<span id="apm_titulo"><a href="<?php echo $_ll['app']['home'];?>"><?php echo $this->nome;?></a></span>
		
		<div class="container corpo ll_background">
			<div class="menu">
				<ul>
					<li class="apm_menu_home "><a href="<?php echo $_ll['app']['home']?>" class="link1 ll_background-hover"><i class="fa fa-home"></i> Desktop</a></li>
				
					<?php					
					foreach($this->menu as $key => $valor){
						
						echo '<li class="'.$this->class_li[$key].'"> ';
	
						if(is_array($valor['link'])){
							
							?>
							
								<span <?php echo 'class="'.$this->class_sub_titulo[$key].' ll_background-hover"' ?>><?php echo (isset($valor['fa']) ? '<i class="fa '.$valor['fa'].'"></i>' : '').$valor['titu']; ?></span> 
								<ul <?php echo 'class="'.$this->class_sub[$key].'"' ?>>								
									<?php
									foreach($valor['link'] as $lok => $defin){
										$link = $_ll['app']['home'].'&apm='.$defin['link'];
									
										if(!empty($key))
											$link = $_ll['app']['home'].'&apm='.$key.'&sapm='.$defin['link'];
															
										echo '<li class="'.$this->class_li[$key.'-'.$lok].' ">'
												.'<a href="'.$link.'">'.$defin['titu'].'</a>'
											.'</li>';	
									}
									?>									
								</ul>
						
							<?php
						} else {
							echo '<a href="'.$_ll['app']['home'].'&apm='.$valor['link'].'" class="link1 ll_background-hover'.$this->class_li[$key].'">'.(isset($valor['fa']) ? '<i class="fa '.$valor['fa'].'"></i> ' : '').$valor['titu'].'</a>';
						}			
						echo '</li>';
					}
					?>
				</ul>
			</div>
			
			<div class="centro">		
				<?php
				if(!empty($this->hc_menu))		/**************************		Monta menu superior	**/		
					$this->monta_hc_menu();					
						
				$this->require_page();
				?>
				<div class="both"></div>
			</div>
			
		</div>
		
		
		<script src="js/jquery.prod-novo-impostoext.js" type="text/javascript" /></script>
		<script>		
			$('.menu ul li span').click(function(){	
				var box = $(this).closest('li').find('ul');

				
				if($(box).css('display') == 'none')
					$(this).addClass('open_sub');
				else 
					$(this).removeClass('open_sub');
				
				$(box).slideToggle();
			});			
			<?php echo $this->js; ?>
		</script>
		<?php
	}
}
?>
