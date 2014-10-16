<?php
/**
*
* gerenciamento de APIs - lliure
*
* @Versão 6.1
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Colaboração Carlos Alberto Carucci
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
class lliure {
	public static $apps = array();
		
	public static function loadCss($css = null, $ecoa=true){
		global $_ll;
		
		if(!empty($css)){
			if(!in_array($css, $_ll['css']))
				$_ll['css'][] = $css;				
			
		}else{
			$ret = '';
			foreach($_ll['css'] as $css){
				$ret.= '<link rel="stylesheet" href="'.$css.'"/>'."\r\n\t";
			}
			
			if($ecoa)
				echo $ret;
			else
				return $ret;
		}
	}
	
	public static function loadJs($js = null, $ecoa = true){
		global $_ll;
		
		if(!empty($js)){
			if(!in_array($js, $_ll['js']))
				$_ll['js'][] = $js;
		}else{
			$ret = '';
			foreach($_ll['js'] as $js){
				$ret.= '<script type="text/javascript" src="'.$js.'"></script>'."\r\n\t";
			}
			
			if($ecoa)
				echo $ret;
			else
				return $ret;
		}
	}
	
	public static function iniciaApi($api){
		$api = strtolower($api);
		$loaded = true;
		
		$load = self::$apps[$api];
		if(empty($load['carregado']) || $load['carregado']==false)
			$loaded=false;
		
		if(!$loaded ){
			require_once $load['caminho'];
			
			if(!empty($load['css']) && !is_array($load['css']))
				$load['css'] = explode(';', $load['css']);
			
			if(!empty($load['js']) && !is_array($load['js']))
				$load['js'] = explode(';', $load['js']);
			
			if(isset($load['css']) && is_array($load['css'])){
				foreach($load['css'] as $css)
					self::loadCss($css);
			}
			
			if(isset($load['js']) && is_array($load['js'])){
				foreach($load['js'] as $js)
					self::loadJs($js);
			}
			
			$loaded = true;
			self::$apps[$api]['carregado'] = $loaded;
			
		}

		return $loaded;
	}
	
	public static function inicia($app){
		return self::iniciaApi($app);
	}
	
	public static function addApp($app){
		$css = $app->css();
		$js = $app->js();
		$caminho = $app->caminho;
		$nome = $app->nome;
		
		self::$apps[$nome] = array(
								'js' => $js,
								'css' => $css,
								'caminho' => $caminho
							);
	}
	
}

//print_r(lliure::$apps);

class ll_app{
	private $css;
	private $js;
	public $nome;
	public $caminho;
	
	public function _construct($nome = ''){
		$this->setNome(strtolower($nome));
	}
	
	public function css($cssUrl = null){
		if(!empty($cssUrl)){
			$this->css[] = $cssUrl;
			return $this;
		}else{
			return $this->css;
		}
	}
	
	public function js($jsUrl = null){
		if(!empty($jsUrl)){
			$this->js[] = $jsUrl;
			return $this;
		}else{
			return $this->js;
		}
	}
	
	public function setNome($nome){
		$this->nome = $nome;
		$this->caminho = '';
		$this->css = array();
		$this->js = array();
		return $this;
	}
	
	public function setCaminho($caminho){
		$this->caminho = $caminho;
		return $this;
	}
	
	public function addApp(){
		lliure::addApp($this);
		return $this;
	}
	
}

class api extends lliure{}


require_once 'includes/api.inc.php';
?>
