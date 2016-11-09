<?php

//fun??o que grava e retorna texto do alert
function ll_alert($texto = null, $tempo = 1){
    if(empty($texto)){
        if(isset($_SESSION['aviso'])){
            $tempo_m = 1;
            if(isset($_SESSION['aviso'][1]))
                $tempo_m = $_SESSION['aviso'][1];
            echo '<script type="text/javascript">(function($){$(function(){ jfAlert("' .$_SESSION['aviso'][0]. '", "'. $tempo_m. '"); });})(jQuery)</script>';
            unset($_SESSION['aviso']);
        }
    } else {
        $_SESSION['aviso'][0] = $texto;
        $_SESSION['aviso'][1] = $tempo;
    }
}