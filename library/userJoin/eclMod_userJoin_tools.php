<?php

class eclMod_userJoin_tools
{ // class eclMod_userJoin_tools

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

$row = $mod->appendChild ();

// personal data
$row->appendChild ('modUser_tools_goPersonal')
->url (array (SYSTEM_PROFILES_URI, $document->user->name, '-personal'))
->popUpOpen ();

// public data
$row->appendChild ('modUser_tools_goPublic')
->url (array (SYSTEM_PROFILES_URI, $document->user->name, '-public'))
->popUpOpen ();

$user = $store->user->openById ($document->user->userId);
$register = $store->userContent->open ($document->user->userId, '-register');

// Verify phone
if (INTEGRATION_SMS_ENABLE)
{ // verify by SMS
if ($user['phone'] == '')
$row->appendChild ('userHome_phone_content')
->url (array (SYSTEM_PROFILES_URI, $document->user->name, '-phone'))
->popUpOpen ();
else
$row->appendChild ('userHome_phone_contentVerified')
->url (array (SYSTEM_PROFILES_URI, $document->user->name, '-phone'))
->popUpOpen ();
} // verify by SMS

// Verify mail
if (INTEGRATION_SMTP_ENABLE)
{ // verify by SMTP
if ($user['mail'] == '')
$row->appendChild ('userHome_mail_content')
->url (array (SYSTEM_PROFILES_URI, $document->user->name, '-mail'))
->popUpOpen ();
else
$row->appendChild ('userHome_mail_contextVerified')
->url (array (SYSTEM_PROFILES_URI, $document->user->name, '-mail'))
->set('mail', $register['local']['mail'])
->popUpOpen ();
} // verify by SMTP

// change password
$row->appendChild ('modUser_tools_goPassword')
->url (array (SYSTEM_PROFILES_URI, $document->user->name, '-password'))
->popUpOpen ();

$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modUser_tools_module'));
$mod->enabled = true;
} // function setModule

} // class eclMod_userJoin_tools

?>