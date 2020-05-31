<?php

class eclMod_modFile
{ // class eclMod_modFile

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
if (isset ($me->data['extras']['file_' . $number]))
{ // module exists
$file = $me->data['extras']['file_' . $number];
$mod->data = $file;
$mod->enabled = true;
} // module exists

if ($document->access (4, $me->groups))
{ // personalite
$mod->enabled = true;
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'file_' . $number);

if (!isset ($file))
{ // create
$mod->data = $store->control->read ('modFile_create');
$mod->appendChild ($mod->data)
->url ($pathway)
->popUpOpen ();
return;
} // create

$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'file_' . $number);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modFile_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // personalite

$mod->data['local']['number'] = $number;
$mod->data['local']['list'] = 'details';
$mod->data['local']['details'] = 'media_file';
if (!isset ($file))
return;
$local['extras'] = array ('file_' . $number => $file);
$local['pathway'] = $me->pathway;
$mod->appendChild ($local);
} // function setModule

} // class eclMod_modFile

?>