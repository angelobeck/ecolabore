<?php

class eclApp_adminSystem_log
{ // class eclApp_adminSystem_log

const name = 'log';
const menuType = 'section';
const dataFrom = 'adminSystem_log_content';

static function dispatch ($document)
{ // function dispatch
$formulary = $document->createFormulary ('adminSystem_log_edit');
if ($formulary->command ('save') and $formulary->save ())
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');
$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminSystem_log

?>