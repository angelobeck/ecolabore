<?php

class eclMod_modUser_tools_contactcheck
{ // class eclMod_modUser_tools_contactcheck

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

$user = $store->user->openById ($document->user->userId);
$register = $store->userContent->open ($document->user->userId, '-register');

// Verify phone
if (INTEGRATION_SMS_ENABLE)
{ // verify by SMS
if (isset ($register['local']['phone']))
$row->appendChild ('userHome_phone_contextVerified')
->set ('phone', implode ('-', $register['local']['phone']))
->url ([SYSTEM_PROFILES_URI, $document->user->name, '-phone'])
->popUpOpen ();
else
$row->appendChild ('userHome_phone_contextVerify')
->url ([SYSTEM_PROFILES_URI, $document->user->name, '-phone'])
->popUpOpen ();
} // verify by SMS

// Verify mail
if (INTEGRATION_SMTP_ENABLE)
{ // verify by SMTP
if ($user['mail'] == '')
$row->appendChild ('userHome_mail_content')
->url ([SYSTEM_PROFILES_URI, $document->user->name, '-mail'])
->popUpOpen ();
else
$row->appendChild ('userHome_mail_contextVerified')
->url ([SYSTEM_PROFILES_URI, $document->user->name, '-mail'])
->set ('mail', $register['local']['mail'])
->popUpOpen ();
} // verify by SMTP


$mod->data = array_replace ($render->block ('modules/system_menu'), $store->control->read ('modUser_tools_moduleContactCheck'));
$mod->enabled = true;
} // function setModule

} // class eclMod_modUser_tools_contactcheck

?>