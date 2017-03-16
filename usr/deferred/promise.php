<?php

namespace Deferred;

class Promise {

    protected $state = null, $data;
    private $aDone = [], $aFail = [], $aAlways = [];

    public function always($callback){
        self::addToList($this->aAlways, $callback);
        return $this;
    }

    private function addToList(&$list, $callback){
        if(is_callable($callback)) $list[] = $callback;
        $this->run();
    }

    protected function run($state = null, $datas = []){
        $this->state = (($state !== null)? $state: $this->state);
        $this->data = (($state !== null)? $datas: $this->data);
        if($this->state !== null){
            if($this->state)
                foreach($this->aDone as $callback) call_user_func_array($callback, $this->data);
            else
                foreach($this->aFail as $callback) call_user_func_array($callback, $this->data);
            foreach($this->aAlways as $callback) call_user_func_array($callback, $this->data);
            $this->aAlways = $this->aFail = $this->aDone = [];
        }
    }

    public function done($callback){
        self::addToList($this->aDone, $callback);
        return $this;
    }

    public function fail($callback){
        self::addToList($this->aFail, $callback);
        return $this;
    }

    public function state(){
        return $this->state;
    }

}