<?php

class eclApp_integrationGoogleWebmasters
{ // class eclApp_integrationGoogleWebmasters

static function is_child ($me, $name)
{ // function is_child
if ($name == 'google-webmasters')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('google-webmasters');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('integrationGoogleWebmasters_content');
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application;

$saved = &$store->domainContent->open ($me->domainId, '-google-webmasters');

if ($io->request->uploaded)
{ // save
$fields = reset ($io->request->uploaded);
$file = reset ($fields);
if (!$file['size'] or $file['size'] > 2048)
return;

$data['local']['file_name'] = $file['name'];
$data['local']['file_content'] = file_get_contents ($file['tmp_name']);

if ($saved)
{ // saved
$saved['local'] = $data['local'];
} // saved
else
{ // create
$data['mode'] = MODE_CONFIG;
$data['name'] = '-google-webmasters';
$store->domainContent->insert ($me->domainId, $data);
$saved = $data;
} // create
} // save

if ($saved)
$formulary = $document->createFormulary ('integrationGoogleWebmasters_view', $saved);
else
$formulary = $document->createFormulary ('integrationGoogleWebmasters_create', [], 'new');

if ($saved and $formulary->command ('remove'))
{ // remove
$store->domainContent->delete ($me->domainId, $saved['id']);
$formulary = $document->createFormulary ('integrationGoogleWebmasters_create', [], 'new');
} // remove

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_integrationGoogleWebmasters

?>