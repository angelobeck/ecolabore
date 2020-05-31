<?php

class eclApp_modList
{ // class eclApp_modList

static function is_child ($me, $name)
{ // function is_child
if (substr ($name, 0, 8) == 'section_')
return true;
return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('section');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('modList_content');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

@list ($section, $module, $preset, $name) = explode ('_', $document->application->name, 4);
if (!strlen ($name))
return;

$me = $document->application->findChild ($name);
if (!$me)
return;

if (!$document->access (4, $me->groups))
return;

// Restore default configurations
if (isset ($document->received['save']) and $document->received['save'] == 'restore')
{ // restore default configurations
unset ($me->data['extras'][$module]);
return $document->dataReplace ('layouts/dialog_close');
} // restore default configurations

if (isset ($me->data['extras'][$module]))
$data = $me->data['extras'][$module];
elseif ($module == 'post')
$data = $store->control->read ('modList_preset_' . $preset . 'Content');
else
$data = $store->control->read ('modList_preset_' . $preset);

if ($module == 'post')
$formulary = $document->createFormulary ('modList_postEdit', $data);
else
$formulary = $document->createFormulary ('modList_edit', $data);

if ($formulary->save ())
{ // save formulary
if (!isset ($formulary->data['local']['list']))
$formulary->data['local']['list'] = 'default';

$me->data['extras'][$module] = $formulary->data;
$document->dataReplace ('layouts/dialog_close');
return;
} // save formulary

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->dataMerge ('dialog_tabs');
} // function dispatch

} // class eclApp_modList

?>