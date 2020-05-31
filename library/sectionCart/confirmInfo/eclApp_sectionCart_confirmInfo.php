<?php

class eclApp_sectionCart_confirmInfo
{ // class eclApp_sectionCart_confirmInfo

static function is_child ($me, $name)
{ // function is_child
if ($name == 'confirm-info')
return true;

return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionCart_confirmInfo_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->user->userId)
return self::action_confirm_info ($document);

$document->pathway = $me->pathway;
$document->mod->panel->main = array ('content', 'login', 'user_subscribe', 'formulary');

$formulary = $document->createFormulary ('sectionCart_confirmInfo_previous');
$formulary->pathway = $document->application->pathway;
$document->mod->formulary = $formulary;
} // function dispatch

static function action_confirm_info ($document)
{ // function action_confirm_info
$formulary = $document->createFormulary ('sectionCart_confirmInfo_edit', $document->user->data);

$document->mod->formulary = $formulary;
} // function action_confirm_info

} // class eclApp_sectionCart_confirmInfo

?>