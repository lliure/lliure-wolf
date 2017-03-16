<?php

/**
 * Class Vigile
 *
 *
 *

Vigile::alert('teste main');
Vigile::success('teste 2 main');
Vigile::info('teste 3 main');
Vigile::warning('teste 4 main');
Vigile::danger('teste 5 main');


$vigile = new Vigile('top');
$vigile->alert('teste');
$vigile->success('teste 2');
$vigile->info('teste 3');
$vigile->warning('teste 4');
$vigile->danger('teste 5');

Vigile::popup('top');


 *
 *
 */


class Vigile implements Iterator{

    private $key, $value;
    protected $location, $posts = [];
    static protected $local = [], $load = false, $types = ['alert', 'success', 'info', 'warning', 'danger'];



    public function __construct($location){
        $this->location = $location;
        if(!isset($_SESSION['ll']['vigile']['locations'][$location])) $_SESSION['ll']['vigile']['locations'][$location] = [];
        $this->posts =& $_SESSION['ll']['vigile']['locations'][$location];
        self::$local[$location] =& $this;
    }

    public function __call($name, $argus){
        $argus[1] = $this->location; self::__callStatic($name, $argus);
    }

    public static function __callStatic($name, $argus){
        self::_alert(((in_array($name, self::$types))? $name: self::$types[0]), ((isset($argus[0]))? $argus[0]: ''), ((isset($argus[1]))? $argus[1]: [1, 0]));
    }
    
    protected static function _alert($type, $msg, $local = [1, 0]){
        if(is_string($local)) self::$local[$local]->posts[] = [
            'type' => $type,
            'msg' => $msg,
        ];
        elseif(is_array($local)) $_SESSION['ll']['vigile']['main'][] = [
            'local' => $local,
            'type' => $type,
            'msg' => $msg,
        ];
    }



    function rewind(){}

    function next(){}

    function valid() {
        if(!is_array($this->posts) || empty($this->posts)){
            $this->value = null;
            $this->key = null;
            return false; }
        foreach($this->posts as $this->key => $this->value) break;
        unset($this->posts[$this->key]);
        return true;
    }

    function current(){
        return $this->value;
    }

    function key(){
        return $this->key;
    }



    public static function popup($location){?>
        <div id="<?php echo $location; ?>">
            <?php if(isset($_SESSION['ll']['vigile']['locations'][$location])) foreach($_SESSION['ll']['vigile']['locations'][$location] as $k => $alert){
                self::popupContent($alert);
            } unset($_SESSION['ll']['vigile']['locations'][$location]); ?>
        </div>
    <?php }

    protected static function popupContent($alert){ ?>
        <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo ((is_array($alert['msg']))? ((isset($alert['msg']['msg']))? $alert['msg']['msg']: ''): $alert['msg']); ?>
        </div>
    <?php }


    public static function callout($location){ ?>
        <div id="<?php echo $location; ?>">
            <?php if(isset($_SESSION['ll']['vigile']['locations'][$location])) foreach($_SESSION['ll']['vigile']['locations'][$location] as $k => $alert){
                self::calloutContent($alert);
            } unset($_SESSION['ll']['vigile']['locations'][$location]); ?>
        </div>
    <?php }

    protected static function calloutContent($alert){ ?>
        <div class="callout callout-fade callout-<?php echo $alert['type']; ?>">
            <button type="button" class="close" data-dismiss="callout" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo ((is_array($alert['msg']))? ((isset($alert['msg']['msg']))? $alert['msg']['msg']: ''): $alert['msg']); ?>
        </div>
    <?php }



    public static function script(){
        if(isset($_SESSION['ll']['vigile']) && !empty($_SESSION['ll']['vigile'])){ ?>
        <script type="text/javascript">
            (function($){
                $(function(){<?php if(isset($_SESSION['ll']['vigile']['main'])) foreach($_SESSION['ll']['vigile']['main'] as $k => $v){ ?>

                    Vigile().<?php echo $v['type']; ?>('<?php echo $v['msg'] ?>', <?php echo json_encode($v['local']); ?>);
                <?php }unset($_SESSION['ll']['vigile']['main']);
                /* foreach($_SESSION['ll']['vigile']['locations'] as $k => $v){ ?>

                    $('#<?php echo $k; ?>').vigile()<?php foreach($v as $msg){?>.<?php echo $msg['type']; ?>('<?php echo $msg['msg'] ?>')<?php } unset($_SESSION['ll']['vigile']['locations'][$k]);} ?>; */ ?>
                });
            })(jQuery);
        </script>
    <?php }}

}


