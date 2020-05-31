<?php

class eclApp_domain
{ // class eclApp_domain

static function is_child ($me, $name)
{ // function is_child
global $store;
if ($store->domain->getStatus ($name))
return true;

if (SYSTEM_HOSTING_MODE == 0 and $name == SYSTEM_DEFAULT_DOMAIN_NAME and $name != SYSTEM_ADMIN_URI and $name != SYSTEM_PROFILES_URI)
{ // creates the default domain

$adminId = $store->user->getId (ADMIN_IDENTIFIER);
if (!$adminId)
{ // creates profile
// New profile for administrator
$admin['name'] = ADMIN_IDENTIFIER;
$admin['status'] = 1;
$adminId = $store->user->insert ($admin);

if (!is_dir (PATH_PROFILES))
mkdir (PATH_PROFILES);
if (!is_dir (PATH_PROFILES . ADMIN_IDENTIFIER))
mkdir (PATH_PROFILES . ADMIN_IDENTIFIER);

// register the administrator profile
$caption[TEXT_CONTENT] = ADMIN_CAPTION;
if (SYSTEM_DEFAULT_CHARSET == 'ISO-8859-1')
$caption[TEXT_CHARSET] = 1;
$admin_data['text'] = array (
'caption' => array (SYSTEM_DEFAULT_LANGUAGE => $caption), 
'title' => array (SYSTEM_DEFAULT_LANGUAGE => $caption), 
);
$admin_data['local']['mail'] = ADMIN_MAIL;
$admin_data['local']['gender'] = ADMIN_GENDER;
$admin_data['mode'] = MODE_DOMAIN;
$admin_data['name'] = '-register';
$store->userContent->insert ($adminId, $admin_data);
$admin_data['name'] = '-index';
$store->userContent->insert ($adminId, $admin_data);
} // creates profile

// Creates domain
$domain['name'] = SYSTEM_DEFAULT_DOMAIN_NAME;
$domain['status'] = 1;
$domainId = $store->domain->insert ($domain);

if (!is_dir (PATH_DOMAINS))
mkdir (PATH_DOMAINS);
if (!is_dir (PATH_DOMAINS . SYSTEM_DEFAULT_DOMAIN_NAME))
mkdir (PATH_DOMAINS . SYSTEM_DEFAULT_DOMAIN_NAME);

// Register domain
$domain_data = $store->control->read ('domain_empty_templateForNewDomain');
$domain_data['mode'] = MODE_DOMAIN;
$domain_data['name'] = '-register';
$store->domainContent->insert ($domainId, $domain_data);

// Register administrator
$group = &$store->domainGroup->open ($domainId, 1);
$group[$adminId] = 4;

return true;
} // creates the default domain

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'domain';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->domainId = $store->domain->getId ($me->name);
$me->groups[] = new eclGroup_domain ($me->domainId);
$status = $store->domain->getStatus ($me->name);
if ($status == 1)
{ // empty
$me->data = &$store->domainContent->openChild ($me->domainId, MODE_DOMAIN, 0, '-register');
$me->map = ['userJoin', 'domainStyles', 'domainScripts', 'personalite', 'domain_empty'];
} // empty
elseif ($status == 5)
{ // disabled
$me->map = ['userJoin', 'domainStyles', 'domainScripts', 'personalite', 'domain_disabled'];
} // disabled
elseif ($status == 2 or $status == 4)
{ // active
$me->data = &$store->domainContent->openChild ($me->domainId, MODE_DOMAIN, 0, '-register');
$me->getMap ();
if ($status == 4)
$me->access = 4;
} // active
$me->isDomain = true;
} // function constructor_helper

} // class eclApp_domain

?>