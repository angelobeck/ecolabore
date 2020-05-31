<?php

class eclTag_file
{ // class eclTag_file

static $type = 'module';

static function render ($render, $arguments)
{ // function render
global $store;
$document = $render->document;

$tagArguments = implode (' ', $arguments);
$arguments = explode (' ', $tagArguments);
switch (count ($arguments))
{ // switch number of arguments
case 0:
if (!isset ($document->application->data['name']))
return false;

$arguments[0] = $document->application->data['name'];
$arguments[1] = '0';
break;

case 1:
if (is_numeric ($arguments[0]))
{ // number
if (!isset ($document->application->data['name']))
return false;

$arguments[1] = $arguments[0];
$arguments[0] = $document->application->data['name'];
break;
} // number

$arguments[1] = '0';
} // switch number of arguments

// Vamos abrir o módulo
$mod = $document->createModule ('file', $arguments);

// Se o módulo estiver marcado como vazio
if (!$mod->enabled)
return;

$data = $mod->data;
$data['local']['tag'] = 'file:' . $tagArguments;
$data['mod'] = $data['local'];
$data['children'] = $mod->children;
$data['local']['personalite_char'] = 'O';

return $data;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_file

?>