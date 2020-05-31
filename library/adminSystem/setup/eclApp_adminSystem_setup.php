<?php

class eclApp_adminSystem_setup
{ // class eclApp_adminSystem_setup

const name = 'setup';
const menuType = 'section';
const dataFrom = 'adminSystem_setup_content';

static function dispatch ($document)
{ // function dispatch
$formulary = $document->createFormulary ('adminSystem_setup_edit');

if ($formulary->save ())
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminSystem_setup

?>