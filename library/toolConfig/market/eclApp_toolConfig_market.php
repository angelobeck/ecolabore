<?php

class eclApp_toolConfig_market
{ // class eclApp_toolConfig_market

const name = 'market';
const menuType = 'section';
const dataFrom = 'toolConfig_market_content';

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$data = &$store->domainContent->open ($me->domainId, '-register');

$formulary = $document->createFormulary ('toolConfig_market_edit', $data);

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
} // save

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_toolConfig_market

?>