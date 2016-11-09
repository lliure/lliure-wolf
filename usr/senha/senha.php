<?php

/**
 * Gerencia a criação e validação de senhas do sistema de forma segura.
 */
class Senha{

	/**
	 * Emcripita uma senha de forma segura e unica.
	 * @param string $password A senha a ser encriptada.
	 * @return string A encripitção feita.
	 */
	static function create($password){
		return ($seed = substr(uniqid(md5(mt_rand().':8080')), 0, 8)). substr(md5($seed. $password), 0, -8);
	}

	/**
	 * Verifica se o <var>$hash</var> corresponde ao <var>$password</var>.
	 * @param string $password Senha não encripitada.
	 * @param string $hash Encripitação gerada pelo metodo senha::create.
	 * @return boolean
	 */
	static function valid($password, $hash){
		return (($seed = substr($hash, 0, 8)). substr(md5($seed. $password), 0, -8)) == $hash;
	}
	
}