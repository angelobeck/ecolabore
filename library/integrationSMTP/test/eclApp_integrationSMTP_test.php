<?php

class eclApp_integrationSMTP_test
{ // class eclApp_integrationSMTP_test

static function is_child ($me, $name)
{ // function is_child
if ($name == 'test')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
if (defined ('INTEGRATION_SMTP_ENABLE') and INTEGRATION_SMTP_ENABLE)
return array ('test');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('integrationSMTP_test_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io;

$data['from'] = INTEGRATION_SMTP_FROM;
$formulary = $document->createFormulary ('integrationSMTP_test_edit', $data);

if ($formulary->save ())
{ // formulary saved
$data = $formulary->data;
$mail = new eclIo_smtp ();

if (isset ($data['from']))
$mail->from ($data['from']);
if (isset ($data['to']))
$mail->to ($data['to']);
if (isset ($data['cc']))
$mail->cc ($data['cc']);
if (isset ($data['bcc']))
$mail->bcc ($data['bcc']);
if (isset ($data['subject']))
$mail->subject ($data['subject'], $document->charset);
if (isset ($data['text']))
$mail->contentTXT ($data['text'], $document->charset);
if (isset ($data['html']))
$mail->contentHTML ($data['html'], $document->charset);

$errorMsg = $mail->send ();
$buffer['log'] = $mail->log;

$log = $document->createFormulary ('integrationSMTP_test_view', $buffer, 'mail');
$document->mod->formulary = $log;

if ($errorMsg)
$document->mod->humperstilshen->alert ($errorMsg);
return;
} // formulary saved

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_integrationSMTP_test

?>