<?php
/**
 * Description of autoload
 *
 * @author Rodrigo
 */

class autoload{
    
    static private

        /** array com os caminhos que o auto load procura */
        $path = array(),

        /** array com os functions que o auto load executa */
        $functions = array();
    
    public static function setPath($path){
        $DS = DIRECTORY_SEPARATOR;
        if (!empty($path)
        && (is_dir(realpath(dirname(__FILE__) . $DS . '..' . $DS . '..' . $DS . $path))
        && (!in_array($path, self::$path)))){
            self::$path[] = $path;
        }
    }
    
    public static function setFunction($function){
        if (is_callable($function)) {
            self::$functions[] = $function;
        }
    }

    public static function getFile($nome){
        if (($retorno = self::exectFunctions($nome)) !== NULL){
            return $retorno;
        }else{
            throw new Exception('Erro do AutoLoad', 0);
        }
    }
    
    private static function exectFunctions($nome){
        $DS = DIRECTORY_SEPARATOR;
        $r = NULL;
        foreach (self::$functions as $function){
            if (is_file(($r = realpath(dirname(__FILE__) . $DS . '..' . $DS . '..' . $DS . call_user_func_array($function, array($nome, self::$path)))))){
                break;
            }else{
                $r = NULL;
            }
        }
        return $r;
    }

}

function autoload_function_process($nome){
    try {
        require_once autoload::getFile($nome);
    } catch (Exception $exc) {
        //echo $exc->getMessage();
        return NULL;
    }
}

if (function_exists('spl_autoload_register')){
    spl_autoload_register('autoload_function_process');
}else{
    function __autoload($nome){autoload_function_process($nome);}
}

autoload::setPath('api');
autoload::setPath('sys');
function autoload_function_standard($class, $paths){
    $DS = DIRECTORY_SEPARATOR;
    $retorno = NULL;
    foreach ($paths as $path) {
        if (file_exists(($retorno = $path. $DS. $class. $DS. $class. '.php'))
        || (file_exists(($retorno = $path. $DS. $class. '.php')))){
            break;
        }else{
            $retorno = NULL;
        }
    }
    return $retorno;
}
autoload::setFunction('autoload_function_standard');