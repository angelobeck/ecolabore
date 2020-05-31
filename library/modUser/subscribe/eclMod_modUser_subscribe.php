<?php

class eclMod_modUser_subscribe
{ // class eclMod_modUser_subscribe

public $document;

public function __construct ($document)
{ // function __construct
$this->document = $document;
} // function __construct

public function setModule ($mod, $arguments)
{ // function setModule
global $store, $system;
$document = $this->document;

if ($document->access (1))
return;

$domain = $document->domain;
$main = $system->child (SYSTEM_DEFAULT_DOMAIN_NAME);
if (!$main)
return;

// Recover password only if mails can be sent
if (INTEGRATION_SMTP_ENABLE)
{ // recover password
$mod->appendChild ('modUser_welcome_goPassword')
->url (array (SYSTEM_PROFILES_URI, '-recover-password'))
->popUpOpen ();
} // recover password

// subscribe
if ($domain->child ('-join'))
$mod->appendChild ('modUser_welcome_goSubscribe')
->url (array ($domain->name, '-join'))
->popUpOpen ();

$mod->data = $store->control->read ('modUser_subscribe_module');

$mod->enabled = true;
} // function setModule

} // class eclMod_modUser_subscribe

?>