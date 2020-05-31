<?php

class eclApp_sectionSearch
{ // class eclApp_sectionSearch

static function constructor_helper ($me)
{ // function constructor_helper
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->access (4))
$document->mod->editor->enable ();

$document->mod->panel->main = array ('content', 'formulary', 'list');
$document->mod->formulary = new eclMod_domainSearch_formulary ($document);
$document->mod->list = new eclMod_domainSearch_list ($document);
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionSearch

?>