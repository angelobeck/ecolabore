<?php

class eclMod_modImg
{ // class eclMod_modImg

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
if (isset ($me->data['extras']['img_' . $number]))
{ // module exists
$img = $me->data['extras']['img_' . $number];
$mod->data = $img;
} // module exists

if ($document->access (4, $me->groups))
{ // personalite
$mod->enabled = true;

$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'img_' . $number);

if (!isset ($img))
{ // creates a new image
$mod->data = $store->control->read ('modImg_create');
$mod->appendChild ('modImg_create')
->url ($pathway)
->popUpOpen ();
return;
} // creates a new image

$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modImg_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // personalite

if (!isset ($img))
return;

$mod->data['local']['list'] = 'details';
$mod->data['local']['details'] = 'img';
$local = $me->data;
$local['extras']['img_0'] = $img;
$mod->appendChild ($local);

$mod->enabled = true;
} // function setModule

} // class eclMod_modImg

?>