<?php
/**
*
* gerenciamento de APIs - lliure 8.x
*
* @Versão 7.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Colaboração Carlos Alberto Carucci
* @Colaboração Rodrigo Dechen <rodrigo@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
class lliure {
	public static $apis = array();
		
	/**** metodos para autenticação de paginas */
	public static function autentica($grupo = null){
		if(func_num_args() > 1){
			$grupo = func_get_args();
		} else {
			if(!is_array($grupo) && strpos($grupo, ','))
				$grupo = explode(',', $grupo);
		}
		
		$grupo_user = $_SESSION['logado']['grupo'];
		switch($grupo_user){
			case 'dev':
				return true;
			break;
			
			default:
				if((is_array($grupo) && in_array($grupo_user, $grupo)) || $grupo == $grupo_user)
					return true;

			break;
		}
		
		self::denied('acesso');
	}

		
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
		
		$load = self::$apis[$api];
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
			self::$apis[$api]['carregado'] = $loaded;
			
		}

		return $loaded;
	}
	
	public static function inicia($api){
		return self::iniciaApi($api);
	}
	
	public static function addApi($api){
		$css = $api->css();
		$js = $api->js();
		$caminho = $api->caminho;
		$nome = $api->nome;
		
		self::$apis[$nome] = array(
					'js' => $js,
					'css' => $css,
					'caminho' => $caminho
				);
	}
	
	
		
	public function denied($mod){
		switch ($mod){
		default:
			
			break;			
		}
			

		echo 'Você não tem permissão para acessar está página! <br/>';
		echo '<a href="index.php">Retornar a área de trabalho</a>';
		
		die();
	}
	
}


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
		$this->nome = strtolower($nome);
		$this->caminho = '';
		$this->css = array();
		$this->js = array();
		return $this;
	}
	
	public function setCaminho($caminho){
		$this->caminho = $caminho;
		return $this;
	}
	
	public function addApi(){
		lliure::addApi($this);
		return $this;
	}
}

?>