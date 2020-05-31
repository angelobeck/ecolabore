<?php

class eclTag_html
{ // class eclTag_html

static $type = 'module';

static function render ($render, $arguments)
{ // function render
global $store;
$document = $render->document;

$tagArguments = implode (' ', $arguments);
$arguments = explode (' ', $tagArguments);

// Vamos abrir o módulo
$mod = $document->createModule ('html', $arguments);

// Se o módulo estiver marcado como vazio
if (!$mod->enabled)
return;

$data = $mod->data;
$data['local']['tag'] = 'html:' . $tagArguments;
$data['mod'] = $data['local'];
$data['children'] = $mod->children;
$data['local']['personalite_char'] = 'H';

return $data;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_html

?>