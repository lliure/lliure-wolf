<?php

class MetaClass extends Meta{

    public static function set($key, $id = 0, $value = null){
        $registro = new MetaClass(get_called_class());
        $registro->setValeu($key, $id, $value);
    }

    public static function get($key, $id = 0){
        $registro = new MetaClass(get_called_class());
        return $registro->getValeu($key, $id);
    }

    public static function del($key, $id = 0){
        $registro = new MetaClass(get_called_class());
        return $registro->delValeu($key, $id);
    }

}