<?php

class eclApp_toolConfig_versioning
{ // class eclApp_toolConfig_versioning

const name = 'versioning';
const menuType = 'section';
const dataFrom = 'toolConfig_versioning_content';

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$data = &$store->domainContent->open ($me->domainId, '-register');

$formulary = $document->createFormulary ('toolConfig_versioning_edit', $data);

if ($formulary->command ('clear'))
{ // clear cache
} // clear cache

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
} // save

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_toolConfig_versioning

?>