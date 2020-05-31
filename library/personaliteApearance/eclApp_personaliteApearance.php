<?php

class eclApp_personaliteApearance
{ // class eclApp_personaliteApearance

static function is_child ($me, $name)
{ // function is_child
if ($name == 'apearance')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('apearance');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteApearance_content');

if (!$store->domainExtras->openChild ($me->domainId, MODE_TEMPLATE, 0, 'templates/default'))
{ // create user template
$data = $store->control->read ('templates/default');
$data['mode'] = MODE_TEMPLATE;
$data['name'] = 'templates/default';
$store->domainExtras->insert ($me->domainId, $data);
} // create user template

if (!$store->domainExtras->openChild ($me->domainId, MODE_TEMPLATE, 0, 'themes/user'))
{ // create user theme
$data['mode'] = MODE_TEMPLATE;
$data['name'] = 'themes/user';
$store->domainExtras->insert ($me->domainId, $data);
} // create user theme

$me->map = array ('personaliteApearance_color', 'personaliteApearance_font');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$template = &$store->domainExtras->openChild ($me->domainId, MODE_TEMPLATE, 0, 'templates/user');
$user = &$store->domainExtras->openChild ($me->domainId, MODE_TEMPLATE, 0, 'themes/user');

$preset = $store->control->read ('domainStyles_preset');

$palette = $document->render->block ('palettes/default');
if (isset ($palette['local']))
$preset['local'] = array_replace ($preset['local'], $palette['local']);
$theme = $document->render->block ('themes/default');
if (isset ($theme['local']))
$preset['local'] = array_replace ($preset['local'], $theme['local']);

$data['local'] = $user['local'];
$data['defaults'] = $preset['local'];

// Restore default configurations
if (isset ($document->received['save']) and $document->received['save'] == 'restore')
{ // restore default configurations
$user['local'] = [];
$template['updated'] = TIME;
return $document->dataReplace ('layouts/dialog_close');
} // restore default configurations

$formulary = $document->createFormulary ('personaliteApearance_scheme_edit', $data);

if ($formulary->save ())
{ // save
$user['local'] = $formulary->data['local'];
$template['updated'] = TIME;
$document->dataReplace ('layouts/dialog_close');
return;
} // save

$document->mod->formulary = $formulary;
$document->mod->fontfaces = new eclMod_personaliteApearance_fontfaces ($document);
} // function dispatch

} // class eclApp_personaliteApearance

?>