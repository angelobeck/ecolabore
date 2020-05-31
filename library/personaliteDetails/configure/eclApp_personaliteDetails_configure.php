<?php

class eclApp_personaliteDetails_configure
{ // class eclApp_personaliteDetails_configure

static function is_child ($me, $name)
{ // function is_child
return true;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteDetails_configure_edit');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

$me = $document->application;

$saved = &$store->domainExtras->openChild ($me->domainId, MODE_TEMPLATE, 0, 'details/' . $me->name);
if ($saved)
$data = $saved;
elseif ($store->control->read ('details/' . $me->name))
$data = $store->control->read ('details/' . $me->name);
else
$data = [];

// Restore default configurations
if (isset ($document->received['save']) and $document->received['save'] == 'restore')
{ // restore default configurations
if ($saved)
$store->domainExtras->delete ($me->domainId, $saved['id']);

$data = $store->control->read ('details/' . $me->name);

$document->dataReplace ('layouts/dialog_close');

if ($data)
{ // update to default
$caption = $document->textSelectLanguage ($data['text']['caption']);
$identifier = $me->name;
} // update to system default
else
{ // remove
$identifier = '';
$caption[1] = '';
} // remove

$pathway = $me->parent->pathway;
$pathway[] = $me->name;
$url = $document->url ($pathway);

$document->data['script'] = 'window.opener.listManager.update (' . QUOT . $me->name . QUOT . ', ' . QUOT . $identifier . QUOT . ', ' . QUOT . $caption[1] . QUOT . ', ' . QUOT . $url . QUOT . ');';
return;
} // restore default configurations

$data['identifier'] = $me->name;

$formulary = $document->createFormulary ('personaliteDetails_configure_edit', $data);

if ($formulary->save ())
{ // save formulary

$data = $formulary->data;
if (!isset ($data['identifier']) or !preg_match ('/^[a-z][a-z0-9_]*$/', $data['identifier']))
$data['identifier'] = $me->name;

if ($data['identifier'] == $me->name and $saved)
$saved = $formulary->data;
else
{ // create saved modules
if ($data['identifier'] != $me->name and $saved = &$store->domainExtras->openChild ($me->domainId, MODE_TEMPLATE, 0, $data['identifier']))
{ // overwrite existing detail
$saved['text'] = $data['text'];
$saved['local'] = $data['local'];
$saved['html'] = $data['html'];
} // overwrite existing detail
else
{ // create new detail
$data['mode'] = MODE_TEMPLATE;
$data['name'] = 'details/' . $data['identifier'];
$store->domainExtras->insert ($me->domainId, $data);
} // create new detail
} // create saved modules

$document->dataReplace ('layouts/dialog_close');

$caption = $document->textSelectLanguage ($data['text']['caption']);
$pathway = $me->parent->pathway;
$pathway[] = $data['identifier'];
$url = $document->url ($pathway);
$document->data['script'] = 'window.opener.listManager.update (' . QUOT . $me->name . QUOT . ', ' . QUOT . $data['identifier'] . QUOT . ', ' . QUOT . $caption[1] . QUOT . ', ' . QUOT . $url . QUOT . ');';
return;
} // save formulary

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_personaliteDetails_configure

?>