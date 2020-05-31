<?php

class eclApp_domainAbuseReport
{ // class eclApp_domainAbuseReport

const name = '-abuse-report';
const menuType = 'hidden';
const dataFrom = 'domainAbuseReport_content';
const isDomain = true;

static function dispatch ($document)
{ // function dispatch
global $store;

if (isset ($document->actions['close']))
return $document->dataReplace ('layouts/dialog_cancel');

$formulary = $document->createFormulary ('domainAbuseReport_edit');

if ($formulary->command('cancel'))
return $document->dataReplace ('layouts/dialog_cancel');

if($formulary->command('save') and $formulary->save())
{ // save
$data = $formulary->data;
$data['mode'] = MODE_ABUSE_REPORT;
$data['folder'] = 'domain';
$data['name'] = $document->domain->name;
$data['domain_id'] = $document->domain->domainId;
$store->systemExtras->insert ($data);

$close = $document->createFormulary ('domainAbuseReport_close', [], 'close');
$close->action = '_close';
$document->mod->formulary = $close;
return;
} // save

if($formulary->errorMsg)
$document->mod->humperstilshen->alert($formulary->errorMsg);

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_domainInfo

?>