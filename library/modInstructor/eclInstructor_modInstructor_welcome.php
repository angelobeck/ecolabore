<?php

class eclInstructor_modInstructor_welcome
{ // class eclInstructor_modInstructor_welcome

static function route ($instructor)
{ // function route
global $store;
$document = $instructor->document;
$me = $document->application;

if ($me->applicationName != 'domain_empty')
{ // index page exists
if ($me->applicationName == 'domainIndex' and $document->access (4) and !$store->domainContent->children ($me->domainId, MODE_SECTION, 0))
{ // before new sections
if (isset ($document->actions['login']))
goto WELCOME_RETURN;
if ($document->actions ('first', 'edit', 'save'))
goto WELCOME_DONE;
if (!isset ($document->actions['instructor']))
goto WELCOME_DONE;
} // before new sections

return false;
} // index page exists

if ($document->access (4))
{ // admin
if (isset ($document->actions['login']))
goto WELCOME_EDIT;
if ($document->mod->formulary->errorMsg)
goto WELCOME_EDIT_ERROR;

goto WELCOME_EDIT;
} // admin

if ($document->access (1))
goto WELCOME_ACCESS_DENIED;

if ($document->mod->humperstilshen->loginError)
goto WELCOME_LOGIN_ERROR;

goto WELCOME_EMPTY;

WELCOME_EMPTY:
$instructor->addMessage ('domain_empty_help01empty');
$instructor->addMessage ('domain_empty_help01login');
return true;

WELCOME_LOGIN_ERROR:
$instructor->addMessage ('domain_empty_help01empty');
$instructor->addMessage ('domain_empty_help02loginError');
return true;

WELCOME_ACCESS_DENIED:
$instructor->addMessage ('domain_empty_help02accessDenied');
return true;

WELCOME_EDIT:
if (isset ($document->user->data['local']['gender']) and $document->user->data['local']['gender'] == 'female')
$instructor->addMessage ('domain_empty_help02welcomeAdmin_f');
else
$instructor->addMessage ('domain_empty_help02welcomeAdmin_m');

$instructor->addMessage ('domain_empty_help04index01edit');
return true;

WELCOME_EDIT_ERROR:
$instructor->addMessage ('domain_empty_help04index01edit');
$instructor->addMessage ('domain_empty_help04index01editError');
return true;

WELCOME_DONE:
$instructor->addMessage ('domain_empty_help04index02editDone')
->url (true, true, '_instructor-next');
return true;

WELCOME_RETURN:
$instructor->addMessage ('domain_empty_help04index02return')
->url (true, true, '_instructor-next');
return true;
} // function route

} // class eclInstructor_modInstructor_welcome

?>