<?php

/**
 * Class Meta
 *
 *------------------------------------*

CREATE TABLE `[nome]_meta` (
    `key` VARCHAR(255) NOT NULL,
    `id` BIGINT(20) NOT NULL,
    `type` VARCHAR(4) NOT NULL,
    `value` LONGTEXT NOT NULL,
    UNIQUE INDEX `unique_key_id` (`key`, `id`)
)ENGINE=InnoDB;

 *------------------------------------**/

abstract class Meta extends DB{

    /**  @type string tipo da tabela de onde sera lido os metas. o nome da tabela entre PREFIXO e '_meta'; */
    public function __construct($type){
        parent::__construct(PREFIXO. $type. '_meta');
    }

    final protected function setValeu($key, $id, $value = null){
        if(empty($key) || !is_string($key) || (empty($id) && !($id === 0 || $id === '0')))
            throw new Exception('Erro em algum argumento.');

        $value = (($type = self::type($value)) == 'OBJ'? serialize($value): $value);
        $r = parent::select('SELECT count(*) as total FROM '. $this. ' WHERE `key` = "'. parent::antiInjection($key). '" AND `id` = "'. parent::antiInjection($id). '"');
        if($r[0]['total'] >= 1)
            parent::update(array('type' => $type, 'value' => $value), '`key` = "'. parent::antiInjection($key). '" AND `id` = "'. parent::antiInjection($id). '"');
        else
            parent::insert(array('type' => $type, 'key' => $key, 'id' => $id, 'value' => $value));
    }

    final protected function getValeu($key, $id){
        if(empty($key) || !is_string($key) || (empty($id) && !($id === 0 || $id === '0')))
            throw new Exception('Erro em algum argumento.');

        $r = parent::select('SELECT `type`, `value` FROM '. $this. ' WHERE `key` = "'. parent::antiInjection($key). '" AND `id` = "'. parent::antiInjection($id). '"');
        if(parent::numRows($r) >= 1)
            return (($r[0]['type']) == 'OBJ'? unserialize($r[0]['value']): $r[0]['value']) ;
        else
            throw new Exception('Key não existe');
    }

    final protected function delValeu($key, $id = null){
        if(empty($key)  || !is_string($key))
            throw new Exception('Erro em algum argumento.');

        if($id !== null)
            return parent::delete(array('key' => $key), '`key´="[key]" AND id="[id]"');
        else
            return parent::delete(array('key' => $key), '`key´="[key]"');
    }

    final private static function type($value){
        if(is_array($value) || is_object($value)) return 'OBJ';
        if(is_numeric($value)) return 'NUB';
        if(is_string($value)) return 'TEX';
        return 'NUL';
    }


}