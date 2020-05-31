<?php

class eclApp_modTable
{ // class eclApp_modTable

static function is_child ($me, $name)
{ // function is_child
if ($name == 'table')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('table');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('modTable_content');
$me->ignoreSubfolders = true;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store, $system;

$pathway = $document->pathway;
array_shift ($pathway); // domain
array_shift ($pathway); // -dialog
array_shift ($pathway); // table

$name = array_pop ($pathway);
$parts = explode (CHR_FNS, $name);
if (count ($parts) == 2)
list ($name, $number) = $parts;
else
$number = '0';
$pathway[] = $name;

$me = $document->domain;
foreach ($pathway as $name)
{ // each name
$me = $me->child ($name);
if ($me === false)
return;
} // each name

if (!$document->access (4, $me->groups))
return;

if (isset ($me->data['extras']['table_' . $number]))
$data = $me->data['extras']['table_' . $number];
else
{ // empty
$data = $store->control->read ('modTable_module');
$data['table'] = array (array ('', ''), array ('', ''));
} // empty

$formulary = $document->createFormulary ('modTable_edit', $data);
$formulary->pathway = $document->pathway;

if ($formulary->save ())
{ // save formulary
$me->data['extras']['table_' . $number] = $formulary->data;
$document->dataReplace ('layouts/dialog_close');
return;
} // save formulary

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_modTable

?>