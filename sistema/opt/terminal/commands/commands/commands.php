<?php
function commands($args){
    if(! isset($args[0]))
        $args[0] = '';
    
    echo '<span class="color2">********************************************************************************</span><br/><br/>';
    
    $c_dir = dir(TCMD_PATH);
    while($file = $c_dir->read()){
        if(
            @preg_match('/'.$args[0].'[a-zA-z0-9]*/i', $file )
            && $file != '.'    
            && $file != '..'    
        ){
            echo '<div><span class="color2">'.str_replace ('.php', '', $file).'</span>';
            
            $tipFile = TCMD_PATH.'/'.$file.'/tip.txt';
            if(file_exists($tipFile))
                echo '<span> - '.nl2br(file_get_contents ($tipFile)).'</span>';
            
            echo '</div>';
        }
    }
    echo '<br/><span class="color2">********************************************************************************</span><br/>';
    
}
?>
