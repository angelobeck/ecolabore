<?php

class eclApp_financialAccount_create
{ // class eclApp_financialAccount_create

static function is_child ($me, $name)
{ // function is_child
if ($name == '-new-account')
return true;
return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
global $store;
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-new-account');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('financialAccount_create_content');
$me->ignoreSubfolders = true;
$me->access = 4;
$me->getMap ();
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application;

if (!isset ($document->actions['create'][1]))
goto show_list;

$type = $document->actions['create'][1];
if (!is_object ($document->application->child ($type)))
goto show_list;

$preset = $document->application->child ($type)->child ('-preset');
if (!is_object ($preset))
goto show_list;

$data = $preset->data;
unset ($data['text']);

$formulary = $document->createFormulary ('financialAccount_create_edit', $data, 'accountEdit');

if ($formulary->command ('cancel'))
{ // return to parent page
$parent = $document->application->parent;
if ($parent->isDomain)
$parent = $parent->child ('');
$document->application = $parent;
$document->application->dispatch ($document);
$document->reload = $document->url ();
return;
} // return to parent page

// Salvar formulário e redespachar
if ($formulary->command ('save') and $formulary->save (1))
{ // save account
$parent = $document->application->parent;
$data = &$formulary->data;
$data['mode'] = MODE_ACCOUNT;
$data['parent_id'] = 0;
$data['id'] = $store->domainContent->insert ($parent->domainId, $data);
$me->id = $data['id'];
$formulary->save (2);
$io->database->commit ();
$me->data = &$store->domainContent->openById ($me->domainId, $me->id);
$me->data = $formulary->data;

$parent->reset ();
$document->application = $parent->child ($data['name']);
$document->reload = $document->url ();
$document->application->dispatch ($document);
return;
} // save account

// Sugestão de texto
if (!isset ($formulary->data['text']) and isset ($preset->data['text']))
$formulary->data['text'] = $preset->data['text'];

// Exibir formulário
$formulary->action = '_create-' . $type;
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->languages->action = '_create-' . $type;
$document->mod->instructor->addMessage ('financialAccount' . ucfirst ($type) . '_helpCreate');
return;

// Exibir lista de opções
show_list:
unset ($document->actions['create']);
$document->mod->list = new eclMod_financialAccount_create_list ($document);
} // function dispatch

} // class eclApp_financialAccount_create

?>