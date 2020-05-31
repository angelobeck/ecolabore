<?php

class eclApp_domainIndex
{ // class eclApp_domainIndex

static function is_child ($me, $name)
{ // function is_child
global $store;
if ( ($name == '' or $name == '-index') and $store->domainContent->openChild ($me->domainId, MODE_DOMAIN, 0, '-index'))
return true;

return false;
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
$me->data = &$store->domainContent->openChild ($me->domainId, MODE_DOMAIN, 0, '-index');
$me->id = $me->data['id'];
unset ($me->pathway[1]);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;
$view = 'page';

if ($document->access (4))
{ // user is admin

// action edit
if ($document->actions ('index', 'edit'))
$view = self::action_edit ($document);

// context edit
$document->mod->context->appendChild ('domainIndex_edit')
->active ($document->actions ('index'))
->url (true, true, '_index-edit');

// Versioning
$store->domainExtras->versioning ($document);

// Context new section
$pathway = $me->pathway;
$pathway[] = '-new-section';
$document->mod->context->appendChild ('section_create_contextNewMain')
->url ($pathway);

if ($view == 'page')
$document->mod->editor->enable ();
} // user is admin
else
$me->data['hits']++;

if ($view == 'page')
{ // view
$document->mod->panel->main = array ('content');

if (isset ($me->data['local']['listSections']))
{ // list sections
$document->mod->panel->main[] = 'listsections';
$document->mod->listsections = new eclMod_modDinamic ($document, '-index');
} // list sections

if (isset ($me->data['local']['listRecents']))
{ // list recents
$document->mod->panel->main[] = 'listrecents';
$document->mod->listrecents = new eclMod_modDinamic ($document, '-recents');
} // list recents
} //  view
} // function dispatch

static function action_edit ($document)
{ // function action_edit
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('domainIndex_edit', $me->data);

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['index']);
return 'page';
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save the index page
$me->data = $formulary->data;
$store->domainExtras->createVersion ($me->domainId, $me->data, $document);
$document->dataReplace ($me->data);
unset ($document->actions['index']);
return 'page';
} // save the index page

$formulary->action = '_index-edit';
$document->dataReplace ('domainIndex_edit');
$document->mod->context->help ('domainIndex_edit');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->languages->action = '_index-edit';
$document->dataMerge ('domainIndex_contentEdit');
return 'form';
} // function action_edit

static function remove ($me)
{ // function remove
global $store;
$store->domainContent->delete ($me->domainId, $me->id);
} // function remove

} // class eclApp_domainIndex

?>