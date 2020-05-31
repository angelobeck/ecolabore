<?php

class eclApp_adminUsers
{ // class eclApp_adminUsers

const name = 'users';
const menuType = 'section';
const dataFrom = 'adminUsers_content';

static function constructor_helper ($me)
{ // function constructor_helper
global $io, $store;
if ($io->database->tableEnabled ($store->user) and is_dir (PATH_PROFILES))
$me->map = array ('adminUsers_add', 'adminUsers_details');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application;

if (!$io->database->tableEnabled ($store->user))
{ // disabled
$document->dataMerge ('adminUsers_contentDisabled');
return;
} // disabled

if (!is_dir (PATH_PROFILES) and !$document->access (4))
{ // no folder for visitors
$document->dataMerge ('adminUsers_contentEmpty');
return;
} // no folder for visitors

if (!is_dir (PATH_PROFILES))
{ // create folder
$formulary = $document->createFormulary ('adminUsers_createFolder', [], 'createFolder');
if ($formulary->command ('create') and $formulary->save ())
{ // create
$folder_profiles = PATH_ROOT . $io->systemConstants->get ('FOLDER_PROFILES');
} // create
else
{ // view form
$document->mod->formulary = $formulary;
return;
} // view form
} // create folder

$document->mod->list = new eclMod_adminUsers_list ($document);

if (!isset ($folder_profiles))
$folder_profiles = PATH_PROFILES;

if ($document->access (4) and !$store->User->getStatus (ADMIN_IDENTIFIER))
{ // create the admin profile
$formulary = $document->createFormulary ('adminUsers_adminProfile', [], 'admin_profile');

if ($formulary->command ('create'))
{ // create
$user = array (
'name' => ADMIN_IDENTIFIER, 
'password' => ADMIN_PASSWORD, 
'status' => 1
);
$userId = $store->user->insert ($user);

$data['mode'] = MODE_DOMAIN;
$data['name'] = '-register';
$data['text'] = array (
'caption' => array ($document->lang => array (TEXT_CONTENT => ADMIN_CAPTION)), 
'title' => array ($document->lang => array (TEXT_CONTENT => ADMIN_CAPTION))
);
$data['local']['mail'] = ADMIN_MAIL;
$data['local']['gender'] = ADMIN_GENDER;

$store->userContent->insert ($userId, $data);
} // create
else
$document->mod->formulary = $formulary;

$document->mod->panel->main = array ('content', 'formulary', 'list');
} // create the admin profile

if (count (scandir ($folder_profiles)) == 2)
$document->dataMerge ('adminUsers_contentEmpty');
} // function dispatch

} // class eclApp_adminUsers

?>