<?php
/**
*
* API navigi - lliure
*
* @Versão 6.4
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

#	Para alterar o icone padrão adicione 'ico' => 'img/meuico.png' onde o endereço começa a contar apartir da raiz do aplicativo
	exemplo da utilização
	$navegador->config = array(
				'link' => '?app=meuapp&pagina=visualizar&id=',
				'ico' => 'img/meuico.png'
				);	

#	Alterando a coluna de exibição (por padrão se chama nome), desta forma vamos definir que a coluna que vamos consultar será "cor" ao invés de "nome"
		$navegador->config = array(
				'link' => '?app=meuapp&pagina=visualizar&id=',
				'coluna' => 'cor'
				);

#	Exemplo de montagem do config com tabelas anexadas, isso é usado para quando a coluna principal estiver em outra tabela (um exemplo é quando utilizamos multidiomas), o "as_id" nada mais é que o id da FK para quando for fazer o rename realizar na tabela correta e com o id correto
	$navegador->config = array(
			'link' => '?app=meuapp&pagina=visualizar&id=',
			'ico' => 'img/meuico.png',

			'tabela' => PREFIXO.'meuapp_dados'
			'as_id' => 'campo_id'
			);
	
#	Consultando mais de um tipo de registro
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
	
#	Para habilitar a função "apagar" passe como "true" o paramentro 'delete'
	$navegador->delete = true;
	
#	Para habilitar a função "renomear" passe como "true" o paramentro 'rename'
	$navigi->rename = true;
	
#	Trabalhando com botões auxiliares
	use 'ico' para definir o icone do botao
		'link' para definir o link ao clicar
		'modal' em caso de abertura de modal, sendo "Largura X Altura" ex: 250x100, para que fique automatico use a palavra "auto"
	
	ex:
	'botao' => array(
				array('ico' => $_ll['app']['pasta'].'img/box.png', 
				'link' => $_ll['app']['sen_html'].'&apm=produtos&sapm=produto&ac=estoque&id=#ID', 
				'modal' => '300xauto')
			)

#	Alterando os nomes das etiquetas
	$navigi->etiqueta = array(
								'id' => 'Pedido',								
								'coluna' => 'Data'
							);
	//1 lembrando que essas são as duas padrões utilizadas pelo sistema, caso adicione mais, as mesmo serão carregadas no modo lista com seus respectivos conteudos
	
	//2 utilize um array com o arg 0 com o nome e o arg 1 com a medida da coluna caso necessário
		ex: 'usuario' => array('nome','50px');

#	Exemplo de utilização simples *************
	
	$navigi = new navigi();
	$navigi->tabela = PREFIXO.'app';
	$navigi->query = 'select * from '.$navigi->tabela.' order by nome asc' ;
	$navigi->delete = true;
	$navigi->rename = true;
	$navigi->config = array(
		'ico' => $_ll['app']['pasta'].'imagens/sys/app.png',
		'link' => '?app=meuapp&ac=editar&id='           
		);								
	$navigi->monta();
	
*/

class navigi{
	/** query em mysql */
	var $query;
	
	/*apagar var $pasta; */
	
	/** tabela da consulta */
	var $tabela;
	
	/*apagar var $objetos; */
	
	/** configurações: ico; link; tabela; as_id, botao: array()*/
	var $config;
	
	/** true,false */
	var $debug = false;
	
	/** icone,lista */
	var $exibicao = 'icone';
	
	/** true,false */
	var $delete = false;	
	
	/** true,false */
	var $rename = false;	
	
	/** string // nome do campo que faz a diferenciação dos dados listados */
	var $configSel = false; 	
	
	/** false,1,n */	
	var $paginacao = false;
	
	/** array(); ex: array('id' => 'codigo' [, 'coluna' => 'valor']) */	
	var $etiqueta = null;
	var $cell = null;

	function monta(){
		global $_ll;
		
		/** Retro compatibilidade para verções antigas*/
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
		
		/** caso tenha botoes na configuracao muda a exibicao para lista*/
		if(isset($this->config['botao']))
			$this->exibicao = 'lista';
		
		
		$navigi = array(
						'tabela' => $this->tabela,
						'query' => $this->query,
						'debug' => $this->debug,
						'delete' => $this->delete,
						'rename' => $this->rename,
						'configSel' => $this->configSel,
						'paginacao' => $this->paginacao,
						'exibicao' => $this->exibicao
						);
		
		
		/*apagar
		if(isset($_ll['app']['pasta']))
			$this->pasta = $_ll['app']['pasta'];
		*/
		
		/** AJUSTA O PADRAO PARA CONFIGURACAO DE BOTOES */
		if(isset($this->config['botao']) && !isset($this->config['botao'][0]) && isset($this->config['botao']['link']))
			$this->config['botao'] = array(0 => $this->config['botao']);
		
		
		if($this->configSel === false)
			$this->config = array($this->config);
		
		
		foreach($this->config as $chave => $valor){
			$this->config[$chave]['coluna'] = (isset($this->config[$chave]['coluna']) ? $this->config[$chave]['coluna'] : 'nome');
			$this->config[$chave]['as_id'] = (isset($this->config[$chave]['as_id']) ? $this->config[$chave]['as_id'] : 'id');
			$this->config[$chave]['id'] = (isset($this->config[$chave]['id']) ? $this->config[$chave]['id'] : 'id');
			$this->config[$chave]['tabela'] = (isset($this->config[$chave]['tabela']) ? $this->config[$chave]['tabela'] : $this->tabela);
		}
				
		$navigi['config'] = $this->config;
		
		
		/** CONFIGURA A ETIQUETA*/
		if(!isset($this->etiqueta['id']))
			$this->etiqueta['id'] = 'Cod.';
			
		if(!isset($this->etiqueta['coluna']))
			$this->etiqueta['coluna'] = '';		
		
		foreach($this->etiqueta as $chave => $valor){
			if(!is_array($valor))
				$this->etiqueta[$chave] = $valor = array($valor, 'auto');		
				
			if($chave != 'id' && $chave != 'coluna' )
				$this->cell[$chave] = $valor;
		}
		
		$navigi['etiqueta'] = $this->etiqueta;
		$navigi['cell'] = $this->cell;
		/**/
		
		
		if($this->debug == true)
			echo '<pre>'.print_r($navigi ,true).'</pre>';
		
		
		$navigi = serialize($navigi);
		
		$encriptado = jf_encode($_ll['user']['token'], $navigi);		
	
		
		echo '<div id="navigi" token="'.$encriptado.'"></div>';
	}
}

?>
