<?php

class eclApp_sectionFormulary_received
{ // class eclApp_sectionFormulary_received

static function is_child ($me, $name)
{ // function is_child
if ($name == '-received')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-received');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->access = 4;
$me->data = $store->control->read ('sectionFormulary_received_content');
$me->map = array ('sectionFormulary_record');
$children = $store->domainExtras->children ($me->domainId, MODE_FORM, $me->parent->id);
$num = 0;
foreach ($children as $data)
{ // each children
if (!$data['status'] or $data['status'] == 720)
$num++;
} // each children
$me->data['num_children'] = $num;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;
$view = true;

if (isset ($document->actions['selectcolumns']))
$view = self::action_selectcolumns ($document);

$document->mod->context->appendChild ('sectionFormulary_received_selectColumns')
->active (isset ($document->actions['selectcolumns']))
->url (true, true, '_selectcolumns');

if (!$view)
return;

$document->mod->list = new eclMod_sectionFormulary_received_list ($document);
} // function dispatch

static function action_selectcolumns ($document)
{ // function action_selectcolumns
$formulary = $document->createFormulary ('sectionFormulary_received_selectColumns', [], 'selectcolumns');
$formulary->action = '_selectcolumns';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['selectcolumns']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['selectcolumns']);
return true;
} // save

$document->mod->formulary = $formulary;
$document->dataReplace ('sectionFormulary_received_selectColumns');
return false;
} // function action_selectcolumns

} // class eclApp_sectionFormulary_received

?>