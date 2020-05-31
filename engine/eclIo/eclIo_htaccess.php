<?php

class eclIo_htaccess
{ // class eclIo_htaccess

public function regenerate ()
{ // function regenerate
$string = '#!Ecolabore Engine (c)2017 - Copyleft by Angelo Beck' . CRLF
 . '# Do not change these lines. Changes will be made from system.' . CRLF
 . CRLF
 . '<IfModule mod_rewrite.c>' . CRLF
 . 'RewriteEngine On' . CRLF
 . CRLF
 . '# Forbiden invalid requests' . CRLF
 . 'RewriteRule [@%<>&] - [F]' . CRLF
 . CRLF
 . '# redirect all requests for the index' . CRLF
 . 'RewriteRule .* ' . SYSTEM_SCRIPT_NAME . '?RewriteEngine=On [L]' . CRLF
 . CRLF
 . '</IfModule>' . CRLF
 . '#!End of Ecolabore Engine settings' . CRLF;

file_put_contents ('.htaccess', $string);
} // function regenerate

public function close ()
{ // function close
} // function close

} // class eclIo_htaccess

?>