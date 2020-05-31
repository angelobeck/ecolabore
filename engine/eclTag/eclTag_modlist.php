<?php

class eclTag_modlist
{ // class eclTag_modlist

static $type = 'scope';

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;

// Que bloco deve ser inserido?
$buffer = '[';
foreach (explode (CRLF, $arguments[0]) as $name)
{ // each name
$buffer .= 'mod`' . $name . '`;';
} // each name

$data['html'] = $buffer;
return $data;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_modlist

?>