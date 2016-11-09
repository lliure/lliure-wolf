<?php

/**
 * Description of db
 *
 * @author Rodrigo
 */

/*
class DB
{

    const
        PDO = 'PDO',
        MYSQL = 'MYSQL';

    protected static
        $DB = NULL,
        $type = NULL;

    protected
        $tabela,
        $tempPoxFix = NULL,
        $tempTab = NULL,
        $queryList = array();

    public function __construct($tabela)
    {
        $this->tabela = self::antiInjection($tabela);
    }

    static function antiInjection($sql)
    {
        if (is_array($sql)) {
            foreach ($sql as $chave => $valor)
                $sql[self::antiInjection($chave)] = self::antiInjection($valor);
        } elseif (is_string($sql)) {
            $sql = @get_magic_quotes_gpc() ? stripslashes($sql) : $sql;
            $sql = trim($sql);
            $sql = addslashes($sql);
        }
        return $sql;
    }

    final static function fetch(array &$result)
    {
        $retorno = current($result);
        if ($retorno !== FALSE)
            next($result);
        return $retorno;
    }

    final static function numRows(array $result)
    {
        return count($result);
    }

    final static function lastInsertId()
    {
        return self::insert_id();
    }

    final static function insert_id()
    {
        if (self::$type == self::MYSQL) {
            return mysql_insert_id(self::$DB);
        } elseif (self::$type == self::PDO) {
            return self::$DB->lastInsertId();
        }
    }

    final protected static function shortTagReplace($stringShortTag, array &$dados, $naoDeletar = FALSE)
    {
        return self::prepare($stringShortTag, $dados, $naoDeletar);
    }

    final protected static function prepare($stringShortTag, array &$dados, $naoDeletar = FALSE)
    {

        $retorno = $stringShortTag;
        $e = array();
        $sts = array();

        if (!class_exists('ShortTag')) {
            $matches = null;
            $returnValue = preg_match_all('/(?:\\[(?:(?:(?:\\\'(?:[^\\\'\\\\]|\\\\.)*\\\'|\\"(?:[^"\\\\]|\\\\.)*\\"|(?:[^\\ \\]]*))(?:[\\ ])?)+)\\])/im', $stringShortTag, $matches, PREG_OFFSET_CAPTURE);

            if ($returnValue > 0 && isset($matches[0]))
                foreach ($matches[0] as $k => $m)
                    $sts[$k] = array(
                        'shortTag' => explode(' ', trim($m[0], '[]')),
                        'start' => $m[1], 'length' => strlen($m[0]));

        } else
            $sts = ShortTag::Search($stringShortTag, null);


        foreach (array_reverse($sts) as $k => $st) {
            if (count($st['shortTag']) == 1)
                if (isset($dados[$st['shortTag'][0]]))
                    $retorno = substr_replace($retorno, $dados[($e[] = $st['shortTag'][0])], $st['start'], $st['length']);

            if (count($st['shortTag']) == 2 || count($st['shortTag']) == 3) {
                $comp = strtoupper($st['shortTag'][0]);
                if (!in_array($comp, array(
                    "=", "==", "IS", "IN",
                    ">=", "<=", "!=", ">", "<",
                    "NOT", "NOT_IS", "NOT_IN",
                    'LIKE', 'LIKE_LEFT', 'LIKE_RIGHT', 'LIKE_CLEAR',
                    'NOT_LIKE', 'NOT_LIKE_LEFT', 'NOT_LIKE_RIGHT', 'NOT_LIKE_CLEAR'
                ))
                ) continue;

                if (array_key_exists($st['shortTag'][1], $dados))
                    $value = $dados[($e[] = $st['shortTag'][1])];
                elseif (array_key_exists('_content', $st['shortTag']))
                    $value = eval('return ' . $st['shortTag']['_content'] . ';');
                else continue;

                $value = ((is_object($value)) ? (array)$value : $value);

                $comp = (in_array($comp, array('==', 'IN', 'IS')) ? '=' : $comp);
                $comp = (in_array($comp, array('NOT', 'NOT_IN', 'NOT_IS')) ? '!=' : $comp);
                $nega = !(in_array($comp, array('=', '<=', '>=', 'LIKE', 'LIKE_LEFT', 'LIKE_RIGHT', 'LIKE_CLEAR')));

                $like =
                    (in_array($comp, array('LIKE_CLEAR', 'NOT_LIKE_CLEAR')) ? null :
                        (in_array($comp, array('LIKE', 'NOT_LIKE')) ? 0 :
                            (in_array($comp, array('LIKE_LEFT', 'NOT_LIKE_LEFT')) ? -1 :
                                (in_array($comp, array('LIKE_RIGHT', 'NOT_LIKE_RIGHT')) ? 1 : false))));

                if (is_array($value)) {
                    $b = array();
                    foreach ($value as $v) $b[] = (is_bool($v) ? ($v ? 'TRUE' : 'FALSE') : (is_null($v) ? 'NULL' : ('"' . ((string)$v) . '"')));
                    $replace = ($nega ? 'NOT ' : '') . 'IN(' . implode(', ', $b) . ')';

                } elseif (is_null($value)) $replace = ($nega ? 'NOT ' : '') . 'IS NULL';
                elseif (is_bool($value)) $replace = ($nega ? '!=' : '=') . ' ' . ($value ? 'TRUE' : 'FALSE');
                elseif ($like !== false) $replace = ($nega ? 'NOT ' : '') . 'LIKE "' . ($like !== null && $like <= 0 ? '%' : '') . ((string)$value) . ($like !== null && $like >= 0 ? '%' : '') . '"';
                else    ($replace = ($comp . ' "' . ((string)$value) . '"'));

                $retorno = substr_replace($retorno, $replace, $st['start'], $st['length']);
            }
        }

        if (!$naoDeletar && !empty($e)) foreach ($e as $d) unset($dados[$d]);

        return $retorno;

    }

    final public function __toString()
    {
        if ($this->tempTab !== NULL)
            return $this->tempTab;
        else
            return $this->tabela . ($this->tempPoxFix !== NULL ? $this->tempPoxFix : '');
    }

    public function queryLog()
    {
        echo '<pre>', print_r($this->getQueryList(TRUE), TRUE), '</pre>';
    }

    public function getQueryList($quant = null)
    {
        $retorno = null;
        if (!empty($this->queryList)) {
            if ($quant === null)
                $retorno = $this->queryList[count($this->queryList) - 1];
            elseif ($quant === true)
                $retorno = $this->queryList;
            elseif (is_numeric($quant))
                for ($i = (((count($this->queryList) - $quant) <= 0) ? 0 : count($this->queryList) - $quant); $i < count($this->queryList); $i++)
                    $retorno[] = $this->queryList[$i];
        }
        return $retorno;
    }

    final protected function setTempPosFix($tempPoxFix)
    {
        $this->tempPoxFix = $tempPoxFix;
        return $this;
    }

    final protected function setTempTab($tempTab)
    {
        $this->tempTab = $tempTab;
        return $this;
    }

    protected static function conectar($basetype = null, $hostName = null, $userName = null, $password = null, $tableName = null)
    {

        $bdconf['basetype'] = db_type;
        $bdconf['hostName'] = db_host;
        $bdconf['userName'] = db_user;
        $bdconf['password'] = db_password;
        $bdconf['tableName'] = db_database;

        if (self::$DB !== NULL)
            return self::$DB;

        if (!(!isset($GLOBALS['hostname_conexao'])
            || (!isset($GLOBALS['username_conexao']))
            || (!isset($GLOBALS['password_conexao']))
            || (!isset($GLOBALS['banco_conexao'])))
        ) {
            $bdconf['basetype'] = 'mysql';
            $bdconf['hostName'] = $GLOBALS['hostname_conexao'];
            $bdconf['userName'] = $GLOBALS['username_conexao'];
            $bdconf['password'] = $GLOBALS['password_conexao'];
            $bdconf['tableName'] = $GLOBALS['banco_conexao'];
        }

        if (class_exists('PDO')) {
            try {
                self::$DB = new PDO($bdconf['basetype'] . ':host=' . $bdconf['hostName'] . ';dbname=' . $bdconf['tableName'], $bdconf['userName'], $bdconf['password']);
                self::$type = self::PDO;
                return self::$DB;

            } catch (PDOException $e) {
                throw new Exception('Falha de conexão: ' . $e->getMessage(), 1);
            }

        } else {
            if ((self::$DB = @mysql_connect($bdconf['hostName'], $bdconf['userName'], $bdconf['password'])) === FALSE)
                throw new Exception('<strong>Não foi possivel realizar a conexão com banco de dados</strong><br>verifique as configurações do arquivo bdconf.php em /etc', 1);

            mysql_select_db($bdconf['tableName'], self::$DB);
            self::$type = self::MYSQL;
            return self::$DB;
        }

    }

    final protected function select($query, $persistir = FALSE)
    {
        if (self::$DB === NULL) self::conectar();
        if (!$persistir) $this->clierTemps();
        $query =& $this->setQueryList($query);
        try {
            if (self::$type == self::MYSQL) {
                $result = mysql_query($query, self::$DB);
                if ($result === FALSE) {
                    $e = $this->error();
                    $query = array($query, $e[1], $e[2]);
                    throw new Exception((string)$e[2], (float)$e[1]);
                }
                $return = array();
                while (($return[] = mysql_fetch_assoc($result)) or array_pop($return)) ;
                return $return;
            } elseif (self::$type == self::PDO)
                return ($r = self::$DB->query($query)) ? $r->fetchAll(PDO::FETCH_ASSOC) : array();
        } catch (Exception $e) {
            $query = array($query, $e->getCode(), $e->getMessage());
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    final protected function clierTemps()
    {
        $this->tempPoxFix = NULL;
        $this->tempTab = NULL;
    }

    protected function &setQueryList($query)
    {
        $k = count($this->queryList);
        $this->queryList[$k] = $query;
        return $this->queryList[$k];
    }

    final protected function error()
    {
        if (self::$type == self::MYSQL)
            return array(0, mysql_errno(), mysql_error());

        else
            return self::$DB->errorInfo();
    }

    final protected function insert(array $dados)
    {
        $dados = self::antiInjection($dados);
        return $this->exec('INSERT ' . $this . ' ' . self::createInsertReplace($dados));
    }

    final protected function exec($query, $persistir = FALSE)
    {
        if (self::$DB === NULL) self::conectar();
        if (!$persistir) $this->clierTemps();
        $query =& $this->setQueryList($query);
        if (self::$type == self::MYSQL) {
            $r = mysql_query($query, self::$DB);
            if ($r === TRUE) $r = mysql_affected_rows(self::$DB);
        } elseif (self::$type == self::PDO)
            $r = self::$DB->exec($query);
        if ($r === FALSE) {
            $e = $this->error();
            $query = array($query, $e[1], $e[2]);
            throw new Exception((string)$e[2], (float)$e[1]);
        }
        return $r;
    }

    final private static function createInsertReplace(array $dados)
    {
        $chaves = array_keys($dados);
        if (!is_array($dados[$chaves[0]]))
            $dados = array($dados);

        $colunas = null;
        foreach ($dados as $valor)
            foreach ($valor as $chave => $dado)
                $colunas[$chave] = $chave;

        $VALUES = '';
        foreach ($dados as $value) {
            $linha = '';
            foreach ($colunas as $coluna)
                $linha .= ($linha == '' ? '' : ', ') . (!isset($value[$coluna]) || self::is_null($value[$coluna]) ? 'NULL' : '"' . ((string)$value[$coluna]) . '"');

            $VALUES .= ($VALUES == '' ? '' : ', ') . '(' . $linha . ')';
        }

        foreach ($colunas as $k => $coluna)
            $colunas[$k] = '`' . $coluna . '`';

        return ('(' . implode(', ', $colunas) . ') VALUES ' . $VALUES);
    }

    final static function is_null($var)
    {
        return ($var === NULL || (is_string($var) && strlen($var) == 4 && preg_match('/^null$/i', $var) === 1));
    }

    final protected function replace(array $dados)
    {
        $dados = self::antiInjection($dados);
        return $this->exec('REPLACE ' . $this . ' ' . self::createInsertReplace($dados));
    }

    final protected function update(array $dados, $where = NULL)
    {

        $dados = self::antiInjection($dados);

        $chaves = array_keys($dados);
        if (!is_array($dados[$chaves[0]]))
            $dados = array($dados);

        foreach ($dados as $dado) {

            $w = self::prepare($where, $dado);

            $valores = '';
            foreach ($dado as $chaves => $valor) {
                //echo '<pre>`' . $chaves . '`= |'. (self::is_null($valor)? 'true': 'false'). '| "' . $valor . '"</pre>';
                $valores .= (empty($valores) ? '' : ', ') . '`' . $chaves . '`=' . (self::is_null($valor) ? 'NULL' : '"' . $valor . '"');
            }

            $this->exec('UPDATE ' . $this . ' SET ' . $valores . ' WHERE ' . $w . ' ;', true);

        }
        $this->clierTemps();
    }

    final protected function delete(array $array, $where = NULL)
    {
        $array = self::antiInjection($array);

        $keys = array_keys($array);
        if (!is_array($array[$keys[0]]))
            $array = array($array);

        foreach ($array as $value) {
            $w = '';
            if ($where !== NULL) {
                $w = $this->prepare($where, $value);
            } else {
                foreach ($value as $chave => $valor)
                    if (is_string($chave))
                        $w .= ($w = '' ? '' : ' and ') . $chave . ' ' . (self::is_null($valor) ? 'IS NULL' : '= "' . $valor . '"');
            }

            $this->exec('DELETE FROM ' . $this . ' WHERE ' . $w . ' ;', true);
        }
        $this->clierTemps();
    }

}*/