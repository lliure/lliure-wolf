<?php

use Deferred\Promise;

class Deferred extends Deferred\Promise {

    private $promise;

    function __construct(){
        $this->promise = new Promise();
    }

    public function always($callback){
        return $this->promise->always($callback);
    }

    public function done($callback){
        return $this->promise->done($callback);
    }

    public function fail($callback){
        return $this->promise->fail($callback);
    }

    public function state(){
        return $this->promise->state();
    }

    public function reject(){
        return $this->fulfill(false, func_get_args());
    }

    public function resolve(){
        return $this->fulfill(true, func_get_args());
    }

    private function fulfill($as, $datas){
        if($this->promise->state() === null)
            $this->promise->run($as, $datas);
        return $this;
    }

    public function promise(){
        return $this->promise;
    }

}