<?php

class eclApp_userRecoverPassword
{ // class eclApp_userRecoverPassword

static function is_child ($me, $name)
{ // function is_child
if (INTEGRATION_SMTP_ENABLE and $name == '-recover-password')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (INTEGRATION_SMTP_ENABLE)
return array ('-recover-password');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userRecoverPassword_content');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $system;

if (isset ($document->actions['close']))
return $document->dataReplace ('layouts/dialog_cancel');

$formulary = $document->createFormulary ('userRecoverPassword_edit');

if ($formulary->save ())
{ // save formulary
$token = md5 (mt_rand () . TIME);
$user = $system->child (SYSTEM_PROFILES_URI)->child ($formulary->data['identifier']);
$user->data['flags']['userRecoverPassword_token'] = $token;
$user->data['flags']['userRecoverPassword_time'] = TIME;

$template = clone $document;
$template->user = $user;
$template->charset = 'UTF-8';
$template->dataReplace ('userRecoverPassword_messageTemplate');
$template->data['url'] = $document->url (array (SYSTEM_PROFILES_URI, $template->user->name, '-recover-password'), true, '_token-' . $token);
$template->render ();

$subject = $template->textSelectLanguage ($template->textMerge ($template->data['text']['title'], ' ', $user->data['text']['caption']));

$mail = $document->createMail ()
->subject ($subject[1])
->to ($formulary->data['local']['mail'])
->contentHTML ($template->buffer);

$errorMsg = $mail->send ();

$done = $document->createFormulary ('userRecoverPassword_done', [], 'done');
$done->action = '_close';
$document->mod->formulary = $done;

if ($errorMsg)
{ // mail error
$document->mod->humperstilshen->alert ($errorMsg);
return;
} // mail error

$document->dataMerge ('userRecoverPassword_msgMailSent');
$document->data['mail_from'] = INTEGRATION_SMTP_FROM;
return;
} // save formulary

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_userRecoverPassword

?>