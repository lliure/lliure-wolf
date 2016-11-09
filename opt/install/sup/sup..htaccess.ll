RewriteEngine on 
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteCond %{SCRIPT_FILENAME} !-d

RewriteRule \.([Gg][Ii][Ff]|[Pp][Nn][Gg]|[Jj][Pp][Gg])$ thumbs.php