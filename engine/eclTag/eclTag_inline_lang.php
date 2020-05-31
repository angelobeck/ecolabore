<?php

class eclTag_inline_lang
{ // class eclTag_inline_lang

static $type = 'scope';

static function render ($render, $arguments)
{ // function render
global $store;

// Vamos encontrar o nome do campo selecionado
if (!isset ($arguments[0]))
$field = $render->getVar ('caption');
elseif (is_string ($arguments[0]))
{ // from constant
@list ($prefix, $sufix) = explode ('_', $arguments[0], 2);
$name = 'labels/' . $prefix . '/' . $sufix;
$data = [];
if (isset ($render->blocks[$name]))
$data = $render->blocks[$name];
if (!isset ($data['text']['caption']))
$data = $store->control->read ($name);
if (!isset ($data['text']['caption']))
return;
$field = $data['text']['caption'];
} // from constant
elseif (is_array ($arguments[0]))
$field = $arguments[0];
else
return;

if (!is_array ($field))
return;

$text = $render->document->textSelectLanguage ($field);
if ($text[3] == $render->document->lang)
return;
$data['lang'] = $text[3];
$data['html'] = ' lang=' . QUOT . $text[3] . QUOT;
return $data;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_inline_lang

?>