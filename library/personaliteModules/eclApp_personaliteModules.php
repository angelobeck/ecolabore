<?php

class eclApp_personaliteModules
{ // class eclApp_personaliteModules

static function is_child ($me, $name)
{ // function is_child
if ($name == 'modules')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('modules');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteModules_edit');
$me->access = 4;
$me->isDomain = true;
$me->ignoreSubfolders = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

$me = $document->application;
$me->pathway = $document->pathway;
$name = end ($document->pathway);

list ($prefix, $sufix) = explode ('_', $name, 2);
if ($prefix == 'item')
$name = 'card_' . $sufix;

$controlName = 'mod' . ucfirst ($prefix) . '_edit';
$control = $store->control->read ($controlName);
if ($control)
{ // set modules title
if (!isset ($control['text']['title']) and isset ($control['text']['caption']))
$control['text']['title'] = $control['text']['caption'];
$me->data['text']['title'] = $control['text']['title'];
} // set modules title
else
{ // no configurable
$document->dataMerge ('personaliteModules_contentNotConfigurable');

$formulary = $document->createFormulary ('personaliteModules_contentNotConfigurable', [], 'not_configurable');
if ($formulary->command ('cancel'))
return $document->dataReplace ('layouts/dialog_cancel');

$document->mod->formulary = $formulary;
return;
} // no configurable

$saved = &$store->domainExtras->openChild ($me->domainId, MODE_TEMPLATE, 0, 'modules/' . $name);
if ($saved)
$data = $saved;
elseif ($store->control->read ('modules/' . $name))
$data = $store->control->read ('modules/' . $name);
else
$data = $store->control->read ('modules/' . $prefix);

// Restore default configurations
if (isset ($document->received['save']) and $document->received['save'] == 'restore')
{ // restore default configurations
if ($saved)
$store->domainExtras->delete ($me->domainId, $saved['id']);
return $document->dataReplace ('layouts/dialog_close');
} // restore default configurations

$formulary = $document->createFormulary ($controlName, $data);

if ($formulary->save ())
{ // save formulary
if ($saved)
$saved = $formulary->data;
else
{ // create saved modules
$data = $formulary->data;
$data['mode'] = MODE_TEMPLATE;
$data['name'] = 'modules/' . $name;
$store->domainExtras->insert ($me->domainId, $data);
} // create saved modules

$document->dataReplace ('layouts/dialog_close');
return;
} // save formulary

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_personaliteModules

?>