<?php
/**
*
* API navigi - lliure
*
* @Versão 6.0
* @Desenvolvedor Jeison Frasson <jomadee@lliure.com.br>
* @Entre em contato com o desenvolvedor <jomadee@glliure.com.br> http://www.lliure.com.br/
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


/*	***	Documentação da função ***
	
	Para iniciar a classe
	$navegador = new navigi();
	
	Define a tabela da consulta
	$navegador->tabela = PREFIXO.'tabela ';

	Definendo a query para consulta
	$navegador->query = 'select * from '.$navegador->tabela;

	Define como será a exibição. por ser "lista" ou "icone"
	$navegador->exibicao = 'icone';

	Define as cofigurações da navegação
	$navegador->config = (array) $config;

	Para rodar a classe
	$navegador->monta();

	###
	Opção "config", essa é a mais complicada pois é nela que difinimos como vai ser o icone o clique duplo e a divercidade da busca...

	Assim ficaria uma configuração mais simples, no caso apenas vamos direcionar o duplo clique  para uma página (visualizar) que tenha o id "X" (por padrão o jfnav pesquisa o campo "id" e adiciona no final da url)
	$navegador->config = array(
				'link' => '?app=meuapp&pagina=visualizar&id='
				);

	Para alterar o icone padrão adicione 'ico' => 'img/meuico.png' onde o endereço começa a contar apartir da raiz do aplicativo
	exemplo da utilização
	$navegador->config = array(
				'link' => '?app=meuapp&pagina=visualizar&id=',
				'ico' => 'img/meuico.png'
				);	

	Alterando a coluna de exibição (por padrão se chama nome), desta forma vamos definir que a coluna que vamos consultar será "cor" ao invés de "nome"
		$navegador->config = array(
				'link' => '?app=meuapp&pagina=visualizar&id=',
				'coluna' => 'cor'
				);

	Exemplo de montagem do config com tabelas anexadas, isso é usado para quando a coluna principal estiver em outra tabela (um exemplo é quando utilizamos multidiomas), o "as_id" nada mais é que o id da FK para quando for fazer o rename realizar na tabela correta e com o id correto
	$navegador->config = array(
			'link' => '?app=meuapp&pagina=visualizar&id=',
			'ico' => 'img/meuico.png',

			'tabela' => PREFIXO.'meuapp_dados'
			'as_id' => 'campo_id'
			);
	
	Consultando mais de um tipo de registro
	para este fim você tera que usar da mesma forma que a de cima porem dentro de arrays, e usar o parametro 'configSel' para definir o campo que diferencia um do outro, e os indices dos array de configuração será a diferença

	$navegador->configSel = 'tipo';
	$navegador->config['produto'] =  array (
	 			'link' => '?app=meuapp&pagina=visualizar&id=',
				'ico' => 'img/meuico.png'
				);
	
	$navegador->config['categoria'] =  array (
				'link' => '?app=meuapp&p=categoria&id=',
				'ico' => 'img/outroico.png'
				);
	
	Para habilitar a função "apagar" passe como "true" o paramentro 'delete'
	$navegador->delete = true;
	
	Para habilitar a função "renomear" passe como "true" o paramentro 'rename'
	$navigi->rename = true;
	
	
	Exemplo de utilização *************
	
	$navigi = new navigi();
	$navigi->tabela = PREFIXO.'app';
	$navigi->query = 'select * from '.$navigi->tabela.' order by nome asc' ;
	$navigi->delete = true;
	$navigi->rename = true;
	$navigi->config = array(
		'ico' => 'imagens/sys/app.png',
		'link' => '?app=meuapp&ac=editar&id='           
		);								
	$navigi->monta();
	
*/

class navigi{
	var $query;
	var $pasta;
	var $tabela;
	var $objetos;
	var $config;
	var $debug;
	var $exibicao = 'icone';
	var $delete = false;
	var $rename = false;
	var $configSel = false;
	var $paginacao = false;

	function monta(){
		global $_ll;
		
		if(isset($this->config['campo'])){
			$this->configSel = $this->config['campo'];
			unset($this->config['campo']);
		}
		
		if($this->paginacao != false){
			$pAtual = 1;
			if (isset($_GET['nvg_pg']) && !empty($_GET['nvg_pg'])) 
				$pAtual = $_GET['nvg_pg'];
				
			$inicio = $pAtual - 1;
			$inicio = $inicio * $this->paginacao;
				
			$tReg = mysql_query($this->query);
			$tReg = mysql_num_rows($tReg);
				
			$tPaginas = ceil($tReg / $this->paginacao);
				
			$this->query = $this->query.' limit '.$inicio.','.$this->paginacao;
			
			$url = jf_monta_link($_GET, 'nvg_pg');
			$this->paginacao = array('pAtual' => $pAtual,'tPaginas' => $tPaginas, 'tReg' => $tReg, 'url' => $url);
		}
				
		$navigi = array(
						'tabela' => $this->tabela,
						'query' => $this->query,
						'debug' => $this->debug,
						'delete' => ($this->delete ? true : false ),
						'rename' => ($this->rename ? true : false ),
						'configSel' => $this->configSel,
						'paginacao' => $this->paginacao,
						'exibicao' => $this->exibicao
						);
		
		
		
		if(isset($_ll['app']['pasta']))
			$this->pasta = $_ll['app']['pasta'];

		if($this->configSel === false)
			$this->config = array($this->config);
		
					
		foreach($this->config as $chave => $valor){
			$this->config[$chave]['coluna'] = (isset($this->config[$chave]['coluna']) ? $this->config[$chave]['coluna'] : 'nome');
			$this->config[$chave]['as_id'] = (isset($this->config[$chave]['as_id']) ? $this->config[$chave]['as_id'] : 'id');
			$this->config[$chave]['tabela'] = (isset($this->config[$chave]['tabela']) ? $this->config[$chave]['tabela'] : $this->tabela);
		}
		
		$navigi['config'] = $this->config;
	
		$navigi = serialize($navigi);
		
		$encriptado = jf_encode($_ll['user']['token'], $navigi);
		
		if($this->debug == true)
			echo '<pre>'.print_r($this->config ,true).'</pre>';
		
		echo '<div id="navigi" token="'.$encriptado.'"></div>';
	}
}

?>
