<?php

class eclMod_modEditor_insert
{ // class eclMod_modEditor_insert

public $mode = false;
public $enabled = false;

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

$control = $store->control->read ('modEditor_insert_module');
$mod->data = $control;

foreach ($control['children'] as $childName)
{ // each child
$child = $store->control->read ($childName);
$item = $mod->appendChild ($child);

if (isset ($child['local']['target']))
{ // open dialog
$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', $child['local']['personalite'], $child['local']['target']);

$item->url ($pathway, true, '_return-tag')
->popUpOpen (400, 300);
} // open dialog
} // each child

$mod->enabled = true;
} // function setModule

} // class eclMod_modEditor_insert

?>