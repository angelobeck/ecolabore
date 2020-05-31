<?php

class eclApp_adminDomains_add
{ // class eclApp_adminDomains_add

const name = '-new-domain';
const menuType = 'section';
const dataFrom = 'adminDomains_add_content';
const access = 4;

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$formulary = $document->createFormulary ('adminDomains_add_edit', [], 'domainAdd');

if ($formulary->command ('save') and $formulary->save ())
{ // formulary saved

$domain['name'] = $formulary->data['name'];
$domain['status'] = 1;
mkdir (PATH_DOMAINS . $domain['name']);

$domainId = $store->domain->insert ($domain);
$data = $formulary->data;
$data['mode'] = MODE_DOMAIN;
$data['parent_id'] = 0;
$data['name'] = '-register';
$data['text']['caption'] = $data['text']['title'];
$store->domainContent->insert ($domainId, $data);

$group = &$store->domainGroup->open ($domainId, 1);
$group[$data['admin_id']] = 4;

$document->received = [];
unset ($document->actions['domain']);
$parent = $document->application->parent;
$parent->reset ();
$document->application = $parent->child ($formulary->data['name']);
$document->application->dispatch ($document);
$document->reload = $document->url ();
return;
} // formulary saved

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_adminDomains_add

?>