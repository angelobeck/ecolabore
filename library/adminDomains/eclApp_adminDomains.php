<?php

class eclApp_adminDomains
{ // class eclApp_adminDomains

const name = 'domains';
const menuType = 'section';
const dataFrom = 'adminDomains_content';

static function constructor_helper ($me)
{ // function constructor_helper
global $io, $store;
if ($io->database->tableEnabled ($store->domain) and is_dir (PATH_DOMAINS))
$me->map = array ('adminDomains_abuseReports', 'adminDomains_add', 'adminDomains_details');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application;

if (!$io->database->tableEnabled ($store->domain))
{ // disabled
$document->dataMerge ('adminDomains_contentDisabled');
return;
} // disabled

if (!is_dir (PATH_DOMAINS) and !$document->access (4))
{ // no folder for visitors
$document->dataMerge ('adminDomains_contentEmpty');
return;
} // no folder for visitors

if (!is_dir (PATH_DOMAINS))
{ // create folder
$formulary = $document->createFormulary ('adminDomains_edit', [], 'createFolder');
if ($formulary->command ('create') and $formulary->save ())
{ // create
$folder_domains = PATH_ROOT . $io->systemConstants->get ('FOLDER_DOMAIN');
} // create
else
{ // view form
$document->mod->formulary = $formulary;
return;
} // view form
} // create folder

$document->mod->list = new eclMod_adminDomains_list ($document);
if (!isset ($folder_domains))
$folder_domains = PATH_DOMAINS;
if (count (scandir ($folder_domains)) == 2)
{ // empty
$document->dataMerge ('adminDomains_contentEmpty');
return;
} // empty
} // function dispatch

} // class eclApp_adminDomains

?>