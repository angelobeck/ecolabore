<?php

class eclTag_field
{ // class eclTag_field

static $type = 'scope';

static function render ($render, $arguments)
{ // function render
global $store;

$name = 'fields/' . $render->getVar ('type');

$data = $render->block ($name);

if (!isset ($data['html']))
return;

unset ($data['text']);
if (isset ($data['local']))
$data['local'] = array_replace ($render->data, $data['local']);
else
$data['local'] = $render->data;
if ($render->data)
$data['data'] = $render->data;
return $data;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_field

?>