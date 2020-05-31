<?php

class eclMod_modEditor_objects
{ // class eclMod_modEditor_objects

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
$render = $document->render;
$me = $document->application;
$base = array_slice ($me->pathway, 1);

if (!$me->domainId or !isset ($me->data['mode']) or !$document->access (4))
return;

switch ($me->data['mode'])
{ // switch mode
case MODE_SECTION:
$modeCaption = $store->control->read ('modEditor_objects_ofSection');
break;

case MODE_POST:
$modeCaption = $store->control->read ('modEditor_objects_ofPost');
break;

default:
$modeCaption = $store->control->read ('modEditor_objects_ofPage');
} // switch mode

$documentObjects = [];
$contentObjects = [];
foreach ($me->data['extras'] as $field => $object)
{ // each object
$parts = explode ('_', $field);
if (count ($parts) < 2)
continue;

list ($target, $number) = $parts;
switch ($target)
{ // switch target
case 'audio':
case 'box':
case 'file':
case 'html':
case 'img':
case 'table':
case 'video':

if ($number == '0')
$documentObjects[$field] = $object;
else
$contentObjects[$field] = $object;
} // switch target
} // each object

ksort ($documentObjects);
ksort ($contentObjects);
$row = $mod->appendChild ();
foreach ($documentObjects as $field => $object)
{ // each document object

$pathway = $base;
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', $field);

$caption = $store->control->read ('modEditor_objects_' . $target);
$local['caption'] = $document->textMerge ($caption['text']['caption'], ' ', $modeCaption['text']['caption']);
$row->appendChild ($local)
->url ($pathway)
->popUpOpen ();
} // each document object

foreach ($contentObjects as $field => $object)
{ // each content object
list ($target, $number) = explode ('_', $field);

$pathway = $base;
array_unshift ($pathway, $document->domain->name, '-personalite', 'extras', $field);
$caption = $store->control->read ('modEditor_objects_' . $target);
$local['caption'] = $document->textMerge ($caption['text']['caption'], ' ' . $number);
$row->appendChild ($local)
->url ($pathway)
->popUpOpen ();
} // each content object

if (!$row->children)
return;

$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modEditor_objects_module'));
$mod->enabled = true;
} // function setModule

} // class eclMod_modEditor_objects

?>