<?php

class eclApp_domainGlossary
{ // class eclApp_domainGlossary

static function is_child ($me, $name)
{ // function is_child
if ($name == '-glossary')
return true;
return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-default');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('domainGlossary_content');
$me->map = array ('domainGlossary_keyword');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_domainGlossary_list ($document);
} // function dispatch

} // class eclApp_domainGlossary

?>