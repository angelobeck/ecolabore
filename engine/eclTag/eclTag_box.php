<?php

class eclTag_box
{ // class eclTag_box

static $type = 'module';

static function render ($render, $arguments)
{ // function render
global $store;
$document = $render->document;

$tagArguments = implode (' ', $arguments);
$arguments = explode (' ', $tagArguments);

// Vamos abrir o módulo
$mod = $document->createModule ('box', $arguments);

// Se o módulo estiver marcado como vazio
if (!$mod->enabled)
return;

$data = $mod->data;
$data['local']['tag'] = 'box:' . $tagArguments;
$data['mod'] = $data['local'];
$data['children'] = $mod->children;
$data['local']['personalite_char'] = 'B';

return $data;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_box

?>