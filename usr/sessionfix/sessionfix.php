<?php
/**
 *
 * lliure WAP
 *
 * @Versão 6.0
 * @Pacote lliure
 * @Entre em contato com o desenvolvedor <lliure@lliure.com.br> http://www.lliure.com.br/
 * @Licença http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

if(!function_exists('is_session_started')){
function is_session_started(){
    if ( php_sapi_name() !== 'cli' ) {
        if ( version_compare(phpversion(), '5.4.0', '>=') )
            return session_status() === PHP_SESSION_ACTIVE;
        else
            return session_id() === '';
    }
    return FALSE;
}}
if (is_session_started() === FALSE) session_start();

final class sessionFix{ static public function script($link){ echo'
<script type="text/javascript">
    (function($){ $(function(){ setInterval(function(){ $.get("'. $link. '"); }, 1000*60*10); });})(jQuery); 
</script>
';}}