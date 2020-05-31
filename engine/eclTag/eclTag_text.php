<?php

class eclTag_text
{ // class eclTag_text

static function render ($render, $arguments)
{ // function render
global $store;

// Vamos encontrar o nome do campo selecionado
if (!isset ($arguments[0]))
$field = $render->getVar ('caption');
elseif (is_array ($arguments[0]))
$field = $arguments[0];
elseif (is_string ($arguments[0]) and !strlen ($arguments[0]))
return;
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
else
return;

if (is_array ($field))
$text = $render->document->textSelectLanguage ($field);
else
$text = [];

if (isset ($arguments[1]) and $arguments[1])
{ // editable
if (!isset ($text[TEXT_CONTENT]))
$text[TEXT_CONTENT] = CRLF;
if (!isset ($text[TEXT_FORMAT]))
$text[TEXT_FORMAT] = 1;
if (!isset ($text[TEXT_ECOLABORE]))
$text[TEXT_ECOLABORE] = 1;
$text[TEXT_EDITABLE] = 1;
} // editable

if (!$text)
return;

$render->render ($text);
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_text

?>