<?php

class eclApp_modTable_new
{ // class eclApp_modTable_new

static function is_child ($me, $name)
{ // function is_child
if ($name == 'table_create')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('table_create');
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

$formulary = $document->createFormulary ('modTable_new_edit', [], 'create');
$formulary->pathway = $document->pathway;
$formulary->action = '_create';

if ($formulary->command ('cancel'))
{ // cancel
$document->dataReplace ('layouts/dialog_close');
return;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save

$alpha = array ('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$table = [];
for ($rows = 0; $rows < $formulary->data['rows']; $rows++)
{ // each row
$table[$rows] = [];
for ($cols = 0; $cols < $formulary->data['cols']; $cols++)
{ // each column
$table[$rows][$cols] = '';
} // each column
} // each row
unset ($formulary->data['rows']);
unset ($formulary->data['cols']);
$formulary->data['table'] = $table;
$me->data['extras']['table_' . $number] = $formulary->data;

$document->dataReplace ('layouts/dialog_close');
$document->data['module'] = 'table:' . $number;
return;
} // save

$document->dataReplace ('modTable_new_content');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_modTable_new

?>