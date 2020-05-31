<?php

class eclTag_paste
{ // class eclTag_paste

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;

// Vamos anotar a posiчуo e o nome desta colagem
$render->pasteIndex++;
$render->pastePointer[$render->pasteIndex] = strlen ($render->buffer);
$render->pasteNames[$render->pasteIndex] = $arguments[0];
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_paste

?>