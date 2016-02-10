RewriteEngine on 
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteCond %{SCRIPT_FILENAME} !-d

RewriteRule \.(gif|png|jpg)$ thumbs.php
