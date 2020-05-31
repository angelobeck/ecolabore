<?php

class eclApp_integrationSMTP
{ // class eclApp_integrationSMTP

static function is_child ($me, $name)
{ // function is_child
if ($name != 'smtp')
return false;
if (SYSTEM_HOSTING_MODE == 0)
return true;
if ($me->applicationName == 'adminIntegrations')
return true;
if (defined ('INTEGRATION_SMTP_MODE') and INTEGRATION_SMTP_MODE > 1)
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (SYSTEM_HOSTING_MODE == 0)
return array ('smtp');
if ($me->applicationName == 'adminIntegrations')
return array ('smtp');
if (defined ('INTEGRATION_SMTP_MODE') and INTEGRATION_SMTP_MODE > 1)
return array ('smtp');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store, $system;
$me->data = $store->control->read ('integrationSMTP_content');
$me->map = array ('integrationSMTP_config', 'integrationSMTP_test');
$me->groups = $system->groups;
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_integrationSMTP

?>