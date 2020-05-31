<?php

class eclApp_sectionGlossary
{ // class eclApp_sectionGlossary

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('domainGlossary_keyword');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->access (4))
$document->mod->editor->enable ();

$document->mod->list = new eclMod_domainGlossary_list ($document);
} // function dispatch

static function remove ($me)
{ // function remove
global $store;

$data = &$store->domainContent->open ($me->domainId, '-register');
unset ($data['links']['sectionGlossary']);
} // function remove

} // class eclApp_sectionGlossary

?>