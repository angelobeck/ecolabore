<?php

class eclTag_http
{ // class eclTag_http

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;

$args = explode (' ', $arguments[0], 2);
if (substr ($args[0], 0, 2) != '//')
$args[0] = '//' . $args[0];

$buffer = '<a href="http:' . $args[0] . QUOT . ' data-tag="http:' . $args[0];
if (isset ($args[1]) and strlen ($args[1]))
$buffer .= ' ' . $args[1];
$buffer .= '">';

if (isset ($args[1]) and strlen ($args[1]))
$buffer .= $args[1];
else
$buffer .= substr ($args[0], 2);
$buffer .= "</a>" . CRLF;
$render->buffer .= $buffer;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_http

?>