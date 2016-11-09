<?php
// ---------------------------------------------------------------------------
// Leitor de arquivos SQL por Alfred Reinold Baudisch<alfred_baudisch@hotmail.com>
// Copyright ? 2003, 2004 AuriumSoft - www.auriumsoft.com.br
// ---------------------------------------------------------------------------
// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
// 
// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
// 
// You should have received a copy of the GNU Lesser General Public
// License along with this library; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
// ---------------------------------------------------------------------------

/**
* Executa arquivos SQL imprimindo mensagens de erros, nomes de tabelas criadas, etc..
* Para qualquer tipo de banco de dados, bastando somente alterar a fun??o de query e erro
*
* @since Mar 15, 2004
* @version 1.1.2
* @author Alfred Reinold Baudisch<alfred_baudisch@hotmail.com>
* @update Jeison Frasson <lliure@lliure.com.br>
*/
class leitor_sql extends DB{

    /**
    * Contagem de cláusulas SQL Insert
    * @var array $foi
    */
    private $msgs;

    /**
    * Construtor. Aqui onde tudo ocorre
    *
    * @param string $arquivo_sql Nome do arquivo com as instru??es SQL
    * @since Mar 15, 2004
    * @access public
    */
    public function __construct($arquivo_sql, $prefixo_atual = null, $prefixo_novo = null){
        /**
        * Inicializa as vari?veis de contagem e erros.
        * Isso reseta as mesmas caso a fun??o seja chamada mais de uma vez na mesma p?gina,
        * evitando a impress?o das mesmas mensagens repetidamente
        */
        $this->msgs = array();

        // Verifica se arquivo existe
        if(!file_exists($arquivo_sql)){
            $this->msgs[] = array('danger' => "<strong>ERROR:</strong> O arquivo <strong>{$arquivo_sql}</strong> inexistente!"); return;}

        /**
        * Importa o arquivo SQL para um array
        */
        
        $conteudo = file($arquivo_sql);

        /**
        * Inicializa vari?veis a se usar nas formata??es e limpezas
        */
        $i = 0;
        $dados = array();

        /**
        * Formatações e limpezas
        */
        foreach($conteudo as $linha){
            // Remove espaços em branco nas "bordas"
            $linha = trim($linha);

            // Caso for linha em branco ou linha com comentário, "pula" a mesma
            if(empty($linha) || (substr($linha, 0, 1) == '#')) continue;

            // Adiciona quebra de linha e instruções da mesma
			$dados[$i] = (!isset($dados[$i]) ? "\n". $linha : $dados[$i]. "\n". $linha);
            
            /**
            * Encontrado um ";" no final da linha, então, instrução encerrada.
            * Pula para o próximo inndice do array
            */
            if(substr(rtrim($linha), -1, 1) == ';') ++$i;
        }

        $sqls = array();

        /**
         * Separa e muda o prefixos dos sqls;
         */
        foreach($dados as $k => $atual) {

            // Limpa os ";"
            $atual = rtrim($atual, ';');
			
            /**
            * Pega nome da tabela criada
            * $resultado[4] conterá o nome da mesma
            */
            if(preg_match('/(.*)(CREATE TABLE (IF NOT EXISTS )?)(\\S+)(.*)/is', $atual, $r))
                $sqls[$k.':CREATE TABLE:'. self::limpa_acento(self::altera_prefixo($r[4], $prefixo_atual, $prefixo_novo))] =
                    $r[1]. $r[2]. self::altera_prefixo($r[4], $prefixo_atual, $prefixo_novo). $r[5];
               
            /**
            * Pega o nome da tabela onde dados foram inseridos
            * $resultado[3] conterá o nome da mesma
            */
            elseif(preg_match('/(.*)(INSERT INTO )(\\S+)(.*)/is', $atual, $r))
                $sqls[$k.':INSERT INTO:'. self::limpa_acento(self::altera_prefixo($r[3], $prefixo_atual, $prefixo_novo))] =
                    $r[1]. $r[2]. self::altera_prefixo($r[3], $prefixo_atual, $prefixo_novo). $r[4];

            /**
             * Comandos DROP TABLE
             * $resultado[4] conterá o nome da mesma
             */
            elseif(preg_match('/(.*)(DROP TABLE (IF EXISTS )?)(\\S+)(.*)/is', $atual, $r))
                $sqls[$k.':DROP TABLE:'. self::limpa_acento(self::altera_prefixo($r[4], $prefixo_atual, $prefixo_novo))] =
                    $r[1]. $r[2]. self::altera_prefixo($r[4], $prefixo_atual, $prefixo_novo). $r[5];

            /**
             * Comandos DROP DATABASE
             * $resultado[4] conterá o nome da mesma
             */
            elseif(preg_match('/(.*)(DROP DATABASE (IF EXISTS )?)(\\S+)(.*)/is', $atual, $r))
                $sqls[$k.':DROP DATABASE:'. self::limpa_acento(self::altera_prefixo($r[4], $prefixo_atual, $prefixo_novo))] =
                    $r[1]. $r[2]. self::altera_prefixo($r[4], $prefixo_atual, $prefixo_novo). $r[5];
            
            /**
             * CREATE DATABASE
             * $resultado[3] conterá o nome da mesma
             */
            elseif(preg_match('/(.*)(CREATE DATABASE )(\\S+)(.*)/is', $atual, $r))
                $sqls[$k.':CREATE DATABASE:'. self::limpa_acento(self::altera_prefixo($r[3], $prefixo_atual, $prefixo_novo))] =
                    $r[1]. $r[2]. self::altera_prefixo($r[3], $prefixo_atual, $prefixo_novo). $r[4];
            
            /**
             * ALTER TABLE
             * $resultado[3] conterá o nome da mesma
             */
            elseif(preg_match('/(.*)(ALTER TABLE )(\\S+)(.*)/is', $atual, $r))
                $sqls[$k.':ALTER TABLE:'. self::limpa_acento(self::altera_prefixo($r[3], $prefixo_atual, $prefixo_novo))] =
                    $r[1]. $r[2]. self::altera_prefixo($r[3], $prefixo_atual, $prefixo_novo). $r[4];
        }

        foreach ($sqls as $k => $sql){
            list($k, $type, $table) = explode(':', $k);
            try{
                $t = parent::exec($sql);
                $this->msgs[] = array('success' => '<strong>OK:</strong> '. $type. ' '. $table. ($t > 0? ' | Affected rows: '. $t: ''));
            }catch (Exception $e){
                $this->msgs[] = array('danger' => '<strong>ERROR:</strong> '. $type. ' '. $table. ' | '. $e->getMessage());
            }
        }

    }

    /**
    * Limpa os acentos dos nomes das tabelas, ex: `tabela`
    *
    * @param string $dados
    * @since Mar 15, 2004
    * @access private
     * @return string o nome limpo
    */
    private static function limpa_acento($dados){
        return trim($dados, '`');
    }

    /**
    * Altera prefixo das tabelas, ex: teste_tabela
    */
    private static function altera_prefixo($dados, $prefixo_atual, $prefixo_novo)  {
        if($prefixo_atual && $prefixo_novo != null && $prefixo_atual != $prefixo_novo)
            return preg_replace('/^(`)?'. preg_quote($prefixo_atual). '/i', '${1}'. preg_quote($prefixo_novo), $dados);
        return $dados;
    }

    /**
     * @return array
     */
    public function getMsgs(){
        return $this->msgs;
    }

}