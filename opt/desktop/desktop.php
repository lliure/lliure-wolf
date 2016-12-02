<?php

class Desktop extends DB{

    public function __construct(){
        parent::__construct(PREFIXO. 'lliure_desktop');
    }

    public function set($dados){
        $dados = array_intersect_key($dados, [
            'nome'   => '',
            'link'   => '',
            'imagem' => '',
        ]); self::insert($dados);
    }
}