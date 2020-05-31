<?php

class eclTag_gender
{ // class eclTag_gender

static function render ($render, $arguments)
{ // function render
if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;

@list ($male, $female) = explode ('|', $arguments[0], 2);
if (!isset ($render->document->user->data['local']['gender']))
$render->buffer .= $male;
elseif ($render->document->user->data['local']['gender'] == 'female')
$render->buffer .= $female;
else
$render->buffer .= $male;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_gender

?>