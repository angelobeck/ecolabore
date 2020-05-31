<?php

class eclMod_modBanner
{ // class eclMod_modBanner

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;

$me = $document->application;
while (!isset ($me->data['extras']['banner']) and $me->parent->parent)
{ // search parent banner
$me = $me->parent;
} // search parent banner

if ($me->isDomain)
$me = $me->child ('');

if (isset ($me->data['extras']['banner']))
{ // banner found
$mod->data = $me->data['extras']['banner'];
$local['extras']['img_0'] = $me->data['extras']['banner'];
$mod->appendChild ($local);
$mod->enabled = true;
} // banner found

// Se o módulo pode ser editado
if ($document->templateEditable and $document->access (4))
{ // reference
// Crie um módulo
if (!$mod->children)
{ // creates an image
$mod->data = $store->control->read ('modBanner_create');
$mod->appendChild ($store->control->read ('modBanner_create'));
} // creates an image

$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'banner');
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modBanner_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];

$mod->enabled = true;
} // reference
} // function setModule

} // class eclMod_modBanner

?>