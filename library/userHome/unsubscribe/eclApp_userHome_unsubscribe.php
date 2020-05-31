<?php

class eclApp_userHome_unsubscribe
{ // class eclApp_userHome_unsubscribe

static function is_child ($me, $name)
{ // function is_child
if ($name == '-unsubscribe')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-unsubscribe');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;

$me->data = $store->control->read ('userHome_unsubscribe_content');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application->parent;

$formulary = $document->createFormulary ('userHome_unsubscribe_edit', [], 'unsubscribe');

if ($formulary->command ('cancel'))
{ // close pop up
$document->dataReplace ('layouts/dialog_cancel');
} // close pop up

if ($formulary->command ('save') and $formulary->save ())


{ // save
$document->mod->humperstilshen->alert ('userHome_unsubscribe_alertDataUpdated');
} // save

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_userHome_unsubscribe

?>