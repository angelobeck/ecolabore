<?php

class eclApp_personaliteTemplate
{ // class eclApp_personaliteTemplate

static function is_child ($me, $name)
{ // function is_child
if ($name == 'template')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('template');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteTemplate_content');
$me->map = array ('personaliteTemplate_add', 'personaliteTemplate_labels', 'personaliteTemplate_filters');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

if (isset ($document->actions['remove'][1]))
return self::action_remove ($document);

// Restore default configurations
if (isset ($document->received['save']) and $document->received['save'] == 'restore')
{ // restore default configurations
$domainId = $document->domain->domainId;
foreach ($store->domainExtras->children ($domainId, MODE_TEMPLATE, 0) as $data)
{ // each block
$store->domainExtras->delete ($domainId, $data['id']);
} // each block
return $document->dataReplace ('layouts/dialog_close');
} // restore default configurations

$formulary = $document->createFormulary ('personaliteTemplate_edit', [], 'template');

if ($formulary->save ())
return $document->dataReplace ('layouts/dialog_close');

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

static function action_remove ($document)
{ // function action_remove
$id = intval ($document->actions['remove'][1]);
if (!$id)
return $document->buffer = 'error';

$data = $store->domainExtras->openById ($document->domain->domainId, $id);

if (!$data)
return $document->buffer = 'ok';

if ($data['mode'] != MODE_TEMPLATE)
return $document->buffer = 'error';

$store->domainExtras->delete ($data['domain_id'], $data['id']);

$document->buffer = 'ok';
} // function action_remove

} // class eclApp_personaliteTemplate

?>