<?php

class eclTag_mod
{ // class eclTag_mod

static $type = 'module';

static function render ($render, $arguments)
{ // function render
global $store;
if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;

$tagArguments = implode (' ', $arguments);
$arguments = explode (' ', $tagArguments);
$name = array_shift ($arguments);
// Vamos abrir o módulo
$mod = $render->document->createModule ($name, $arguments);

// Se o módulo estiver marcado como vazio
if (!$mod->enabled)
return;

// Vamos criar o escopo para o módulo
$data = $mod->data;
if (isset ($data['text']))
$data['local']['text'] = $data['text'];
$data['local']['tag'] = 'mod:' . $tagArguments;
$data['mod'] = $data['local'];
$data['children'] = $mod->children;
$data['local']['personalite_char'] = 'M';

return $data;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_mod

?>