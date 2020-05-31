<?php

class eclFilter_domainRecents_link
{ // class eclFilter_domainRecents_link

static function create ($fieldName, $control, $formulary)
{ // function create
$document = $formulary->document;
$me = $document->application;

// name
$control['name'] = $fieldName;

// type
if (isset ($control['flags']['type']))
$control['type'] = $control['flags']['type'];
else
$control['type'] = 'checkbox';

if (!$me->id)
{ // creating post
$control['value'] = 1;
$formulary->appendChild ($control);
return;
} // creating post

$recents = $document->domain->child ('-recents');
if (isset ($recents->data['links']['pages']) and in_array ($me->name, $recents->data['links']['pages']))
$control['value'] = 1;

$formulary->appendChild ($control);
} // function create

static function save ($fieldName, $control, $formulary)
{ // function save
global $store;
if ($formulary->errorMsg)
return;
if (!isset ($formulary->data['domain_id']))
return;
if (!isset ($formulary->data['name']))
return;

$received = $formulary->received;
$recents = $formulary->document->domain->child ('-recents');
if (!isset ($recents->data['links']['pages']))
$recents->data['links']['pages'] = [];
$pages = &$recents->data['links']['pages'];
$name = $formulary->data['name'];

if (isset ($received[$fieldName]))
{ // register
if (!in_array ($name, $pages))
array_unshift ($pages, $name);
} // register
elseif (in_array ($name, $pages))
{ // unregister
$key = array_search ($name, $pages);
unset ($pages[$key]);
} // unregister

if (count ($pages) > 20)
array_pop ($pages);
} // function save

} // class eclFilter_domainRecents_link

?>