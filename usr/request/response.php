<?php

namespace Request;

class Response implements \IteratorAggregate {

    private $meta, $data;

    public function meta($endpoint = null, $status = null){
        if($endpoint !== null){
            $this->meta["status"] = $status;
            $this->meta["endpoint"] = $endpoint;
            return $this;
        }else
            return $this->meta;
    }

    public function data($data){
        $this->data = $data;
        return $this;
    }

    public function __get($name){
        return $this->data[$name];
    }

    public function __set($name, $value){
        $this->data[$name] = $value;
    }

    public function __isset($name){
        return isset($this->data[$name]);
    }

    public function __unset($name){
        unset($this->data[$name]);
    }

    function __toString(){
        return (string) $this->data;
    }

    public function getIterator(){
        return new \ArrayIterator($this->data);
    }

    public function isError(){
        return ((!isset($this->meta["status"]))? null: (($this->meta["status"] >= 200 && $this->meta["status"] <= 300)));
    }

    public function response(array $response = null){
        if($response !== null)
            return $this->meta(
                ((isset($response['meta']['status']))? $response['meta']['status']: ''),
                ((isset($response['meta']['endpoint']))? $response['meta']['endpoint']: '')
            )->data((($response['data'])? $response['data']: ''));
        else
            return ['meta' => $this->meta, 'data' => $this->data];
    }

}