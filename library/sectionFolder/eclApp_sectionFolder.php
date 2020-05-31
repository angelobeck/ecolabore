<?php

class eclApp_sectionFolder
{ // class eclApp_sectionFolder

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('section', 'section_create');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$me = $document->application;

if ($document->access (4))
{ // admin
$document->mod->editor->enable ();

$pathway = $me->pathway;
$pathway[] = '-new-section';
$document->mod->context->appendChild ('section_create_contextNew')
->url ($pathway);
} // admin

$document->mod->list = new eclMod_sectionFolder_list ($document);
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionFolder

?>