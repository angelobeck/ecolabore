<?php

class eclMod_modUser_welcome
{ // class eclMod_modUser_welcome

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

if (isset ($document->data['flags']['modUser_enabled']) and !$document->data['flags']['modUser_enabled'])
return;

$row = $mod->appendChild ();
$domain = $document->domain;
$main = $system->child (SYSTEM_DEFAULT_DOMAIN_NAME);
if ($main === false)
$main = $domain;

// Recover password only if mails can be sent
if (INTEGRATION_SMTP_ENABLE)
{ // recover password
$row->appendChild ('modUser_welcome_goPassword')
->url (array (SYSTEM_PROFILES_URI, '-recover-password'))
->popUpOpen ();
} // recover password

// subscribe
if (SYSTEM_ENABLE_USER_SUBSCRIPTIONS and $domain->child ('-join'))
$row->appendChild ('modUser_welcome_goSubscribe')
->url (array ($domain->name, '-join'))
->popUpOpen ();
elseif (SYSTEM_ENABLE_USER_SUBSCRIPTIONS)
$row->appendChild ('modUser_welcome_goSubscribe')
->url (array (SYSTEM_PROFILES_URI, '-join'))
->popUpOpen ();

// info
if ($domain->name != SYSTEM_PROFILES_URI and $domain->name != SYSTEM_ADMIN_URI and $store->domain->getStatus ($domain->name) >= 2)
{ // show info
if ($id = $store->domainContent->findMarker ($domain->domainId, 6))
{ // info page exists
$pathway = $store->domainContent->pathway ($domain->domainId, $id);
$row->appendChild ('modUser_welcome_goInfo')
->url ($pathway)
->active ($document->pathway == $pathway);
} // info page exists
else
$row->appendChild ('modUser_welcome_goInfo')
->url ([$domain->name, '-info'])
->active ($document->pathway == [$domain->name, '-info']);
} // show info

// Organization identification
if (SYSTEM_HOSTING_MODE and $domain->name != SYSTEM_DEFAULT_DOMAIN_NAME and isset ($project['text']['caption']) and isset ($project['local']['url']))
$row->appendChild ('modUser_welcome_goOrganization')
->set ('url', $project['local']['url']);

// Go to profiles page
$row->appendChild ('modUser_welcome_goProfiles')
->url ([SYSTEM_PROFILES_URI])
->active ($document->application->pathway[0] == SYSTEM_PROFILES_URI);

// Back to home
if (SYSTEM_HOSTING_MODE == 0 and $document->application->pathway[0] != SYSTEM_DEFAULT_DOMAIN_NAME)
$row->appendChild ('modUser_welcome_goHome')
->url ([SYSTEM_DEFAULT_DOMAIN_NAME]);

// Abuse report
switch ($domain->name)
{ // switch domain name
case SYSTEM_DEFAULT_DOMAIN_NAME:
case SYSTEM_ADMIN_URI:
break;

case SYSTEM_PROFILES_URI:
if(count ($document->pathway) == 1)
break;

$row->appendChild ('modUser_welcome_goReport')
->url ([SYSTEM_PROFILES_URI, $document->pathway[1], '-abuse-report'])
->popUpOpen();
break;

default:
$row->appendChild ('modUser_welcome_goReport')
->url ([$domain->name, '-abuse-report'])
->popUpOpen();
} // switch domain name

// Service terms
if ($id = $store->domainContent->findMarker ($domain->domainId, 5))
{ // this service terms
$pathway = $store->domainContent->pathway ($domain->domainId, $id);
$row->appendChild ('modUser_welcome_goServiceTerms')
->url ($pathway)
->active ($document->pathway == $pathway);
} // this service terms
elseif ($domain->name != SYSTEM_DEFAULT_DOMAIN_NAME and $id = $store->domainContent->findMarker ($main->domainId, 5))
{ // global service terms
$row->appendChild ('modUser_welcome_goServiceTerms')
->url ($store->domainContent->pathway ($main->domainId, $id));
} // global service terms

// Privacy policy
if ($id = $store->domainContent->findMarker ($domain->domainId, 7))
{ // this privacy policy
$pathway = $store->domainContent->pathway ($domain->domainId, $id);
$row->appendChild ('modUser_welcome_goPrivacyPolicy')
->url ($pathway)
->active ($document->pathway == $pathway);
} // this privacy policy
elseif ($domain->name != SYSTEM_DEFAULT_DOMAIN_NAME and $id = $store->domainContent->findMarker ($main->domainId, 7))
{ // global privacy policy
$row->appendChild ('modUser_welcome_goPrivacyPolicy')
->url ($store->domainContent->pathway ($main->domainId, $id));
} // global privacy policy

// About Ecolabore Engine
$row->appendChild ('modUser_welcome_goEcolabore', [
'ecolabore' => ECOLABORE_DATA['text']['caption'],
'url' => ECOLABORE_DATA['local']['url'],
]);

// Extra links
for ($i = 1; $i <= 3; $i++)
{ // each extra links
if (!isset ($project['text']['caption_' . $i]) or !isset ($project['local']['url_' . $i]))
break;

$row->appendChild ([
'caption' => $project['text']['caption_' . $i],
'url' => $project['local']['url_' . $i],
]);
} // each extra links

$mod->data = array_replace ($document->render->block ('modules/system_menu'), $store->control->read ('modUser_welcome_module'));

$mod->enabled = true;
} // function setModule

} // class eclMod_modUser_welcome

?>