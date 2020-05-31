<?php

class eclApp_domainInfo
{ // class eclApp_domainInfo

const name = '-info';
const menuType = 'hidden';
const dataFrom = 'domainInfo_content';

static function dispatch ($document)
{ // function dispatch
$document->mod->formulary = $document->createFormulary ('domainInfo_view', $document->domain->data);
$document->mod->list = new eclMod_domainInfo_listAdministrators ($document);
$document->mod->panel->main = array ('formulary', 'list', 'content');
} // function dispatch

} // class eclApp_domainInfo

?>