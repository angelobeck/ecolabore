<?php

class eclApp_adminSystem_hosting
{ // class eclApp_adminSystem_hosting

const name = 'hosting';
const menuType = 'section';
const dataFrom = 'adminSystem_hosting_content';

static function dispatch ($document)
{ // function dispatch
global $io;

$formulary = $document->createFormulary ('adminSystem_hosting_edit');

if ($formulary->save ())
{ // formulary saved
$document->mode = $io->systemConstants->get ('SYSTEM_HOSTING_MODE');

$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');
} // formulary saved

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminSystem_hosting

?>