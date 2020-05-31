<?php

class eclApp_sectionMenu
{ // class eclApp_sectionMenu

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('section', 'section_create');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$me = $document->application;

if ($document->access (4))
{ // admin

$pathway = $me->pathway;
$pathway[] = '-new-section';
$document->mod->context->appendChild ('section_create_contextNew')
->url ($pathway);
} // admin

$document->mod->list = new eclMod_sectionMenu_list ($document);
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionMenu

?>