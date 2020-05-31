<?php

class eclMod_modBox
{ // class eclMod_modBox

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store;
$document = $this->document;
$render = $document->render;

$me = $document->application->findModuleTarget ($document, $arguments);
if (!$me)
return;

$number = $arguments[1];

// Procure o mуdulo dentro da aplicaзгo
if (isset ($me->data['extras']['box_' . $number]))
{ // module exists
$box = $me->data['extras']['box_' . $number];
$mod->data = array_replace_recursive ($render->block ('modules/box'), $box);
} // module exists

if ($document->access (4, $me->groups))
{ // personalite
$mod->enabled = true;

if (!isset ($box))
{ // empty
if (!$document->contentEditable)
$mod->data = $store->control->read ('modBox_create');
else
$box = [];
} // empty

if ($document->contentEditable)
{ // editable
$mod->data['local']['editable'] = 1;
$mod->data['local']['id'] = $me->id;
$mod->data['local']['number'] = $number;
} // editable

$pathway = array_slice ($me->pathway, 1);
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', 'box_' . $number);
$mod->data['local']['personalite_url'] = $document->url ($pathway);
$caption = $store->control->read ('modBox_edit');
$mod->data['local']['personalite_caption'] = $caption['text']['caption'];
} // personalite

if (!isset ($box))
return;

$mod->data['local']['list'] = 'box';
$mod->appendChild ($box);

$mod->enabled = true;
} // function setModule

} // class eclMod_modBox

?>