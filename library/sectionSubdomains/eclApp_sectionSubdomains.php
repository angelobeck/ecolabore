<?php

class eclApp_sectionSubdomains
{ // class eclApp_sectionSubdomains

static function constructor_helper ($me)
{ // function constructor_helper
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
if ($document->access (4))
$document->mod->editor->enable ();

$document->mod->list = new eclMod_sectionSubdomains_list ($document);
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionSubdomains

?>