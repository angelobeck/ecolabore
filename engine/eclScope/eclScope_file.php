<?php

class eclScope_file
{ // class eclScope_file

static function get ($render, $arguments)
{ // function get
global $store, $system;
$document = $render->document;
if (isset ($render->data['extras']))
$extras = $render->data['extras'];
else
$extras = [];

$number = $render->getVar ('mod.number');
if (!$number)
$number = '0';

$target = 'file_' . $number;
if (!isset ($extras[$target]))
{ // no media
$pathway = $render->getVar ('pathway');
if (!$pathway)
{ // get pathway
if (!isset ($render->data['id']) or !isset ($render->data['domain_id']))
return false;
$id = $render->data['id'];
$pathway = $store->domainContent->pathway ($render->data['domain_id'], $id);
} // get pathway

$me = $document->domain->parent;
foreach ($pathway as $folder)
{ // each folder
$me = $me->child ($folder);
if (!$me)
return;
} // each folder

if (!$document->access (4, $me->groups))
return;

$data['local']['editable'] = 1;
$domain = array_shift ($pathway);
array_unshift ($pathway, $domain, '-personalite', 'extras', $target);
$url = $document->url ($pathway);
$data['local']['url'] = "javaScript:humperstilshen.popUpOpen(" . TIC . $url . TIC . ", 300, 200)";
return $data;
} // no media

$data['local'] = $extras[$target];
if (isset ($data['local']['files']['file']))
{ // url
$data['local']['url_download'] = $document->urlFiles ($data['local']['files']['file'], true, '-downloads');
$data['local']['url_play'] = $document->urlFiles ($data['local']['files']['file'], true, '-play');
} // url
//!start version 3 compatibility
if (isset ($data['local']['file']))
{ // Version 3 compatibility
$data['local']['url_download'] = $document->urlFiles ($data['local']['file'], true, '-downloads');
$data['local']['url_play'] = $document->urlFiles ($data['local']['file'], true, '-play');
} // Version 3 compatibility
//!end version 3 compatibility

if (isset ($data['local']['play']))
$data['local']['play'] = strval ($data['local']['play']);
//!start version 3 compatibility
elseif (isset ($data['local']['plays']))
$data['local']['play'] = strval ($data['local']['plays']);
//!end version 3 compatibility

return $data;
} // function get

} // class eclScope_file

?>