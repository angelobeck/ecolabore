<?php

class eclApp_sectionRedirect
{ // class eclApp_sectionRedirect

static function constructor_helper ($me)
{ // function constructor_helper
$me->data['hits']++;
$me->access = 0;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io;

if ($document->access (4))
{ // admin
$document->dataMerge ('sectionRedirect_content');
return;
} // admin

$me = $document->application;
if (!isset ($me->data['local']['url']) or !$me->data['local']['url'])
return;

$me->data['value']++;
$document->reload = $me->data['local']['url'];
$io->log->silent = true;
} // function dispatch

static function remove ($me)
{ // function remove
} // function remove

} // class eclApp_sectionRedirect

?>