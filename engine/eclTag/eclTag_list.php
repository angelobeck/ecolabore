<?php

class eclTag_list
{ // class eclTag_list

static $type = 'scope';

static function render ($render, $arguments)
{ // function render
global $store;
$document = $render->document;

// Se não existir uma lista
if (!count ($render->children))
return;

if (isset ($arguments[0]) and is_string ($arguments[0]) and isset ($arguments[0][0]))
$name = 'lists/' . $arguments[0];
else
$name = 'lists/default';

// Procuramos o bloco
$data = $render->block ($name);
unset ($data['text'], $data['local']);

// Vamos procurar a lista
if ($render->index)
{ // subitem
if (!isset ($render->children[$render->index - 1]->children))
return;
if (!count ($render->children[$render->index - 1]->children))
return;
$data['children'] = $render->children[$render->index - 1]->children;
$data['index'] = 1;
$data['local'] = $render->children[$render->index - 1]->children[0]->data;
$data['me'] = $render->children[$render->index - 1]->children[0]->me;
if (count ($data['children']) == 1)
$data['local']['last'] = 1;
$data['local']['index'] = 1;
} // subitem
else
{ // list from module level 0
$data['data'] = $render->children[0]->data;
$data['me'] = $render->children[0]->me;
$render->index = 1;
if (count ($render->children) == 1)
$data['local']['last'] = 1;
$data['local']['index'] = 1;
} // list from module level 0

$data['local']['first'] = 1;
return $data;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_list

?>