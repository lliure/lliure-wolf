<?php


class Liberacao extends DB{


    public function __construct(){
        parent::__construct(PREFIXO. 'lliure_liberacao');}

    public function get($id = null, $ord = array(), $pg = null, $pp = null){
        global $_ll;
        if(!isset($id) && !isset($id['login']) && isset($_ll['user']['login'])) $id['login'] = $_ll['user']['login'];
        list($where, $order, $limit) = self::WhereOrderLimit($id, $ord, $pg, $pp);
        return self::select("SELECT * FROM {$this}{$where}{$order}{$limit}");
    }

    public function set(array $dados){
        $dados = array_intersect_key(array_merge($k = array(
            'operation_type' => '',
            'operation_load' => '',
            'login' => '',
        ), $dados), $k);
        $dados['hash'] = Senha::create("{$dados['login']}/{$dados['operation_type']}/{$dados['operation_load']}");
        self::insert($dados);
    }

    public function upd(array $dados){
        $dados = array_intersect_key(array_merge($k = array(
            'id' => null,
            'operation_type' => '',
            'operation_load' => '',
            'login' => '',
        ), $dados), $k);
        $dados['hash'] = Senha::create("{$dados['login']}/{$dados['operation_type']}/{$dados['operation_load']}");
        if(isset($dados['id'])) self::update($dados, 'id [= id]');
        else return false;
    }

    public function del($id = null){
        list($where) = self::WhereOrderLimit($id);
        if(empty($where)) return false;
        self::delete($where);
    }

    public static function test(
        $operation_type,
        $operation_load,
        $login = null
    ){
        global $_ll;
        if($_ll['user']['grupo'] === 'dev') return true;
        $login = ((!!$login)? $login: $_ll['user']['login']);
        $self = new self;
        $liberation = self::first($self->get(array(
            'operation_type' => $operation_type,
            'operation_load' => $operation_load,
            'login' => $login,
        )));
        return Senha::valid("$login/$operation_type/$operation_load", $liberation['hash']);
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