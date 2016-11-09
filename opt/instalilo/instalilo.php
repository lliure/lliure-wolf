<?php

class Instalilo extends DB{

    public function __construct(){
        parent::__construct(PREFIXO. 'lliure_apps');}


    public function get($id = null, $ord = array(), $pg = null, $pp = null){
        list($where, $order, $limit) = self::WhereOrderLimit($id, $ord, $pg, $pp);
        return self::select("SELECT * FROM {$this}{$where}{$order}{$limit}");
    }

    public function upd(array $dados){
        $dados = array_intersect_key(array_merge($k = array(
            'id' => null,
            'nome' => '',
            'pasta' => '',
        ), $dados), $k);
        if(isset($dados['id'])) self::update($dados, 'id [= id]');
        else return false;
    }

    public function set(array $dados){
        $dados = array_intersect_key(array_merge($k = array(
            'nome' => '',
            'pasta' => '',
        ), $dados), $k);
        self::insert($dados);
    }

    public function del($id = null){
        list($where) = self::WhereOrderLimit($id);
        if(empty($where)) return false;
        self::delete($where);
    }

    /**
     * @param null|string|array|double $id query para o WHERE caso um numero intemde que é o id
     * @param array $ord contem as colunas para ordernar
     * @param null $pg PaGina
     * @param null $pp Por Pagina
     * @return array array($where, $limit, $order)
     */
    private static function WhereOrderLimit($id = null, $ord = array(), $pg = null, $pp = null){
        $id = (($id === null || is_string($id) || is_array($id))? $id: array('id' => $id));
        $where = array();
        if(is_array($id)){
            $dados = array();
            foreach (parent::antiInjection($id) as $col => $val){
                $d = explode(':', $col);
                $c = array_reverse(explode('.', $d[0]));
                foreach ($c as $k => $v){$c[$k] = "`{$v}`"; break; }
                $c = implode('.', array_reverse($c));
                $dados[$c] = $val;
                if(count($d) == 1) $where[] = "{$c} [== {$c}]";
                if(count($d) == 2) $where[] = "{$c} [{$d[1]} {$c}]";}
            $where = parent::prepare(implode(' AND ', $where), $dados);}
        else
            $where = (string) $id;
        if(!empty($where)) $where = " WHERE ({$where})";

        $order = array();
        if(!!$ord) foreach ($ord as $col => $sent) $order []=  ((is_numeric($col))? '': $col. ' '). $sent;
        $order = ((!empty($order))? ' ORDER BY '. implode($order, ', '): '');

        $pg = ($pg !== null? max(1, $pg): $pg); $pp = ($pp !== null? max(1, $pp): $pp);
        $limit = ($pg !== null && $pp !== null? ' LIMIT '. ($pp * ($pg - 1)) . ', ' . ($pp): '');

        return array($where, $limit, $order);
    }

}