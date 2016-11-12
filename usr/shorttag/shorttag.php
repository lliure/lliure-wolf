<?php

class ShortTag{

    static final function Explode($cmd, $equalizer = '=', $separator = 's'){

        if(!is_string($cmd)) throw new Exception('O comando não é uma string', 0);

        $c = ((is_string($equalizer))? '\\'. $equalizer: ''). ((is_string($separator))? '\\'. $separator: '');

        if(preg_match(($query = '/(?:\\[((?:(?:\\\'(?:[^\\\'\\\\]|\\\\.)*\\\'|\\"(?:[^"\\\\]|\\\\.)*\\"|(?:[^'.$c.'\\]]*))(?:['.$c.'])?)+)\\])/im'), $cmd, $parts) > 0) {
            $cmr = $parts[1];
            $offset = strlen($parts[0]);
        }else return false;

        $cmr = trim(trim(trim($cmr), '[]'));

        $array  = array();

        if(preg_match_all(($query = '/(\\\'(?:[^\\\'\\\\]|\\\\.)*\\\'|\\"(?:[^\\"\\\\]|\\\\.)*\\"|[^'.$c.']*)(['.$c.'])/m'), $cmr.' ', $parts) > 0)
        foreach($parts[1] as $key => $value){

            $vasia = (strlen($value) <= 0);
            $value = ((isset($value[0]) && ($value[0] == '"' || $value[0] == "'"))? str_replace(array('\\"', '\\\\'), array('"', '\\'), substr($value, 1, -1)): $value);

            switch($parts[2][$key]) {

                case $equalizer:

                    if (!isset($pont)){
                        if(!$vasia){
                            if (!isset($array[$value]))
                                $array[$value] = '';
                            $pont = &$array[$value];
                        }else{
                            $array[] = '';  end($array);
                            $pont = &$array[key($array)];
                        }

                    } else {
                        if(!$vasia){
                            if (!isset($pont[$value]))
                                $pont[$value] = '';
                            $pont = &$pont[$value];
                        }else{
                            $pont[] = '';  end($pont);
                            $pont = &$pont[key($pont)];
                        }
                    }

                    break;

                default:

                    if (!$vasia){
                        if (!isset($pont)){
                            $array[] = $value;

                        }else{
                            $pont = $value;
                            unset($pont);
                        }
                    }elseif(isset($pont)){
                        unset($pont);
                    }

                    break;
            }

        };

        if(isset($array[0]) && preg_match(($query2 = '/^([^\\0]*)?\\[\\\\'. preg_quote($array[0]). '\\]/m'), substr($cmd, $offset), $conts) > 0)
            $array['_content'] = $conts[1];

        return $array;

    }


    static final function Implode(array $array){
        return self::IR($array);
    }

    private static final function IR(array $array, $base = ''){

        $i =       0;
        $b = array();

        foreach($array as $index => $value){

            if(is_string($index)){
                $index = ((strpos($index, ' ') !== false || strpos($index, '"') !== false || strlen($index) == 0)?  '"'. str_replace(array('\\', '"'), array('\\\\', '\\"'), $index). '"' : $index);

            }elseif(is_numeric($index)){
                if(is_array($value)) {
                    $index = (string)$index;

                }elseif ($i == $index){
                    $index = '';
                    $i++;

                }elseif($i < $index){
                    $i = $index;
                    $i++;
                    $index = (string) $index;

                }else{
                    $index = (string) $index;

                }
            }

            if(is_array($value)) {
                $b[] = self::IR($value, $base.$index.'=');

            }else{

                $value = (string) $value;
                $value = ((strpos($value, ' ') !== false || strpos($value, '"') !== false)?  '"'. str_replace(array('\\', '"'), array('\\\\', '\\"'), $value). '"' : $value);
                $b[] = $base. $index. (strlen($base. $index) > 0? '=': ''). $value;

            }

        }

        return implode(' ', $b);

    }

    public static function Search($testo, $equalizer = '=', $separator = 's'){
        if(!is_string($testo)) return false;
        $c = ((is_string($equalizer))? '\\'. $equalizer: ''). ((is_string($separator))? '\\'. $separator: '');
        $r = array();
        $offset = 0;
        while(preg_match(($query = '/(\\\\*)(\\[(?:(?:\\\'(?:[^\\\'\\\\]|\\\\.)*\\\'|\\"(?:[^"\\\\]|\\\\.)*\\"|(?:[^'.$c.'\\]]*))(?:['.$c.'])?)+\\])/im'), substr($testo, $offset), $parts) > 0){
            $dif = strlen($parts[1]);
            $i['shortTag'] = '';
            $i['start'   ] = strpos($testo, $parts[0], $offset) + $dif;
            $i['length'  ] = strlen($parts[2]);
            $i['offset'  ] = $i['start'] + $i['length'];
            $offset = $i['offset'];
            if(!!($dif % 2)) continue;
            $i['shortTag'] = self::Explode($parts[2], $equalizer, $separator);
            if(isset($i['shortTag'][0]) && preg_match(($query2 = '/^[^\\0]*?\\[\\\\'. preg_quote($i['shortTag'][0]). '\\]/m'), ($sub = substr($testo, $offset)), $conts) > 0) {
                $i['shortTag'] = self::Explode($parts[2]. $conts[0], $equalizer, $separator);
                $i['length'  ] = strlen($parts[2] . $conts[0]);
                $i['offset'  ] = $i['start'] + $i['length'];
                $offset = $i['offset'];
            }$r[] = $i;
        }
        return $r;
    }

}