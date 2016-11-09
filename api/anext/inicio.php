<?php

class Anext{

    public function __construct($attr){
        foreach (array_merge( array(
            'name' => ''
        ), $attr) as $k => $v) $this->{$k} = $v;
    }


    public function __toString(){
        $r[]= '<div class="input">';



        $r[]= '</div>';
        return implode('', $r);
    }

}