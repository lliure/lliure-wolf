<?php
/**
 * Description of db
 */

class DB {

    const
        PDO = 'PDO',
        MYSQL = 'MYSQL';

    protected static

        /** garda a conecção com o banco de dados; */
        $DB = NULL, $type = NULL;

    protected

        /** Nome da tebal ondo ocorerao as consultas. */
        $tabela,

        /** Pos Fixo temporario do nome da tabla */
        $tempPoxFix = NULL,

        /** Nome temporario para tebala */
        $tempTab = NULL,

        /** Garda uma lista com as ultimas querys executadas. */
        $queryList = array();

    /**
     * Voce deve sobrescrever este metodo pasando o nome da tabela que sua classe ira gerenciar
     * @param string $tabela o nome da tabela
     */
    public function __construct($tabela) {
        $this->tabela = self::antiInjection($tabela);
    }

    final public function __toString(){
        if ($this->tempTab !== NULL)
            return $this->tempTab;
        else
            return $this->tabela . ($this->tempPoxFix !== NULL? $this->tempPoxFix: '');
    }

    final protected function setTempPosFix($tempPoxFix){
        $this->tempPoxFix = $tempPoxFix;
        return $this;
    }

    final protected function setTempTab($tempTab){
        $this->tempTab = $tempTab;
        return $this;
    }

    final protected function clierTemps(){
        $this->tempPoxFix = NULL;
        $this->tempTab = NULL;
    }

    /**
     * conector com o bamco de dados via PDO ou mysql.
     * @return null|PDO|resource
     * @throws Exception Caso algum erro acontesa.
     */
    protected static function conectar($basetype = null, $hostName = null, $userName = null, $password = null, $tableName = null){

        $bdconf['basetype'] = $basetype;
        $bdconf['hostName'] = $hostName;
        $bdconf['userName'] = $userName;
        $bdconf['password'] = $password;
        $bdconf['tableName'] = $tableName;

        if (self::$DB !== NULL)
            return self::$DB;

        if (!(!isset($GLOBALS['hostname_conexao'])
        || (!isset($GLOBALS['username_conexao']))
        || (!isset($GLOBALS['password_conexao']))
        || (!isset($GLOBALS['banco_conexao'])))){
            $bdconf['basetype'] = 'mysql';
            $bdconf['hostName'] = $GLOBALS['hostname_conexao'];
            $bdconf['userName'] = $GLOBALS['username_conexao'];
            $bdconf['password'] = $GLOBALS['password_conexao'];
            $bdconf['tableName'] = $GLOBALS['banco_conexao'];
        }

        if(class_exists('PDO')){
            try {
                self::$DB = new PDO($bdconf['basetype']. ':host='. $bdconf['hostName']. ';dbname='. $bdconf['tableName'], $bdconf['userName'], $bdconf['password']);
                self::$type = self::PDO; return self::$DB;

            } catch (PDOException $e){
                throw new Exception('Falha de conexão: ' . $e->getMessage(), 1, $e);}

        }else{
            if((self::$DB = @mysql_connect($bdconf['hostName'], $bdconf['userName'], $bdconf['password'])) === FALSE)
                throw new Exception('<strong>Não foi possivel realizar a conexão com banco de dados</strong><br>verifique as configurações do arquivo bdconf.php em /etc', 1);

            if(mysql_select_db($bdconf['tableName'], self::$DB) === false)
                throw new Exception('<strong>Não foi possivel localizar a tabela no banco de dados</strong><br>verifique as configurações do arquivo bdconf.php em /etc', 2);;
            
            self::$type = self::MYSQL;
            return self::$DB;
        }

    }

    /**
     * adiciona uma query a lista de query esecuradas.
     * @param string $query a query que foi execurada
     * @return array a query q acaba de ser inserida.
     */
    protected function &setQueryList($query){
        $k = count($this->queryList);
        $this->queryList[$k] = $query;
        return $this->queryList[$k];
    }

    /**
     * retorna uma ou mais query executadas.
     * @param mixed $quant
     *      $quant = null<br/>
     *      retorna a ultima query inserida na lista.
     *
     *      $quant = TRUE<br/>
     *      Retorna toda a lista de querys feiras.
     *
     *      $quant = (<i>numero</i>)<br/>
     *      Retorna a quantidade passada em <b>$quant</b> das ultimas querys feitas.
     *
     * @return mixed
     */
    public function getQueryList($quant = null){
        $retorno = null;
        if (!empty($this->queryList)){
            if ($quant === null)
                $retorno = $this->queryList[count($this->queryList) - 1];
            elseif ($quant === true)
                $retorno = $this->queryList;
            elseif (is_numeric($quant))
                for($i = (((count($this->queryList) - $quant) <= 0)? 0: count($this->queryList) - $quant); $i < count($this->queryList); $i++)
                    $retorno[] = $this->queryList[$i];
        }
        return $retorno;
    }

    /**
     * Printa o Log compreto de querys esecutados. O mesmo que <code>getQueryList(TRUE)</code>
     */
    public function queryLog(){
        echo '<pre>', print_r($this->getQueryList(TRUE), TRUE), '</pre>';
    }

    /**
     * Trata o conteudo inserido com diretizes de anti injection, se o conteudo <br/>
     * inserido for uma string ele a trada e devolve uma string, caso seja um <br/>
     * array ele trata seus vaores recurcivamnete e retorna o array tratado.
     * @param mixed $sql Trata se for uma string ou array outros valores como <br/>
     * <code>boolean</code> ou <code>null</code> s?o preservados.
     * @return mixed retorna o conteudo inserido tratodo.
     */
    static function antiInjection($sql){
        if(is_array($sql)){
            foreach($sql as $chave => $valor)
                $sql[self::antiInjection($chave)] = self::antiInjection($valor);
        }elseif(is_string($sql)){
            $sql = @get_magic_quotes_gpc() ? stripslashes($sql) : $sql;
            //$sql = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($sql) : mysql_escape_string($sql);
            $sql = trim($sql); # Remove espaços vazios.
            $sql = addslashes($sql); # Adiciona barras invertidas à uma string.
        }
        return $sql;
    }

    /**Retorna uma string com todas as <b><i>ShortTegs</i></b> subsistidas por seu
     * correspondente valor contido no array de dados, isto é, localiza-se um
     * índice no array de dados com a teg, e ela substituida por esse valor.
     *
     * As <b><i>ShortTegs</i></b> podem conter 1 ou 2 parametros, se pasar 1 parametro
     * a função localiza a Key no array de dados e subistitue ela na string.
     *
     * EXP.:
     * $dados = array('id' => 1, 'texto' => 'Olá mundo!');
     * echo '//SAIRA '. echo db::prepare(' id = [id] AND texto = "[texto]" ', $dados);
     *
     * -------------------------------------------------------------------------------
     * //SAIDA id = 1 AND texto "Olá mundo!"
     * -------------------------------------------------------------------------------
     *
     * Cada Key teve seu respequitivo valor inserido onde
     *
     *
     * Se pasar 2 parametros, o primeiro sera o comparador e o segundo sera a key,
     * logo a funçao ira analizar o tipo do dado que a key aponta e adequara o
     * comparador ao tipo.
     *
     * EXP.:
     * $dados = array('id' => 1, 'nome' => 'Nome Fulano', 'texto' => null, 'tags' => array('foo', 'bar'), 'idade' => 20);
     * echo '//SAIRA '. db::prepare(' id [= id] AND texto [= texto] OR tags [!= tags] ', $dados). '<br>';
     * echo '//ARRAY '. print_r($dados, true);
     *
     * -------------------------------------------------------------------------------
     * //SAIRA id = "1" AND texto IS NULL OR tags NOT IN("foo", "bar")
     * //ARRAY Array ( [nome] => Nome Fulano [idade] => 20 )
     * -------------------------------------------------------------------------------
     *
     * No exemplo acima [= id] quer a key id e como ela é um numero a função colocou
     * entre '"' (aspas). [= texto] quer a key texto mas como o texto é igual a null e o
     * comparador usado foi o "=" (igual) a função comverteu para IS. [!= tags] quer a
     * key tags mas como as tags são um array a função tranforma ela em um IN e como o
     * comparador quer uma "!=" (diferença) ele colocao o NOT antes da fumção.
     *
     * Comparador obitido crusando o tipo do dado e o comparador pasado.
     *
     *              |                                     TIPOS                                    |
     *              |      array       |    null     |   boolean  |       texto        |   numero  |
     *              |   array(1, 2);   |    NULL     |    TRUE    |      "texto"       |    10     |
     *  COMPARADOR  |------------------|-------------|------------|--------------------|-----------|
     *      =       |   IN("1", "2")   |   IS NULL   |   = TRUE   |     = "texto"      |  = "10"   |
     *      ==      |   IN("1", "2")   |   IS NULL   |   = TRUE   |     = "texto"      |  = "10"   |
     *      IS      |   IN("1", "2")   |   IS NULL   |   = TRUE   |     = "texto"      |  = "10"   |
     *      IN      |   IN("1", "2")   |   IS NULL   |   = TRUE   |     = "texto"      |  = "10"   |
     *     LIKE     |   IN("1", "2")   |   IS NULL   |   = TRUE   |   LIKE "%texto%"   |  = "10"   |
     *      >=      |   IN("1", "2")   |   IS NULL   |   = TRUE   |     >= "texto"     |  >= "10"  |
     *      <=      |   IN("1", "2")   |   IS NULL   |   = TRUE   |     <= "texto"     |  <= "10"  |
     *      !=      | NOT IN("1", "2") | NOT IS NULL |  != TRUE   |     != "texto"     |  != "10"  |
     *      >       | NOT IN("1", "2") | NOT IS NULL |  != TRUE   |     > "texto"      |  > "10"   |
     *      <       | NOT IN("1", "2") | NOT IS NULL |  != TRUE   |     < "texto"      |  < "10"   |
     *     NOT      | NOT IN("1", "2") | NOT IS NULL |  != TRUE   |     != "texto"     |  != "10"  |
     *    NOT_IS    | NOT IN("1", "2") | NOT IS NULL |  != TRUE   |     != "texto"     |  != "10"  |
     *    NOT_IN    | NOT IN("1", "2") | NOT IS NULL |  != TRUE   |     != "texto"     |  != "10"  |
     *    NOT_IN    | NOT IN("1", "2") | NOT IS NULL |  != TRUE   |     != "texto"     |  != "10"  |
     *   NOT_LIKE   | NOT IN("1", "2") | NOT IS NULL |  != TRUE   | NOT LIKE "%texto%" |  != "10"  |
     *
     *
     * O array <b>$dados</b> é passado por referencia, e para cada <b><i>ShortTegs</i></b>
     * encontada o indice e removido do array, isto é, este metodo retira do seu
     * array os indices que coresponder?com as ShortTeg, A não ser que o parametro
     * <i>$naoDeletar</i> estjaa <b>TRUE</b>, neste caso, ele nao remove os indices.
     *
     * o comparador LIKE e NOT_LIKE tem variação que tirão o "%" do começo e final da string.
     * como: LIKE_[LEFR|RIGHT|CLEAR].
     * - LIKE_LEFR gera: LIKE "%texto";
     * - LIKE_RIGHT gera: LIKE "texto%";
     * - LIKE_CLEAR gera: LIKE "texto";
     *
     * @param String $stringShortTag A string contendo as ShortTegs exp.: "id='[id]'"
     * @param array $dados o array com os dados a serem subistituidas.
     * @param boolean $naoDeletar caso ele seta <b>TRUE</b> não são deletados os <br/>
     * indices.
     *
     * @return String A sting com as ShortTegs subistituidas.
     *
     * @version 2
     *
     * ##[12/07/2016]
     * - reformulado o modo de procurar short tegs quando a Class ShortTag não existir.
     * - adicionado o comparador LIKE e suas variações
     */
    final protected static function prepare($stringShortTag, array &$dados, $naoDeletar = FALSE){

        $retorno = $stringShortTag; $e = array(); $sts = array();

        if(!class_exists('ShortTag')){
            $matches = null;
            $returnValue = preg_match_all('/(?:\\[(?:(?:(?:\\\'(?:[^\\\'\\\\]|\\\\.)*\\\'|\\"(?:[^"\\\\]|\\\\.)*\\"|(?:[^\\ \\]]*))(?:[\\ ])?)+)\\])/im', $stringShortTag, $matches, PREG_OFFSET_CAPTURE);

            if ($returnValue > 0 && isset($matches[0]))
                foreach ($matches[0] as $k => $m)
                    $sts[$k] = array(
                        'shortTag' => explode(' ', trim($m[0], '[]')),
                        'start' => $m[1],  'length' => strlen($m[0]));

        }else
            $sts = ShortTag::Search($stringShortTag, null);


        foreach (array_reverse($sts) as $k => $st){
            if (count($st['shortTag']) == 1)
                if (isset($dados[$st['shortTag'][0]]))
                    $retorno = substr_replace($retorno, $dados[($e[] = $st['shortTag'][0])], $st['start'], $st['length']);

            if (count($st['shortTag']) == 2 || count($st['shortTag']) == 3){
                $comp = strtoupper($st['shortTag'][0]);
                if (!in_array($comp, array(
                    "=", "==", "IS", "IN",
                    ">=", "<=", "!=", ">", "<",
                    "NOT", "NOT_IS", "NOT_IN",
                    'LIKE', 'LIKE_LEFT', 'LIKE_RIGHT', 'LIKE_CLEAR',
                    'NOT_LIKE', 'NOT_LIKE_LEFT', 'NOT_LIKE_RIGHT', 'NOT_LIKE_CLEAR'
                ))) continue;

                if (array_key_exists($st['shortTag'][1], $dados))
                    $value = $dados[($e[] = $st['shortTag'][1])];
                elseif (array_key_exists('_content', $st['shortTag']))
                    $value = eval('return ' . $st['shortTag']['_content'] . ';');
                else continue;

                $value = ((is_object($value))? (array) $value: $value);

                $comp = (in_array($comp, array('==', 'IN', 'IS'))? '=' : $comp);
                $comp = (in_array($comp, array('NOT', 'NOT_IN', 'NOT_IS'))? '!=' : $comp);
                $nega = !(in_array($comp, array('=', '<=', '>=', 'LIKE', 'LIKE_LEFT', 'LIKE_RIGHT', 'LIKE_CLEAR')));

                $like =
                    (in_array($comp, array('LIKE_CLEAR', 'NOT_LIKE_CLEAR'))? null:
                        (in_array($comp, array('LIKE', 'NOT_LIKE'))? 0:
                            (in_array($comp, array('LIKE_LEFT', 'NOT_LIKE_LEFT'))? -1:
                                (in_array($comp, array('LIKE_RIGHT', 'NOT_LIKE_RIGHT'))? 1: false))));

                if (is_array($value)){
                    $b = array();
                    foreach ($value as $v) $b[] = (is_bool($v) ? ($v ? 'TRUE' : 'FALSE') : (is_null($v) ? 'NULL' : ('"' . ((string) $v) . '"')));
                    $replace = ($nega ? 'NOT ' : '') . 'IN(' . implode(', ', $b) . ')';

                }elseif (is_null($value)) $replace = ($nega ? 'NOT ' : '') . 'IS NULL';
                elseif  (is_bool($value)) $replace = ($nega ? '!=' : '=') . ' ' . ($value ? 'TRUE' : 'FALSE');
                elseif  ($like !== false) $replace = ($nega ? 'NOT ' : '') . 'LIKE "' . ($like !== null && $like <= 0? '%': '') . ((string) $value) . ($like !== null && $like >= 0? '%': ''). '"' ;
                else    ($replace = ($comp . ' "' . ((string) $value) . '"'));

                $retorno = substr_replace($retorno, $replace, $st['start'], $st['length']);
            }
        }

        if (!$naoDeletar && !empty($e)) foreach ($e as $d) unset($dados[$d]);

        return $retorno;
    }

    final protected static function shortTagReplace($stringShortTag, array &$dados, $naoDeletar = FALSE){
        return self::prepare($stringShortTag, $dados, $naoDeletar);}

    /**
     * Retorna uma linha de um Resultado de pesquisa.
     * @param array $result
     * @return array linha do resultado.
     */
    final static function fetch(array &$result){
        $retorno = current($result);
        if ($retorno !== FALSE)
            next($result);
        return $retorno;
    }

    /**
     * retorna o primero elemento do array independente da chave dele.
     * @param array $array
     * @return mixed
     */
    public static function first(array $array){
        foreach ($array as $first) return $first; return array();}

    /**
     * Retorna a quantidade de linhas que a consulta.
     * @param array $result resultado da consulta.
     * @return int
     */
    final static function numRows(array $result){
        return count($result);
    }

    /**
     * O ID gerado para uma coluna AUTO_INCREMENT pela consulta anterior em caso<br/>
     * de sucesso, 0 se a consulta anterior n?o gerar um valor AUTO_INCREMENT,<br/>
     * ou FALSE se n?o houver conexão MySQL foi criado.
     * @return int
     */
    final static function insert_id(){
        if (self::$type == self::MYSQL){
            return mysql_insert_id(self::$DB);
        }elseif(self::$type == self::PDO){
            return self::$DB->lastInsertId();
        }
    }

    /**
     * Apelido da função <b><i>insert_id</i></b>.
     * @return type
     */
    final static function lastInsertId(){
        return self::insert_id();
    }

    /**
     * Executa consulta ao banco de dados que não geram resultado.
     * @param string $query A consulta SQL para executar (normalmente um INSERT, UPDATE, ou DELETE).
     * @return int Retorna a quantitade de leinhas afetadas.
     * @throws Exception caso ocorra algum erro com a sql.
     */
    protected function exec($query, $persistir = FALSE){
        if(self::$DB === NULL) $this->conectar();
        if(!$persistir)$this->clierTemps();
        $query =& $this->setQueryList($query);

        try {
            if (self::$type == self::MYSQL){
                if (($r = mysql_query($query, self::$DB)) !== false)
                    return mysql_affected_rows(self::$DB);
                else
                    throw new Exception();

            }elseif (self::$type == self::PDO){
                if (($r = @self::$DB->exec($query)) !== false)
                    return $r;
                else
                    throw new Exception();}

        }catch (Exception $e){
            $e = $this->error();
            $query = array($query, $e[1], $e[2]);
            throw new Exception((string)$e[2], (float)$e[1]);
        }
    }

    /**
     * Retorna o ultimo erro ocorido;
     * @return array
     */
    final protected function error(){
        if (self::$type == self::MYSQL)
            return array(0, mysql_errno(), mysql_error());

        else
            return self::$DB->errorInfo();
    }

    /**
     * Execulta uma query que tenha um resulta como SELECT.
     *
     * @param string $query a query a ser execultada.
     * @param bool $persistir Se $persistir for TRUE não limpa as modificações
     * temporaria do nome da tabela.
     * @return array um result em formato de array.
     *
     * @throws Exception
     */
    final protected function select($query, $persistir = FALSE){
        if(self::$DB === NULL) $this->conectar();
        if(!$persistir)$this->clierTemps();
        $query =& $this->setQueryList($query);
        try {
            if (self::$type == self::MYSQL) {
                $result = mysql_query($this->getQueryList(), self::$DB);
                if($result === FALSE) {
                    $e = $this->error(); throw new Exception((string) $e[2], (float) $e[1]);}
                $return = array();
                while (($return[] = mysql_fetch_assoc($result)) or array_pop($return));
                return $return;
            } elseif (self::$type == self::PDO)
                return ($r = self::$DB->query($this->getQueryList())) ? $r->fetchAll(PDO::FETCH_ASSOC) : array();
        }catch (Exception $e){
            $query = array($query, $e->getCode(), $e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Cria um INSERT com os dados pasados no array.
     *
     * Este array pode ser passado de duas formas.
     *
     * Forma 1, array simples.<br/>
     * Exp.:<br/>
     * <pre>array(
     * &nbsp;&nbsp;&nbsp;&nbsp;[nome] => Arnaldo,
     * &nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Silva
     * )</pre>
     *
     *
     * Forma 2, array com 1 subnivel.<br/>
     * Exp.:<br/>
     * <pre>array(
     * &nbsp;&nbsp;&nbsp;&nbsp;[0] => array(
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[nome] => Arnaldo,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Silva
     * &nbsp;&nbsp;&nbsp;&nbsp;),
     * &nbsp;&nbsp;&nbsp;&nbsp;[1] => array(
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[nome] => Amaral,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Costa
     * &nbsp;&nbsp;&nbsp;&nbsp;)
     * )</pre>
     *
     * @param array $dados
     * @return int
     */
    final protected function insert(array $dados){
        $dados = self::antiInjection($dados);
        return $this->exec('INSERT '.$this.' '.self::createInsertReplace($dados));
    }

    /**
     * REPLACE funciona exatamente como INSERT, exceto que se uma velha linha
     * na tabela tem o mesmo valor que uma nova linha para uma chave primária
     * ou um índice único, a velha linha é excluída antes da nova linha ser
     * inserida¹
     *
     * Cria um REPLACE com os dados pasados no array.
     * Este array pode ser passado de duas formas.
     *
     * Forma 1, array simples.<br/>
     * Exp.:<br/>
     * <pre>array(
     * &nbsp;&nbsp;&nbsp;&nbsp;[nome] => Arnaldo,
     * &nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Silva
     * )</pre>
     *
     * Forma 2, array com 1 subnivel.<br/>
     * Exp.:<br/>
     * <pre>array(
     * &nbsp;&nbsp;&nbsp;&nbsp;[0] => array(
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[nome] => Arnaldo,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Silva
     * &nbsp;&nbsp;&nbsp;&nbsp;),
     * &nbsp;&nbsp;&nbsp;&nbsp;[1] => array(
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[nome] => Amaral,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Costa
     * &nbsp;&nbsp;&nbsp;&nbsp;)
     * )</pre>
     *
     * @reference 1 https://dev.mysql.com/doc/refman/5.5/en/replace.html
     *
     * @param array $dados
     * @return int
     */
    final protected function replace(array $dados){
        $dados = self::antiInjection($dados);
        return $this->exec('REPLACE '.$this.' '.self::createInsertReplace($dados));
    }

    final private static function createInsertReplace(array $dados){
        $chaves = array_keys($dados);
        if (!is_array($dados[$chaves[0]]))
            $dados = array($dados);

        $colunas = null;
        foreach ($dados as $valor)
            foreach ($valor as $chave => $dado)
                $colunas[$chave] = $chave;

        $VALUES = '';
        foreach ($dados as $value){
            $linha = '';
            foreach ($colunas as $coluna)
                $linha .= ($linha == ''? '': ', ') . (!isset($value[$coluna]) || self::is_null($value[$coluna])? 'NULL' : '"'. ((string) $value[$coluna]). '"');

            $VALUES .= ($VALUES == ''? '': ', ') . '('.$linha.')';
        }

        foreach ($colunas as $k => $coluna)
            $colunas[$k] = '`' . $coluna . '`';

        return ('('.implode(', ', $colunas).') VALUES '.$VALUES);
    }

    /**
     * Cria um ou varios UPDATE com os dados pasados no array e com o WHELE definido.
     *
     * Este array pode ser passado de duas formas.
     *
     * Forma 1, array simples.<br/>
     * Exp.:<br/>
     * <pre>array(
     * &nbsp;&nbsp;&nbsp;&nbsp;[id] => 0,
     * &nbsp;&nbsp;&nbsp;&nbsp;[nome] => Arnaldo,
     * &nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Silva
     * )</pre>
     *
     *
     * Forma 2, array com 1 subnivel.<br/>
     * Exp.:<br/>
     * <pre>array(
     * &nbsp;&nbsp;&nbsp;&nbsp;[0] => array(
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[id] => 0,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[nome] => Arnaldo,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Silva
     * &nbsp;&nbsp;&nbsp;&nbsp;),
     * &nbsp;&nbsp;&nbsp;&nbsp;[1] => array(
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[id] => 1,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[nome] => Amaral,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Costa
     * &nbsp;&nbsp;&nbsp;&nbsp;)
     * )</pre>
     *
     * <b>OBS</b>.: Quando pasado desta maneira varios updates ser?o feitos.
     *
     * O WHELE ? constrido com ShotTegs e ? subistituido pelo valor corespondente<br/>
     * no array de dados.<br/>
     * Exp.:<br/>
     * Tome para este exemplo o array da forma 1.<br/>
     * $where = 'id="[id]"';
     *
     * RESULTADO:<br/>
     * UPDATE (tabela) SET `nome`="Arnaldo", `sobrenome`="da Silva" WHERE id="0";
     *
     * <b>OBS</b>.: os indices que coresponderer a alguma sortTeg no WHERE n?o s?o colocados
     * como valorer serem upados.
     *
     * @param array $dados
     * @param string $where
     */
    final protected function update(array $dados, $where = NULL){

        $dados = self::antiInjection($dados);

        $chaves = array_keys($dados);
        if (!is_array($dados[$chaves[0]]))
            $dados = array($dados);

        foreach ($dados as $dado){

            $w = self::prepare($where, $dado);

            $valores = '';
            foreach($dado as $chaves => $valor) {
                //echo '<pre>`' . $chaves . '`= |'. (self::is_null($valor)? 'true': 'false'). '| "' . $valor . '"</pre>';
                $valores .= (empty($valores) ? '' : ', ') . '`' . $chaves . '`=' . (self::is_null($valor) ? 'NULL' : '"' . $valor . '"'); }

            $this->exec('UPDATE '.$this.' SET '.$valores.' WHERE '.$w.' ;', true);

        }
        $this->clierTemps();
    }

    /**
     * Cria um ou varios DELETE com os dados pasados no array e com o WHELE definido.
     *
     * Este array pode ser passado de duas formas.
     *
     * Forma 1, array simples.<br/>
     * Exp.:<br/>
     * <pre>array(
     * &nbsp;&nbsp;&nbsp;&nbsp;[id] => 0,
     * &nbsp;&nbsp;&nbsp;&nbsp;[nome] => Arnaldo,
     * &nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Silva
     * )</pre>
     *
     *
     * Forma 2, array com 1 subnivel.<br/>
     * Exp.:<br/>
     * <pre>array(
     * &nbsp;&nbsp;&nbsp;&nbsp;[0] => array(
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[id] => 0,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[nome] => Arnaldo,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Silva
     * &nbsp;&nbsp;&nbsp;&nbsp;),
     * &nbsp;&nbsp;&nbsp;&nbsp;[1] => array(
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[id] => 1,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[nome] => Amaral,
     * &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[sobrenome] => da Costa
     * &nbsp;&nbsp;&nbsp;&nbsp;)
     * )</pre>
     *
     * <b>OBS</b>.: Quando pasado desta maneira varios deletes ser?o feitos.
     *
     * O WHELE ? constrido com ShotTegs e ? subistituido pelo valor corespondente<br/>
     * no array de dados.<br/>
     * Exp.:<br/>
     * Tome para este exemplo o array da forma 1.<br/>
     * $where = 'id="[id]"';
     *
     * RESULTADO:<br/>
     * DELETE FROM (tabela) WHERE id="0";
     *
     * <b>OBS</b>.: os indices que coresponderer a alguma sortTeg no WHERE n?o s?o colocados
     * como valorer serem upados.
     *
     * O <code>$where</code> ? opcional e se nao pasodo o DELETE ? montado de maneira a <br/>
     * todos os indices estaren no WHERE.<br/>
     * EXP.:<br/>
     * Tome para este exemplo o array da forma 1.
     *
     * RESULTADO:<br/>
     * DELETE FROM (tabela) WHERE id="0" and nome="Amaral" and sobrenome="da Silva";
     *
     * @param array $dados
     * @param string $where
     * @return int
     */
    final protected function delete(array $array, $where = NULL){
        $array = self::antiInjection($array);

        $keys = array_keys($array);
        if(!is_array($array[$keys[0]]))
            $array = array($array);

        foreach($array as $value){
            $w = '';
            if($where !== NULL)
                $w = $this->prepare($where, $value);

            else foreach($value as $chave => $valor)
                if(is_string($chave)) $w .= ($w == ''? '': ' and ') . $chave . ' ' . (self::is_null($valor)? 'IS NULL': '= "' . $valor . '"');

            $this->exec("DELETE FROM {$this} WHERE {$w};", true);
        }
        $this->clierTemps();
    }

    final static function is_null($var){
        return ($var === NULL || (is_string($var) && strlen($var) == 4 && preg_match('/^null$/i', $var) === 1));
    }

}