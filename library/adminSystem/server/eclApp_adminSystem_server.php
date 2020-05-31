<?php

class eclApp_adminSystem_server
{ // class eclApp_adminSystem_server

const name = 'server';
const menuType = 'section';
const dataFrom = 'adminSystem_server_content';

static function dispatch ($document)
{ // function dispatch
$formulary = $document->createFormulary ('adminSystem_server_edit');
if ($document->rewriteEngine or strpos (' ' . strtolower ($_SERVER['SERVER_SOFTWARE']), 'apache'))
$formulary->flags['server_is_apache'] = true;

if ($formulary->save ())
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_adminSystem_server

?>