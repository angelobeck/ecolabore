<?php

class eclApp_domain_empty
{ // class eclApp_domain_empty

static function is_child ($me, $name)
{ // function is_child
if ($name == '' or $name == '-default')
return true;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->name = '';
$me->data = $store->control->read ('domain_empty_content');
$me->ignoreSubfolders = true;
unset ($me->pathway[1]);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

if (!$document->access (4))
return;

$register_data = $store->domainContent->open ($me->domainId, '-register');

$formulary = $document->createFormulary ('domain_empty_edit', [], 'domainEmpty');
$formulary->action = '_first-edit-save';

if ($formulary->command ('save') and $formulary->save ())
{ // creates the index page
$data = $formulary->data;
$data['name'] = '-index';
$data['mode'] = MODE_DOMAIN;
$data['parent_id'] = 0;
$store->domainContent->insert ($me->domainId, $data);
$store->domainExtras->createVersion ($me->domainId, $data, $document);

if (!$register_data)
{ // creates register
$register_data = $data;
$register_data['name'] = '-register';
$store->domainContent->insert ($me->domainId, $register_data);
} // creates register
$domain_data = &$store->domain->openById ($me->domainId);
$domain_data['status'] = 2;
unset ($domain_data);
$domain = $me->parent;
$domain->getMap ();
$domain->reset ();
$document->application = $domain->child ('');
$document->application->dispatch ($document);
return;
} // creates the index page

$register_data['text']['caption'] = $me->data['text']['caption'];

if (!$formulary->data)
$formulary->data = $register_data;
$document->dataReplace ('domainIndex_edit');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->context->enabled = false;
$document->mod->toolbox->enabled = false;
return;
} // function dispatch

} // class eclApp_domain_empty

?>