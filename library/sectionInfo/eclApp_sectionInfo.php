<?php

class eclApp_sectionInfo
{ // class eclApp_sectionInfo

static function constructor_helper ($me)
{ // function constructor_helper
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->access (4))
$document->mod->editor->enable ();

$document->mod->formulary = $document->createFormulary ('domainInfo_view', $document->domain->data);
$document->mod->list = new eclMod_domainInfo_listAdministrators ($document);
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionInfo

?>