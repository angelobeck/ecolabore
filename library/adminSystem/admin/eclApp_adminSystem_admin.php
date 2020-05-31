<?php

class eclApp_adminSystem_admin
{ // class eclApp_adminSystem_admin

const name = 'admin';
const menuType = 'section';
const dataFrom = 'adminSystem_admin_content';

static function constructor_helper ($me)
{ // function constructor_helper
$me->groups = array ( new eclGroup_adminSystem_admin ());
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io;

if ($document->actions ('admin', 'changed'))
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');

$formulary = $document->createFormulary ('adminSystem_admin_edit');

if ($formulary->save ())
{ // change login
$document->session['user_id'] = true;
$document->session['user_name'] = $io->systemConstants->constants['ADMIN_IDENTIFIER'];
$document->reload = $document->url (true, true, '_admin-changed');
} // change login
else
{ // restore login
$io->systemConstants->set ('ADMIN_IDENTIFIER', ADMIN_IDENTIFIER);
} // restore login
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_adminSystem_admin

?>