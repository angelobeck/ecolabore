<?php

class eclTag_up
{ // class eclTag_up

static function render ($render, $arguments)
{ // function render
if (!$arguments)
return false;

$value = implode (' ', $arguments);
$buffer = '<span class="up" data-tag="^' . $value . QUOT . '><span>' . $value . '</span></span>';
$render->buffer .= $buffer;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_up

?>