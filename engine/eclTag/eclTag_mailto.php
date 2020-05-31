<?php

class eclTag_mailto
{ // class eclTag_mailto

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;

$args = explode (' ', $arguments[0], 2);

$buffer = '<a href="mailto:' . $args[0] . QUOT . ' data-tag="mailto:' . $args[0];
if (isset ($args[1]) and strlen ($args[1]))
$buffer .= ' ' . $args[1];
$buffer .= '">';

if (isset ($args[1]) and strlen ($args[1]))
$buffer .= $args[1];
else
$buffer .= $args[0];

$buffer .= '</a>' . CRLF;

// hide
if ($render->document->protectedLayout)
$buffer = '<span class="humperstilshen-shuffled" data-shuffled=' . QUOT . base64_encode ($buffer) . QUOT . '></span>';

$render->buffer .= $buffer;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_mailto

?>