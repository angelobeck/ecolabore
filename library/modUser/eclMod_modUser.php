<?php

class eclMod_modUser
{ // class eclMod_modUser

public $enabled = false;
public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store, $system;
$document = $this->document;
$render = $document->render;

if (!$document->access (1))
return;

if ($document->application->isDomain)
return;

if (isset ($document->data['flags']['modUser_enabled']) and !$document->data['flags']['modUser_enabled'])
return;

$row = $mod->appendChild ();
// go to user profile main page
if (substr ($document->user->name, 0, 4) != '-id-')
$row->appendChild ('modUser_goMain')
->url (array (SYSTEM_PROFILES_URI, $document->user->name))
->active (count ($document->application->pathway) > 1 and $document->application->pathway[0] == SYSTEM_PROFILES_URI and $document->application->pathway[1] == $document->user->name);
else
$row->appendChild ('modUser_goPartialSubscription')
->url (array (SYSTEM_PROFILES_URI, $document->user->name))
->active (count ($document->application->pathway) > 1 and $document->application->pathway[0] == SYSTEM_PROFILES_URI and $document->application->pathway[1] == $document->user->name);

// Go to admin area
if ($document->access (4, $system->groups))
$row->appendChild ('modUser_goAdmin')
->url (array (SYSTEM_ADMIN_URI))
->active ($document->application->pathway[0] == SYSTEM_ADMIN_URI);

// Back to home
if (SYSTEM_HOSTING_MODE == 0 and $document->application->pathway[0] != SYSTEM_DEFAULT_DOMAIN_NAME)
$row->appendChild ('modUser_welcome_goHome')
->url (array (SYSTEM_DEFAULT_DOMAIN_NAME));

// logout
$application = $document->application;
while ($application->access)
{ // access denied
$application = $application->parent;
} // access denied
$row->appendChild ('modUser_goLogout')
->virtual ()
->url ($application->pathway, true, '_logout');

$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modUser_module'));

$mod->enabled = true;
} // function setModule

} // class eclMod_modUser

?>