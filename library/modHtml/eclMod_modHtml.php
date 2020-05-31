<?php

class eclMod_modHtml
{ // class eclMod_modHtml

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

// Procure a aplicaзгo
$me = $document->application->findModuleTarget ($document, $arguments);
if (!$me)
return;

$number = $arguments[1];

// Procure o mуdulo dentro da aplicaзгo
if (isset ($me->data['extras']['html_' . $number]))
{ // module exists
$html = $me->data['extras']['html_' . $number];
$mod->data = $html;
$mod->enabled = true;
} // module exists

if ($document->access (4, $me->groups))
{ // personalite
$mod->enabled = true;

$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'html_' . $number);

if (!isset ($html))
{ // empty
$mod->data = $store->control->read ('modHtml_create');
$mod->appendChild ($mod->data)
->url ($pathway)
->popUpOpen ();
return;
} // empty

$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modHtml_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // personalite

if (!isset ($html))
return;

$mod->data['local']['list'] = 'html';
$mod->appendChild ($html);
} // function setModule

} // class eclMod_modHtml

?>