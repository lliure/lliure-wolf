<?php
/**
*
* tags - lliure
*
* @Versão 1.0
* @Desenvolvedor Rodrigo Dechen <rodrigo@lliure.com.br>
* @Entre em contato com o desenvolvedor <rodrigo@lliure.com.br>
* @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

class tags{
    
    const 
        TAGS = 1,
        CONTATOS = 2;

    static private 
        $id = 0;

    private 
        $contexto = null,
        $action = array(),
        $novos = true,
        $sugestoes = true;
    
    public function __construct() {
        $this->contexto = 'tags-'. self::$id++;
    }
    
    public function contexto($class = null){
        if($class !== NULL){
            if(is_string($class))
                $this->contexto = $class;
        }else{
            return $this->contexto;
        }
    }
    
    public function action($action = null){
        if($action !== NULL){
            if(is_string($action))
                $this->action = $action;
        }else{
            return $this->action;
        }
    }
    
    /**
     * libera e bloquei a incerção de novas tags.</br>
     * Se <b>true</b> libera se <b>false</b> bloqueia
     * 
     * @param bool $liberar o valor que voce quer atribuir
     * @return bool o valor atual para o status de <var>novos</var>
     */
    public function novos($liberar = NULL){
        if($liberar === NULL)
            return $this->novos;
        
        elseif(is_bool($liberar))
            $this->novos = $liberar;
    }
    
    /**
     * libera e bloquei o aparecimento de sugestoes.</br>
     * se <b>true</b> libera se <b>false</b> bloqueia
     * 
     * @param bool $liberar o valor que voce quer atribuir
     * @return bool o valor atual para o status da <var>sugestao</var>
     */
    public function sugestoes($liberar = NULL){
        if($liberar === NULL)
            return $this->sugestoes;
        
        elseif(is_bool($liberar))
            $this->sugestoes = $liberar;
    }

    /**
     * Configura um simples gerador de tags consultando e gravando na mesma tabela.
     * 
     * @param string $tabela nome da tabela onde esta as tags
     * @param string|int $idDeLigacao valor do id de ligacao, isto é, a qual is as tags pertencem 
     * @param string $campoDeLigacao a coluna mo bamco que é a coluna de ligacao. padrão lig
     * @param string $campoDeTag a coluna que guarda as tags, padrão tag
     */
    public function config($tabela, $idDeLigacao, $campoDeLigacao = 'lig', $campoDeTag = 'tag'){
        $this->action['tabela'] = $tabela; 
        $this->action['idDeLigacao'] = $idDeLigacao; 
        $this->action['campoDeLigacao'] = $campoDeLigacao; 
        $this->action['campoDeTag'] = $campoDeTag;
    }
    
    public function tabelaDeBusca($buscaTabelaTabela, $buscaTabelaCampoTag = 'tag', $buscaTabelaCampoId = 'id'){
        $this->action['buscaTable'] = $buscaTabelaTabela; 
        $this->action['buscaTableTag'] = $buscaTabelaCampoTag; 
        $this->action['buscaTableId'] = $buscaTabelaCampoId;
    }

    public function contruir(){
        return $this->__toString();
    }

    public function __toString(){
        return $this->__invoke();
    }
    
    public function __invoke($stilo = self::TAGS){
        if($this->action !== null && $this->contexto !== NULL && ((is_string($this->contexto)) || (is_array($this->contexto) && isset($this->action['tabela'])))){
            $action = (is_array($this->action)? 'api/tags/tag.php?d='. rawurlencode(jf_encode($_SESSION['logado']['token'], serialize($this->action))): $this->action);
            if($stilo = self::TAGS){
                return '
                    <div class="'. $this->contexto. ' api-tag" action="'. $action. '" data-novos="'. ($this->novos? 'true': 'false').'" data-sugestoes="'. ($this->sugestoes? 'true': 'false').'">
                        <div class="topicosForm">
                            <div class="inputMargem">
                                <div class="inputBox">
                                    <input class="pesquisa" type="text" autocomplete="off"/>
                                    <div class="sugestao"></div>
                                </div>
                            </div>
                        </div>
                        <div class="relacionados"></div>
                    </div>
                    <script>
                        $(function(){
                            tag.loadTopicos($(".'. $this->contexto. '"));
                        });
                    </script>
                ';
            }else{
                return '  
                    <div class="participantes">
                        <span class="tamanhoInput"></span>
                            <div class="inputLista">
                                <div class="lista" style="display: none;"></div>
                                <input id="contato" type="text" name="contato" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                ';
            }
        }else{
            return '
                <div class="topicos erro">
                    Api configurada incorretamente.<br/>'.
                    (ll_tsecuryt()? '<pre>'. (print_r($this, TRUE)). '</pre>': '').
                '</div>
            ';
        }
    }

}

interface tag_interface{
    
    /**
     * metodo chamado na contrucao e atualisacao da lista de tags
     */
    public function get();
    
    /**
     * metodo usado para traser as opcoes te tag segundo oq o cara digitou
     */
    public function query();
    
    /**
     * metodo chamodo quando é deletado alguma tag
     */
    public function del();
    
    /**
     * metodo camado quando se cria uma tag
     */
    public function set();
    
}


class tag_server{
    
    final public static function rum(tag_interface $obj){
        
        switch ($_GET['tag']){

            case 'get':
                echo self::lista($obj->get());
            break;

            case 'query':
                echo self::opcoes($obj->query());
            break;

            case 'del':
                echo json_encode(self::preparaParaJson($obj->del()));
            break;

            case 'set':
                echo json_encode(self::preparaParaJson($obj->set()));
            break;

        }
    }
    
    final private static function preparaParaJson($array){
        if(is_array($array)){
            foreach ($array as $key => $value){
                $array[self::preparaParaJson($key)] = self::preparaParaJson($value);
            }
        }else{
            return rawurlencode($array);
        }
        return $array;
    }
    
    final private static function opcoes(array $array){
        $r = '';
        foreach($array as $key => $value){
            $r .= '
                <a class="topico" href="" data-id="'. $value['id']. '" data-tag="'. $value['tag']. '">'. $value['tag']. '</a>'
            ;
        }
        return $r;
    }
    
    final private static function lista($array){
        $r = '';
        foreach($array as $key => $value){
            $r .= '
				<span class="tag-adedida" data-id="'.$value['id'].'" data-tag="'.$value['tag'].'">
					<a class="tag-bot-del"><img src="imagens/icones/delete.png" alt="excluir"></a>
					<div class="tag-content">'.$value['tag'].'</div>
				</span>		
			';
        }
        return $r;
    }
    
    
}


