<?php

class eclApp_sectionFormulary
{ // class eclApp_sectionFormulary

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('sectionFormulary_received', 'sectionFormulary_removed', 'sectionFormulary_statistics', 'sectionFormulary_done', 'sectionFormulary_selectColumns', 'personaliteFields');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$me = $document->application;
$view = true;
if ($document->access (4))
{ // admin access

// edit fields
if ($document->actions ('fields', 'edit') or !isset ($me->data['extras']['formulary']))
$view = self::action_edit_fields ($document);

// context edit fields
$document->mod->context->appendChild ('sectionFormulary_fields')
->url (true, true, '_fields-edit')
->active ($document->actions ('fields', 'edit') or !isset ($me->data['extras']['formulary']));

// security
if ($document->actions ('security', 'edit'))
$view = self::action_edit_security ($document);

// context edit security
$document->mod->context->appendChild ('sectionFormulary_security')
->url (true, true, '_security-edit')
->active ($document->actions ('security', 'edit'));
} // admin access

if (!$view)
return;

if (isset ($document->actions['review'][1]))
$view = self::action_review ($document);

elseif (isset ($me->data['extras']['formulary']))
$view = self::action_formulary ($document);

if (!$view)
return;

if ($document->access (4))
$document->mod->editor->enable ();

$document->mod->list = new eclMod_sectionFolder_list ($document);
} // function dispatch

static function action_edit_fields ($document)
{ // function action_edit_fields
$me = $document->application;

$formulary = $document->createFormulary ('sectionFormulary_fields', $me->data, 'editfields');
$formulary->action = '_fields-edit';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['fields']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['fields']);

$me->data = $formulary->data;
return true;
} // save

$document->dataReplace ('sectionFormulary_fields');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
return false;
} // function action_edit_fields

static function action_edit_security ($document)
{ // function action_edit_security
$me = $document->application;

$formulary = $document->createFormulary ('sectionFormulary_security', $me->data, 'editsecurity');
$formulary->action = '_security-edit';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['security']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['security']);

$me->data = $formulary->data;
return true;
} // save

$document->dataReplace ('sectionFormulary_security');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
return false;
} // function action_edit_security

static function action_formulary ($document)
{ // function action_formulary
$me = $document->application;

if (isset ($document->actions['review'][1]) and $token = $document->actions['review'][1] and isset ($document->session['form_' . $token]))
$data = $document->session['form_' . $token];
elseif ($document->actions ('share', 'user', 'data', 'enable'))
{ // share user data
$data = array ('share_user_data' => true);
} // share user data
elseif ($document->user->userId and !$document->received)
{ // user is connected
$data = [];

foreach ($me->data['extras']['formulary'] as $field)
{ // each field
if (isset ($field['share_user_data']))
{ // share request

$local = array (
'alert' => 1, 
'url' => $document->url (true, true, '_share-user-data-enable')
);
$document->mod->humperstilshen->dialog ('personaliteFields_alertShareUserDataRequest', $local);

break;
} // share request
} // each field
} // user is connected
else
$data = [];

$formulary = $document->createFormulary ('sectionFormulary_formulary', $data, 'page0');

if ($formulary->command ('cancel'))
{ // cancel
$formulary->data = [];
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
$token = md5 (TIME . session_id ());
$document->actions['review'] = array ('review', $token);
$document->session['form_' . $token] = $formulary->data;

if (isset ($document->application->data['flags']['form_review']))
return self::action_review ($document);

return self::action_done ($document);
} // save

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
$document->mod->formulary = $formulary;
$document->mod->panel->main = array ('content', 'formulary', 'list');
return true;
} // function action_formulary

static function action_review ($document)
{ // function action_review
$token = $document->actions['review'][1];
if (isset ($document->session['form_' . $token]))
$data = $document->session['form_' . $token];
else
return false;

$formulary = $document->createFormulary ('sectionFormulary_review', $data, 'review');

if ($formulary->command ('previous'))
return self::action_formulary ($document);

if ($formulary->command ('save'))
return self::action_done ($document);

$formulary->action = '_review-' . $token;
$document->mod->formulary = $formulary;
$document->mod->panel->main = array ('content', 'formulary', 'list');
return false;
} // function action_review

static function action_done ($document)
{ // function action_done
global $store;
$me = $document->application;

$token = $document->actions['review'][1];

$data = $document->session['form_' . $token];
$data['domain_id'] = $me->domainId;
$data['mode'] = MODE_FORM;
$data['parent_id'] = $me->id;
$data['owner_id'] = $document->user->userId;
$data['status'] = 720;

$id = $store->domainExtras->insert ($me->domainId, $data);

unset ($document->actions['review']);
unset ($document->session['form_' . $token]);

$message = $me->child ('-done');
$document->dataReplace ($message->data);

if (!isset ($me->data['local']['mail']) or !INTEGRATION_SMTP_ENABLE)
return false;

$template = clone $document;
$template->application = $me->child ('-received')->child ($id);
$template->application->access = 0;
$template->dispatch ();
$template->data['flags']['modLayout_base'] = 'mail';
$template->charset = 'UTF-8';
$template->protectedLayout = false;
$template->render ();

$subject = $template->textSelectLanguage ($template->textMerge ($document->domain->data['text']['title'], ' -> ', $me->data['text']['caption']));

$mail = $document->createMail ()
->subject ($subject[1])
->to ($me->data['local']['mail'])
->contentHTML ($template->buffer);

$errorMsg = $mail->send ();

if ($errorMsg)
$document->mod->humperstilshen->alert ($errorMsg);

return false;
} // function action_done

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionFormulary

?>