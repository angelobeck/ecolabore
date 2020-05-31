<?php

class eclApp_domainDefault
{ // class eclApp_domainDefault

const name = '-default';
const menuType = 'hidden';

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
if ($store->domainContent->openChild ($me->domainId, MODE_DOMAIN, 0, '-default'))
{ // custom content
$me->data = &$store->domainContent->open ($me->domainId, '-default');
$me->id = $me->data['id'];
} // custom content
else
$me->data = $store->control->read ('domainDefault_content');
$me->ignoreSubfolders = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

if ($document->access (4))
{ // user is admin
if ($document->application->id)
$view = 'custom';
else
$view = 'default';

// action restore
if ($document->actions ('default', 'restore') and $view == 'custom')
$view = self::action_restore ($document);

// action edit
elseif ($document->actions ('default', 'edit') and $view == 'custom')
$view = self::action_edit ($document);

// action custom
if ($document->actions ('default', 'edit') and $view == 'default')
$document->actions['default'] = array ('default', 'custom');
if ( ($document->actions ('default', 'custom')) and $view == 'default')
$view = self::action_custom ($document);

// context custom
if ($view == 'default' or $document->actions ('default', 'custom'))
$document->mod->context->appendChild ('domainDefault_custom')
->active ($document->actions ('default', 'custom'))
->url (true, true, '_default-custom');

else
{ // custom tools
// context edit
$document->mod->context->appendChild ('domainDefault_edit')
->active ($document->actions ('default', 'edit'))
->url (true, true, '_default-edit');

// Versioning
$store->domainExtras->versioning ($document);

// context restore
$document->mod->context->appendChild ('domainDefault_restore')
->url (true, true, '_default-restore');

$document->mod->editor->enable ();
} // custom tools
} // user is admin
} // function dispatch

static function action_custom ($document)
{ // function action_custom
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('domainDefault_edit', $store->control->read ('domainDefault_content'));

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['default']);
return 'default';
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
$data['name'] = '-default';
$data['mode'] = MODE_DOMAIN;
$data['parent_id'] = 0;
$me->id = $store->domainContent->insert ($document->application->domainId, $data);
$me->data = $data;
$store->domainExtras->createVersion ($me->domainId, $data, $document);
$document->dataReplace ($me->data);
unset ($document->actions['default']);
return 'custom';
} // save
$formulary->action = '_default-custom';
$document->dataReplace ('domainDefault_custom');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_default-custom';
return 'form';
} // function action_custom

static function action_edit ($document)
{ // function action_edit
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('domainDefault_edit', $me->data);

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['default']);
return 'custom';
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
$me->data = $data;
$store->domainExtras->createVersion ($me->domainId, $data, $document);
$document->dataReplace ($data);
unset ($document->actions['default']);
return 'custom';
} // save
$formulary->action = '_default-edit';
$document->dataReplace ('domainDefault_edit');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_default-edit';
return 'form';
} // function action_edit

static function action_restore ($document)
{ // function action_restore
global $store;
$me = $document->application;
unset ($me->data);
$store->domainContent->delete ($me->domainId, $me->id);
$store->domainExtras->deleteAllChildren ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, '-default');
$me->data = $store->control->read ('domainDefault_content');
$document->dataReplace ($me->data);
$me->id = 0;
return 'default';
} // function action_restore

static function remove ($me)
{ // function remove
global $store;
if ($me->id)
$store->domainContent->delete ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, '-default');
} // function remove

} // class eclApp_domainDefault

?>