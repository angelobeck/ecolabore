<?php

class eclApp_sectionPrivacyPolicy_create
{ // class eclApp_sectionPrivacyPolicy_create

static function is_child ($me, $name)
{ // function is_child
global $store;
if (!$store->domainContent->findMarker ($me->domainId, 7) and $name == 'privacypolicy')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;

$domain = $me;
while (!$domain->isDomain and $domain = $domain->parent);
if ($domain->name != SYSTEM_DEFAULT_DOMAIN_NAME)
return [];

if (!$store->domainContent->findMarker ($me->domainId, 7))
return array ('privacypolicy');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionPrivacyPolicy_create_content');
$me->map = array ('section_preset');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_sectionPrivacyPolicy_create

?>