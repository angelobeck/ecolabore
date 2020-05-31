<?php

class eclApp_adminDatabase
{ // class eclApp_adminDatabase

const name = 'database';
const menuType = 'section';
const dataFrom = 'adminDatabase_content';
const access = 4;
const map = 'adminDatabase_config adminDatabase_cryptography adminDatabase_query adminDatabase_log';

static function dispatch ($document)
{ // function dispatch
if (!is_dir (PATH_DATABASE))
{ // create folder
$formulary = $document->createFormulary ('adminDatabase_edit', [], 'createFolder');
if (!$formulary->command ('create') or !$formulary->save ())
{ // view form
$document->mod->formulary = $formulary;
return;
} // view form
} // create folder

$document->mod->list = 'admin_list';
} // function dispatch

} // class eclApp_adminDatabase

?>