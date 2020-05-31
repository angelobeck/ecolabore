<?php

class eclApp_personaliteFields
{ // class eclApp_personaliteFields

static function is_child ($me, $name)
{ // function is_child
if ($name == '-fields')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-fields');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteFields_content');
$me->map = array ('personaliteFields_config');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$formulary = $document->createFormulary ('personaliteFields_add');
if ($formulary->command ('cancel'))
return $document->dataReplace ('layouts/dialog_cancel');

$document->mod->formulary = $formulary;
$document->mod->list = new eclMod_personaliteFields_list ($document);
} // function dispatch

} // class eclApp_personaliteFields

?>