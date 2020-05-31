<?php

class eclApp_adminDatabase_config
{ // class eclApp_adminDatabase_config

const name = 'config';
const menuType = 'section';
const dataFrom = 'adminDatabase_config_content';

static function dispatch ($document)
{ // function dispatch
global $io, $store;

$formulary = $document->createFormulary ('adminDatabase_config_edit');
if ($formulary->save ())
{ // save
if ($io->systemConstants->check ('DATABASE_ENABLE') and $io->systemConstants->constants['DATABASE_ENABLE'])
{ // check database connection
$store->close ();
$io->close ();
$io->database->reconnect ();
if ($io->database->status)
$document->mod->humperstilshen->alert ('admin_constants_databaseEnabledSuccess');
else
$document->mod->humperstilshen->alert ('admin_constants_databaseEnabledFailed');
} // check database connection
else
$document->mod->humperstilshen->alert ('admin_constants_databaseEnabledDisabled');

$io->session->regenerate();
} // save
$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminDatabase_config

?>