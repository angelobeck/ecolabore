<?php

class eclTag_shared
{ // class eclTag_shared

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;

$render->buffer .= $render->document->urlFiles ($arguments[0], true, '-shared');
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_shared

?>