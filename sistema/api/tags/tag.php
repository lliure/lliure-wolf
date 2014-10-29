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

header ('Content-type: text/html; charset=ISO-8859-1');

define('BASEPATH', realpath(dirname(__FILE__). '/../../'));

require_once BASEPATH. '/etc/bdconf.php';
require_once BASEPATH. '/includes/functions.php';
require_once 'inicio.php';

$dados = rawurldecode($_GET['d']);
$dados = jf_decode($_SESSION['logado']['token'], $dados);
$dados = unserialize($dados);


class tag_gets implements tag_interface{

    public function get() {
        
        global $dados;
        //echo '<pre>'. print_r($dados, true). '</pre>';

        if(!isset($dados['buscaTable']))
            $r = 'SELECT id, '. $dados['campoDeTag']. ' as tag FROM '. $dados['tabela']. ' WHERE '. $dados['campoDeLigacao']. '="'. $dados['idDeLigacao']. '"';

        else
            $r = 'SELECT a.id, b.'. $dados['buscaTableTag']. ' as tag FROM '. $dados['tabela']. ' a LEFT JOIN '. $dados['buscaTable']. ' b ON a.'. $dados['campoDeTag']. ' = b.'. $dados['buscaTableId']. ' WHERE a.'. $dados['campoDeLigacao']. '="'. $dados['idDeLigacao']. '"';

        $r = mysql_query($r);
        $row = array();
        while($row[] = mysql_fetch_assoc($r) or array_pop($row));
        return $row;
        
    }

    public function query(){
        
        global $dados;
        
        if(!isset($dados['buscaTable']))
            $r = ('SELECT id, '. $dados['campoDeTag']. ' as tag FROM '. $dados['tabela']. ' WHERE '. $dados['campoDeLigacao']. ' not in(SELECT id FROM '. $dados['tabela']. ' WHERE '. $dados['campoDeLigacao']. '="'. $dados['idDeLigacao']. '") and '. $dados['campoDeTag']. ' LIKE "%'. jf_anti_injection($_GET['query']).'%"');

        else
            $r = ('SELECT '. $dados['buscaTableId']. ' as id, '. $dados['buscaTableTag']. ' as tag FROM '. $dados['buscaTable']. ' WHERE '. $dados['buscaTableId']. ' not in(SELECT '. $dados['campoDeTag']. ' FROM '. $dados['tabela']. ' WHERE '. $dados['campoDeLigacao']. '="'. $dados['idDeLigacao']. '" GROUP BY '. $dados['campoDeTag']. ') and '. $dados['buscaTableTag']. ' LIKE "%'. jf_anti_injection($_GET['query']).'%"');

        $r = mysql_query($r);
        $row = array();
        while($row[] = mysql_fetch_assoc($r) or array_pop($row));
        return $row;
        
    }
    
    public function del() {
        
        global $dados;

        if(($erro = jf_delete($dados['tabela'], array('id' => jf_anti_injection($_GET['del'])))))
            return array('erro' => $erro);
        
        return array('status' => 'ok');
        
    }

    public function set() {
        
        global $dados;

        if(!isset($dados['buscaTable'])){
            if(($erro = jf_insert($dados['tabela'], array($dados['campoDeLigacao'] => $dados['idDeLigacao'], $dados['campoDeTag'] => $_GET['set']))))
                return array('erro' => $erro);

        }else{

            if (isset($_GET['id']) && $_GET['id'] != NULL){
                if(($erro = jf_insert($dados['tabela'], array($dados['campoDeLigacao'] => $dados['idDeLigacao'], $dados['campoDeTag'] => $_GET['id']))))
                    return array('erro' => $erro);

            }else{
                if(
                    ($erro = jf_insert($dados['buscaTable'], array($dados['buscaTableTag'] => $_GET['set']))) ||
                    ($erro = jf_insert($dados['tabela'], array($dados['campoDeLigacao'] => $dados['idDeLigacao'], $dados['campoDeTag'] => mysql_insert_id())))
                )
                    return array('erro' => $erro);
            }

        }
		
        return array('id' => mysql_insert_id(), 'tag' => $_GET['set']);
        
    }

}

tag_server::rum(new tag_gets);