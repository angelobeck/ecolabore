<?php

class eclApp_adminDatabase_log
{ // class eclApp_adminDatabase_log

const name = 'log';
const menuType = 'section';
const dataFrom = 'adminDatabase_log_content';

static function dispatch ($document)
{ // function dispatch
$formulary = $document->createFormulary ('adminDatabase_log_edit');
if ($formulary->command ('save') and $formulary->save ())
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');
$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminDatabase_log

?>