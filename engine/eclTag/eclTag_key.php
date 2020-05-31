<?php

class eclTag_key
{ // class eclTag_key

static function render ($render, $arguments)
{ // function render
global $store;
if (!isset ($arguments[0]) or !is_string ($arguments[0]) or !strlen ($arguments[0]))
return;

$document = $render->document;
$domain = $document->domain;

$label = $key = $arguments[0];
if ($document->charset != 'ISO-8859-1')
$key = mb_convert_encoding ($key, 'ISO-8859-1', $document->charset);

$key = $render->toKeyword ($key);
if (!strlen ($key))
return;

if ($id = $store->domainContent->findMarker ($document->domain->domainId, 1))
$pathway = $store->domainContent->pathway ($document->domain->domainId, $id);
else
$pathway = array ($domain->name, '-glossary');

$pathway[] = $key;
$url = $document->url ($pathway);

$buffer = '<a href=' . QUOT . $url . QUOT . ' data-tag="key:' . $label . QUOT . '>' . $label . "</a>" . CRLF;
$render->buffer .= $buffer;
} // function render

static function close ($render)
{ // function close

} // function close

} // class eclTag_key

?>