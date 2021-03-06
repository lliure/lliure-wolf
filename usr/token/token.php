<?php

/**
 * Gerencia a criação e validções de tokens.
 */
final class Token {

    /**
     * Retorna um tokem. em cada chamada a este metdo um tokem diferente baseado
     * no do sistema é chamado.
     * @return string
     */
    final static function get(){
        return Senha::create(($_SESSION['ll']['token'] = ((isset($_SESSION['ll']['token']))? $_SESSION['ll']['token']: self::create())));
    }

    /**
     * Cria uma hash aleatoria
     * @return string
     */
    final public static function create(){
        return (Senha::create(rand(1000, 9999) . ':8080'));
    }

    /**
     * Recebe e valida um token comparando ele com o do sistema.
     * @param string $token Token a ser validados.
     * @return boolean
     */
    final static function valid($token){
        return (isset($_SESSION['ll']['token']) && Senha::valid($_SESSION['ll']['token'], $token));
    }

}
