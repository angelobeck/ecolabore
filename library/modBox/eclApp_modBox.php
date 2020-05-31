<?php

class eclApp_modBox
{ // class eclApp_modBox

static function is_child ($me, $name)
{ // function is_child
if ($name == 'box')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('box');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('modBox_content');
$me->ignoreSubfolders = true;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store, $system;

$pathway = $document->pathway;
array_shift ($pathway); // domain
array_shift ($pathway); // -dialog
array_shift ($pathway); // box

if (!isset ($document->actions['create']))
{ // edit
$name = array_pop ($pathway);
$parts = explode (CHR_FNS, $name);
if (count ($parts) == 2)
list ($name, $number) = $parts;
else
$number = '0';
$pathway[] = $name;
} // edit

$me = $document->domain;
foreach ($pathway as $name)
{ // each name
$me = $me->child ($name);
if ($me === false)
return;
} // each name

if (!$document->access (4, $me->groups))
return;

if (!isset ($number))
{ // find last index
$newBox = true;
for ($number = 0; $number < 100; $number++)
{ // find number
if (!isset ($me->data['extras']['box_' . strval ($number)]))
break;
} // find number
$document->pathway[count ($document->pathway) - 1] .= CHR_FNS . strval ($number);
} // find last index

if (isset ($me->data['extras']['box_' . strval ($number)]))
$data = $me->data['extras']['box_' . strval ($number)];
else
{ // empty
$data = $store->control->read ('modBox_module');
} // empty

$formulary = $document->createFormulary ('modBox_edit', $data);
$formulary->pathway = $document->pathway;
if (isset ($document->actions['create']))
$formulary->action = '_return-tag';

if ($formulary->save ())
{ // save box
$me->data['extras']['box_' . strval ($number)] = $formulary->data;
$document->dataReplace ('layouts/dialog_close');

if ($document->actions ('return', 'tag'))
$document->data['module'] = 'box:' . $number;
return;
} // save box

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_modBox

?>