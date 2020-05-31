<?php

class eclApp_adminDatabase_cryptography
{ // class eclApp_adminDatabase_cryptography

const name = 'cryptography';
const menuType = 'section';
const dataFrom = 'adminDatabase_cryptography_content';

static function dispatch ($document)
{ // function dispatch
global $io;

$formulary = $document->createFormulary ('adminDatabase_cryptography_edit');

// command create keys
$createKeys = $document->createFormulary ('adminDatabase_cryptography_contextCreateKeysConfirm', [], 'createKeys');
if ($document->actions('create', 'keys') and $createKeys->save ())
{ // create new keys
$io->systemConstants->constants['DATABASE_ENCRYPT_KEY'] = base64_encode (openssl_random_pseudo_bytes(32));
$io->systemConstants->constants['DATABASE_ENCRYPT_HASH'] = base64_encode (openssl_random_pseudo_bytes(32));
$document->mod->humperstilshen->alert ('adminDatabase_cryptography_contextCreateKeysCreated');
$io->session->regenerate (true);
} // create new keys
if ($createKeys->errorMsg)
$document->mod->humperstilshen->alert ($createKeys->errorMsg);

// Context create keys
$document->mod->context->appendChild ('adminDatabase_cryptography_contextCreateKeys')
->confirm ('adminDatabase_cryptography_contextCreateKeysConfirm', $createKeys);

if ($formulary->save ())
{ // formulary saved
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');
$io->session->regenerate (true);
} // formulary saved

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminDatabase_cryptography

?>