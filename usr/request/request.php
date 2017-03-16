<?php

/**
 * UsrRequest
 */
class Request {

    private $headers = '';
    private $url;
    private $query;
    private $curlOpt = [];

    function __construct($url, array $query = []){

        $url = parse_url($url);

        if(isset($url['query'])) parse_str($url['query'], $url['query']);
        if(isset($url['query']) && !empty($url['query']) || !empty($query))
            $url['query'] = array_merge(((isset($url['query']))? $url['query']: []), $query);
        if(isset($url['query'])) array_walk_recursive($url['query'], function(&$v){
            if(is_string($v)) $v = rawurlencode($v);
        });
        if(isset($url['query'])) $url['query'] = rawurldecode(http_build_query($url['query']));

        $url = self::unparse_url($url);

        $this->url = $url;
        $this->query = $query;
    }

    public static function request($url, array $query = []){
        return new self($url, $query);
    }


    public function get(){
        return $this->run('get', []);
    }

    public function post($data){
        return $this->run('post', $data);
    }

    public function delete($data){
        return $this->run('delete', $data);
    }

    public function put($data){
        return $this->run('put', $data);
    }

    public function patch($data){
        return $this->run('patch', $data);
    }

    function __call($name, $arguments){
        return $this->run($name, $arguments);
    }

    public function curlSetOpt(array $opts){
        $this->curlOpt = array_merge($this->curlOpt, $opts);
        return $this;
    }

    public function headers(array $headers){
        $this->headers = $headers;
        return $this;
    }

    private function run($method, $data){

        $dfd = new Deferred();
        $rps = new \Request\Response();
        $method = strtoupper($method);
        $curlOpt = [];

        if(!empty($this->headers))
            $curlOpt = [CURLOPT_HTTPHEADER => $this->headers];

        $curlOpt = ($curlOpt + [
            CURLOPT_URL            => $this->url,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_CUSTOMREQUEST, $method,
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_HEADER         => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS      => 50,
        ]);

        if($method != 'GET') $curlOpt = ($curlOpt + [
            CURLOPT_POST => 1,
            CURLOPT_VERBOSE => true,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);

        $curlOpt = ($this->curlOpt + $curlOpt);

        $ch = curl_init();
        curl_setopt_array($ch, $curlOpt);
        $return = curl_exec($ch);

        $rps->meta($this->url, curl_getinfo($ch, CURLINFO_HTTP_CODE));
        $rps->data($return);

        if($rps->isError())
            $dfd->reject($rps);
        else
            $dfd->resolve($rps);

        return $dfd->promise();
    }

    private static function unparse_url($parsed_url) {
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }


}
