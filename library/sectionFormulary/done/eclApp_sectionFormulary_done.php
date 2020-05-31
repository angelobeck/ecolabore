<?php

class eclApp_sectionFormulary_done
{ // class eclApp_sectionFormulary_done

static function is_child ($me, $name)
{ // function is_child
if ($name == '-done')
return true;

return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-done');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
if ($store->domainContent->open ($me->domainId, '-' . $me->parent->id . '-done'))
{ // custom content
$me->data = &$store->domainContent->open ($me->domainId, '-' . $me->parent->id . '-done');
$me->id = $me->data['id'];
} // custom content
else
$me->data = $store->control->read ('sectionFormulary_done_content');
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$view = $me->id ? 'custom' : 'default';

if ($document->access (4))
{ // admin access

// Restore to default message
if ($view == 'custom' and $document->actions ('message', 'restore'))
$view = self::action_restore ($document);

// Edit custom message
elseif ($view == 'custom' and $document->actions ('message', 'edit'))
$view = self::action_edit ($document);

// Edit default message
elseif ($view == 'default' and $document->actions ('message', 'custom'))
$view = self::action_custom ($document);

// Create a custom message to save Wysiwyg edition
elseif ($view == 'default' and $document->actions ('wysiwyg', 'save'))
$view = self::action_wysiwyg_save ($document);

if ($view == 'default' or $document->actions ('message', 'custom'))
{ // default tools

// Context custom message
$document->mod->context->appendChild ('sectionFormulary_done_custom')
->active ($document->actions ('message', 'custom'))
->url (true, true, '_message-custom');
} // default tools
else
{ // custom tools

// context edit
$document->mod->context->appendChild ('sectionFormulary_done_edit')
->active ($document->actions ('message', 'edit'))
->url (true, true, '_message-edit');

// Versioning
$store->domainExtras->versioning ($document);

// context restore
$document->mod->context->appendChild ('sectionFormulary_done_restore')
->url (true, true, '_message-restore');
} // custom tools

if ($view != 'form')
$document->mod->editor->enable ();
} // admin access
} // function dispatch

static function action_wysiwyg_save ($document)
{ // function action_wysiwyg_save
global $store;
$me = $document->application;

$data = $me->data;
$data['name'] = 'm' . TIME;
$data['mode'] = MODE_QUESTION;
$data['parent_id'] = $me->parent->id;
$me->id = $store->domainContent->insert ($me->domainId, $data);
$me->data = &$store->domainContent->openById ($me->domainId, $me->id);
return 'custom';
} // function action_wysiwyg_save

static function action_custom ($document)
{ // function action_custom
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('sectionFormulary_done_edit', $store->control->read ('sectionFormulary_done_content'));
$formulary->action = '_message-custom';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['message']);
return 'default';
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['message']);
$data = $formulary->data;
$data['name'] = '-' . $me->parent->id . '-done';
$data['mode'] = MODE_QUESTION;
$data['parent_id'] = $me->parent->id;
$me->id = $store->domainContent->insert ($me->domainId, $data);
$me->data = $data;
$store->domainExtras->createVersion ($me->domainId, $me->data, $document);
$document->dataReplace ($me->data);
return 'custom';
} // save

$document->dataReplace ('sectionFormulary_done_custom');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_message-custom';
return 'form';
} // function action_custom

static function action_edit ($document)
{ // function action_edit
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('sectionFormulary_done_edit', $me->data);
$formulary->action = '_message-edit';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['message']);
return 'custom';
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['message']);
$me->data = $formulary->data;
$store->domainExtras->createVersion ($me->domainId, $me->data, $document);
$document->dataReplace ($me->data);
return 'custom';
} // save

$document->dataReplace ('sectionFormulary_done_edit');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_message-edit';
return 'form';
} // function action_edit

static function action_restore ($document)
{ // function action_restore
global $store;
$me = $document->application;

unset ($document->actions['message']);
$store->domainContent->delete ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, $me->data['name']);
$store->domainExtras->deleteAllChildren ($me->domainId, $me->id);

$document->dataReplace ('sectionFormulary_done_content');
$me->id = 0;
return 'default';
} // function action_restore

static function remove ($me)
{ // function remove
global $store;
if ($me->id)
$store->domainContent->delete ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, $me->data['name']);
} // function remove

} // class eclApp_sectionFormulary_done

?>