<?php

class eclApp_personaliteFields_config
{ // class eclApp_personaliteFields_config

static function is_child ($me, $name)
{ // function is_child
return true;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('dialogFields_' . $me->name . '_content');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$name = $document->application->name;
$me = $document->application->parent->parent;

list ($filter) = explode ('_', $name, 2);

if (isset ($me->data['extras']['formulary'][$name]))
$data = &$me->data['extras']['formulary'][$name];
elseif ($filter == $name)
$data = $store->control->read ('personaliteFields_' . $filter . '_preset');
else
$data = [];

$formulary = $document->createFormulary ('personaliteFields_' . $filter . '_edit', $data, 'field');

if ($formulary->command ('cancel'))
{ // cancel
$document->dataReplace ('layouts/dialog_cancel');
return;
} // cancel

if ($formulary->command ('save') and $formulary->save () and $formulary->data)
{ // save
switch ($filter)
{ // switch filter
case 'address':
case 'created':
case 'mail':
case 'phone':

$document->dataReplace ('layouts/dialog_close');
$url = $document->url ($document->pathway);
$caption = $document->textSelectLanguage ($formulary->data['caption']);

if (!isset ($me->data['extras']['formulary']))
$me->data['extras']['formulary'] = [];

$me->data['extras']['formulary'][$filter] = $formulary->data;
$document->data['script'] = 'window.opener.listManager.update (' . QUOT . $name . QUOT . ', ' . QUOT . $name . QUOT . ', ' . QUOT . $caption[1] . QUOT . ', ' . QUOT . $url . QUOT . ');';
return;
} // switch filter

if ($filter != $name)
{ // update
$data = $formulary->data;

$document->dataReplace ('layouts/dialog_close');

$url = $document->url ($document->pathway);
$caption = $document->textSelectLanguage ($formulary->data['caption']);
$document->data['script'] = 'window.opener.listManager.update (' . QUOT . $name . QUOT . ', ' . QUOT . $name . QUOT . ', ' . QUOT . $caption[1] . QUOT . ', ' . QUOT . $url . QUOT . ');';
return;
} // update

if (!isset ($me->data['extras']['formulary']))
$me->data['extras']['formulary'] = [];

$index = 1;
while (isset ($me->data['extras']['formulary'][$name . '_' . $index]))
$index++;

$me->data['extras']['formulary'][$name . '_' . $index] = $formulary->data;

$document->dataReplace ('layouts/dialog_close');

$pathway = $document->application->parent->pathway;
$pathway[] = $name . '_' . $index;
$url = $document->url ($pathway);
$caption = $document->textSelectLanguage ($formulary->data['caption']);
$document->data['script'] = 'window.opener.listManager.append (' . QUOT . $name . '_' . $index . QUOT . ', ' . QUOT . $caption[1] . QUOT . ', ' . QUOT . $url . QUOT . ');';
return;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->dataReplace ('personaliteFields_' . $filter . '_content');
} // function dispatch

} // class eclApp_personaliteFields_config

?>