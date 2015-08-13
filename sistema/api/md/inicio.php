<?php

define('MIDIAS_IMAGENS', 0);

class midias{

	const
		CORTES = array('c', 'o', 'm', 'p', 'r', 'a');
	
	private static 
		$tiposPreDefinidos = array(
			// MIDIAS_IMAGENS
			'png jpg gif'
		);

	private 
		$titulo = 'Selecione os arquivos',
		$dica = '',
		$name = '',
		$tipos = null,
		$corte = null,
		$cortes = array('c', 'o', 'm', 'p', 'r', 'a'),
		$quantidadeStar = 0,
		$quantidadeLength = 1,
		$rais = '',
		$diretorio = '',
		$dados = array(),
		$inseridos = array(),
		$removiodos = array();
	
	
	public function tipos($tipos = null) {
		if($tipos !== NULL)
			$this->tipos = is_numeric($tipos)? self::$tiposPreDefinidos[$tipos]: $tipos;
		
		else
			return is_array($this->tipos)? implode(' ', $this->tipos): $this->tipos;
		
		return $this;
	}

	public function corte($width = null, $height = null){
		if($width !== NULL && $height !== NULL)
			$this->corte = $width. '-'. $height;
		
		else
			return $this->corte;
		
		return $this;
	}

	public function cortes(array $cortes = NULL){
		if($cortes !== NULL)
			$this->cortes = $cortes;
		
		else
			return $this->cortes;
		
		return $this;
	}

	public function quantidade($star = NULL, $length = NULL) {
		if($star !== NULL && $length !== NULL ){
			$this->quantidadeStar = ($star >= 0? $star: 0);
			$this->quantidadeLength = ($length >= 1? $length: 1);
		
		}elseif($star !== NULL){
			$this->quantidadeStar = 0;
			$this->quantidadeLength = ($star >= 0? $star: 0);
		
		}else 
			return $this->quantidadeStar + $this->quantidadeLength;
		
		return $this;
	}
	
	function quantidadeStar() {
		return $this->quantidadeStar;
	}

	function quantidadeLength() {
		return $this->quantidadeLength;
	}

	public function rais($rais = NULL){
		if($rais !== NULL)
			$this->rais = $rais;
		
		else
			return $this->rais;
		
		return $this;
	}

	public function diretorio($diretorio = NULL){
		if($diretorio !== NULL)
			$this->diretorio = $diretorio;
		
		else
			return $this->diretorio;
		
		return $this;
	}

	public function name($name = NULL){
		if($name !== NULL)
			$this->name = $name;
		
		else
			return $this->name;
		
		return $this;
	}

	public function dados(array $arquivos = NULL){
		if($arquivos !== NULL)
			self::inseriArquivos($this->dados, $arquivos);
		
		else
			return array_keys($this->dados);
		
		return $this;
	}

	public function inseridos(array $arquivos = NULL){
		if($arquivos !== NULL)
			self::inseriArquivos($this->inseridos, $arquivos);
		
		else
			return array_keys($this->inseridos);
		
		return $this;
	}
	
	public function removidos(array $arquivos = NULL){
		if($arquivos !== NULL)
			self::inseriArquivos($this->removiodos, $arquivos);
		
		else
			return array_keys($this->removiodos);
		
		return $this;
	}
	
	public function setCortes(array $cortes){
		
		foreach ($cortes as $arq => $corte){
			
			if(isset($this->dados[$arq]))
				$this->dados[$arq] = $corte;
			
			if(isset($this->inseridos[$arq]))
				$this->inseridos[$arq] = $corte;
		}
	}
	
	public function getCorte($arquivo){
		return 
			(isset($this->inseridos[$arquivo])? $this->inseridos[$arquivo]: 
			(isset($this->dados[$arquivo])? $this->dados[$arquivo]: 
		NULL));
	}

	public function corteDados($arquivo){
		return
			(isset($this->inseridos[$arquivo])? $this->inseridos[$arquivo]:
			(isset($this->dados[$arquivo])? $this->dados[$arquivo]:
		NULL));
	}
	
	public function listaDeArquivos(){
		
		$arquivos = array();
		$remo = $this->removidos();
		
		foreach ($this->dados() as $arq)
			if(!in_array($arq, $remo))
				$arquivos[] = $arq;
		
		foreach ($this->inseridos() as $arq)
			$arquivos[] = $arq;
		
		return $arquivos;
	}
	
	private static function inseriArquivos(&$var, $arquivos) {
		$var = array();
		foreach ($arquivos as $arquivo){
			$arq = $arquivo;
			$cor = '';
			if(preg_match('/([0-9]+-)+(([^-]+)-)*['. implode('', self::CORTES). ']\//i', $arquivo)){
				$e = explode('/', $arquivo);
				$arq = array_pop($e);
				$cor = array_pop($e);
				$e[] = $arq;
				$arq = implode('/', $e);
			}
			$var[$arq] = $cor;
		}
	}

	public function dica($dica = NULL){
		if($dica !== NULL)
			$this->dica = $dica;
		
		else
			return $this->dica;
		
		return $this;
	}

	public function titulo($titulo = NULL){
		if($titulo !== NULL)
			$this->titulo = $titulo;
		
		else
			return $this->titulo;
		
		return $this;
	}

	
	public function __toString(){
		return '
			<div class="api-midias" data-name="'. $this->name(). '"  data-action="api/midias/midias.php?m='. $this->implode(). '">
				<input class="div" readonly="readonly" type="test" value="'. implode('; ', $this->dados()). '"/>
				<div class="botoes">
					<button type="button">Selecione os Arquivos</button>
				</div>
			</div>
		';
	}
	
	public function construirSelecionados(){
		$r  = '<div id="midias-dados-antetiores" style="display: none; visibility: hidden;">';
		foreach ($this->dados() as $id => $arquivo){
			$r .= '<input type="hidden" name="dados['. ($id + 1). ']" data-id="'. ($id + 1). '" value="'. ((!empty($this->dados[$arquivo])? $this->dados[$arquivo]. '/': ''). $arquivo). '"/>';
		}
		$r .= '</div>';
		return $r;
	}
    
    final static function preparaParaJson($array){
        if(is_array($array)){
            foreach ($array as $key => $value){
                $array[self::preparaParaJson($key)] = self::preparaParaJson($value);
            }
        }else{
            return rawurlencode($array);
        }
        return $array;
    }
	
	public function implode(){
		return rawurlencode(jf_encode($_SESSION['logado']['token'], serialize($this)));
	}
	
	public function datas(){
		$r  = '';
		$r .= ' data-name="' . $this->name(). '"';
		$r .= ' data-quant-start="'. $this->quantidadeStar(). '"';
		$r .= ' data-quant-length="'. $this->quantidadeLength(). '"';
		$r .= ' data-corte="'. $this->corte(). '"';
		$r .= ' data-tipos="'. $this->tipos(). '"';
		$r .= ' data-cortes="'. implode('-', $this->cortes()). '"';
		$r .= ' data-action="'. $this->implode(). '"';
		return $r;
	}
	
	static public function atualizaBanco(&$_DADOS, $tabela, $idLigacao, $colunaLigacao = 'lig', $colunaArquivo = 'arquivo', $colunaOrden = null){
		
		if(isset($_DADOS['removidos']))
			foreach ($_DADOS['removidos'] as $removido)
				if($e = jf_delete($tabela, array($colunaLigacao => $idLigacao, $colunaArquivo => $removido)))
					echo $erro[] = $e;
		
		if(!empty($erro) && is_array($erro))
			echo '<pre>', print_r($erro, true), '</pre>';
				
		$insert = array();
		if(isset($_DADOS['inseridos'])){
			foreach ($_DADOS['inseridos'] as $pos => $inserir){
				if($colunaOrden !== NULL)
					$insert[] = array($colunaLigacao => $idLigacao, $colunaArquivo => $inserir, $colunaOrden => $pos);
				
				else
					$insert[] = array($colunaLigacao => $idLigacao, $colunaArquivo => $inserir);
			}
		}
		
		if(!empty($insert) && $erro = jf_insert($tabela, $insert))
			echo $erro;
		
	}
	
}