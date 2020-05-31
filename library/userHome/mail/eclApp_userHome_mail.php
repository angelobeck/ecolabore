<?php

class eclApp_userHome_mail
{ // class eclApp_userHome_mail

static function is_child ($me, $name)
{ // function is_child
if (!INTEGRATION_SMTP_ENABLE)
return false;
if ($name == '-mail')
return true;
if ($name == '-mail-check')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (INTEGRATION_SMTP_ENABLE)
return array ('-mail');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userHome_mail_content');
$me->isDomain = true;

if ($me->name == '-mail-check')
$me->access = 0;
else
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

if ($document->application->name == '-mail-check')
return self::action_mail_check ($document);

if ($document->actions ('mail', 'input'))
return self::action_mail_input ($document);

$me = $document->application->parent;
$user = $store->user->open ($me->name);

if (isset ($me->data['flags']['userHome_mail_key']) and isset ($me->data['flags']['userHome_mail_time']) and $me->data['flags']['userHome_mail_time'] + SYSTEM_SESSION_TTL > TIME)
return self::action_mail_sent ($document);

return self::action_mail_input ($document);
} // function dispatch

static function action_mail_input ($document)
{ // function action_mail_input
$me = $document->application->parent;

$formulary = $document->createFormulary ('userHome_mail_edit', $me->data, 'input');
$formulary->action = '_mail-input';

if ($formulary->command ('next') and $formulary->save ())
{ // save
$key = mt_rand (1000000, 9999999);

if (isset ($formulary->data['text']['security']))
$me->data['text']['security'] = $formulary->data['text']['security'];

$me->data['flags']['userHome_mail_key'] = $key;
$me->data['flags']['userHome_mail_time'] = TIME; 
$me->data['flags']['userHome_mail_mail'] = $formulary->data['local']['mail'];

$template = clone $document;
$template->user = $me;
$template->charset = 'UTF-8';
$template->dataReplace ('userHome_mail_messageTemplate');
$template->data['url'] = $document->url (array (SYSTEM_PROFILES_URI, $me->name, '-mail-check'), true, '_key-' . $key);
$template->render ();

$subject = $template->textSelectLanguage ($template->textMerge ($template->data['text']['title'], ' ', $me->data['text']['caption']));

$mail = $document->createMail ()
->subject ($subject[1])
->to ($formulary->data['local']['mail'])
->contentHTML ($template->buffer);

$errorMsg = $mail->send ();

if ($errorMsg)
{ // mail error
$document->mod->humperstilshen->alert ($errorMsg);
$document->mod->formulary = $formulary;
return;
} // mail error

self::action_mail_sent ($document);
return;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function action_mail_input

static function action_mail_sent ($document)
{ // function action_mail_sent
$me = $document->user;

$formulary = $document->createFormulary ('userHome_mail_sent', [], 'sent');

if ($formulary->command ('cancel'))
return $document->dataReplace ('layouts/dialog_cancel');

if ($formulary->command ('update'))
return self::action_mail_input ($document);

$document->mod->formulary = $formulary;
$document->dataMerge ('userHome_mail_contentMailSent');
$document->data['mail'] = $me->data['flags']['userHome_mail_mail'];
} // function action_mail_sent

static function action_mail_check ($document)
{ // function action_mail_check
global $store;
$me = $document->application->parent;
$user = &$store->user->open ($me->name);

$document->dataReplace ('userHome_mail_content');

if (!isset ($document->actions['key'][1]))
return $document->dataMerge ('userHome_mail_contentCheckFail');

if (!isset ($me->data['flags']['userHome_mail_key']))
{ // just verified or an error occurred
if ($user['mail'] == '')
return $document->dataMerge ('userHome_mail_contentCheckFail');

if ($document->user->name == $me->name)
return $document->dataMerge ('userHome_mail_contentCheckVerified');

$document->user = $me;
return $document->dataMerge ('userHome_mail_contentCheckReady');
} // verified or error

$key = $document->actions['key'][1];
if ($key != $me->data['flags']['userHome_mail_key'])
return $document->dataMerge ('userHome_mail_contentCheckFail');

if ($me->data['flags']['userHome_mail_time'] + SYSTEM_SESSION_TTL < TIME)
return $document->dataMerge ('userHome_mail_contentCheckFail');

$user['mail'] = $me->data['flags']['userHome_mail_mail'];
$me->data['local']['mail'] = $me->data['flags']['userHome_mail_mail'];

unset ($me->data['flags']['userHome_mail_key']);
unset ($me->data['flags']['userHome_mail_time']);
unset ($me->data['flags']['userHome_mail_mail']);

$document->user = $me;
$document->dataMerge ('userHome_mail_contentCheckVerified');
} // function action_mail_check

} // class eclApp_userHome_mail

?>