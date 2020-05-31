<?php

class eclApp_domainGlossary_keyword
{ // class eclApp_domainGlossary_keyword

static function is_child ($me, $name)
{ // function is_child
return true;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
if (substr ($me->name, 0, 5) == '-key-')
$me->name = substr ($me->name, 5);
$data = &$store->domainContent->open ($me->domainId, '-key-' . strtolower ($me->name));
if ($data)
{ // existing content
$me->data = &$data;
$me->id = $data['id'];
} // existing content
else
$me->data = $store->control->read ('domainGlossary_keyword_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

if (!$me->id and $document->access (4))
self::action_create ($document);

if (!$me->id)
return;

$view = true;
if ($document->access (4))
{ // admin access

if ($document->actions ('entry', 'remove'))
return self::action_remove ($document);

if ($document->actions ('entry', 'edit'))
$view = self::action_edit ($document);

// Context edit
$document->mod->context->appendChild ('domainGlossary_keyword_edit')
->active ($document->actions ('entry', 'edit'))
->url (true, true, '_entry-edit');

// Versioning
$store->domainExtras->versioning ($document);

// context remove
$document->mod->context->appendChild ('domainGlossary_keyword_remove')
->url (true, true, '_entry-remove')
->confirm ('domainGlossary_keyword_removeConfirm');

if ($view)
$document->mod->editor->enable ();
} // admin access

else
$me->data['hits']++;

if ($view)
$document->mod->list = new eclMod_domainGlossary_keyword_list ($document);
} // function dispatch

static function action_create ($document)
{ // function action_create
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('domainGlossary_keyword_edit');
$formulary->action = '_entry-create';

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
$data['mode'] = MODE_KEYWORD;
$data['parent_id'] = 0;
$data['marker'] = 1;
$data['name'] = '-key-' . $me->name;
$me->id = $store->domainContent->insert ($me->domainId, $data);
$me->data = $data;
$store->domainExtras->createVersion ($me->domainId, $me->data, $document);
$document->dataReplace ($data);
return true;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
return false;
} // function action_create

static function action_edit ($document)
{ // function action_edit
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('domainGlossary_keyword_edit', $me->data, 'entry');
$formulary->action = '_entry-edit';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['entry']);
return true;
} // cancel
if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['entry']);
$me->data = $formulary->data;
$store->domainExtras->createVersion ($me->domainId, $me->data, $document);
$document->dataReplace ($me->data);
return true;
} // save

$document->mod->formulary = $formulary;
$document->dataReplace ('domainGlossary_keyword_edit');
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
return false;
} // function action_edit

static function action_remove ($document)
{ // function action_remove
global $store;
$me = $document->application;

$store->domainFile->deletePrefixedFiles ($me->domainId, $me->data['name']);
$store->domainExtras->deleteAllChildren ($me->domainId, $me->id);
$store->domainContent->delete ($me->domainId, $me->id);
$me->data = $store->control->read ('domainGlossary_keyword_content');
$document->dataReplace ($me->data);
self::action_create ($document);
} // function action_remove

} // class eclApp_domainGlossary_keyword

?>